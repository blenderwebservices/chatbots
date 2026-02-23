<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Models\Chat;
use App\Models\Message;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Facades\Prism;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Chat $chat, StoreMessageRequest $request)
    {
        $chat->load('chatbot.llmModel.providerRelation');

        $chat->messages()->create([
            'role' => 'user',
            'user_id' => $request->user()->id,
            'content' => $request->message,
        ]);

        $llmModel = $chat->chatbot->llmModel;

        if (!$llmModel) {
            throw new \Exception('No LLM model configured for this chatbot');
        }

        $providerIdentifier = $llmModel->providerRelation->identifier;

        // Map internal provider identifiers to Prism Provider enum values
        $provider = match ($providerIdentifier) {
            'google' => Provider::Gemini,
            'microsoft' => Provider::OpenAI,
            default => Provider::tryFrom($providerIdentifier) ?? Provider::OpenAI,
        };

        $config = $llmModel->configuration ?? [];

        $baseUrl = $config['base_url'] ?? match ($providerIdentifier) {
            'google' => config('prism.providers.gemini.url'),
            'microsoft' => config('prism.providers.openai.url'),
            default => config("prism.providers.{$providerIdentifier}.url") ?? config('prism.providers.openai.url'),
        };

        $endpoint = match ($providerIdentifier) {
            'google' => "/{$llmModel->identifier}:streamGenerateContent",
            default => "/chat/completions",
        };

        $fullUrl = rtrim($baseUrl, '/') . $endpoint;

        return response()->eventStream(function () use ($chat, $llmModel, $provider, $config, $fullUrl) {
            yield ['delta' => "[API URL: {$fullUrl}]\n\n"];

            $prismRequest = Prism::text()
                ->using($provider, $llmModel->identifier, array_filter([
                    'api_key' => $llmModel->api_key,
                    'url' => $config['base_url'] ?? null,
                ]))
                ->withSystemPrompt($chat->chatbot->buildSystemPrompt())
                ->usingTemperature($chat->chatbot->temperature)
                ->withMessages($chat->getPrismMessages())
                ->onComplete(function ($pendingRequest, $messages) use ($chat) {
                    $chat->messages()->create([
                        'role' => 'assistant',
                        'content' => $messages->first()->content,
                    ]);
                });

            foreach ($prismRequest->asStream() as $chunk) {
                // Ensure the chunk is serialized as an array if it's a Prism StreamEvent
                yield $chunk instanceof \Prism\Prism\Streaming\Events\StreamEvent ? $chunk->toArray() : $chunk;

                if ($chunk instanceof \Prism\Prism\Streaming\Events\StreamEndEvent) {
                    $metadata = "\n---";
                    $metadata .= "\n[Finish Reason: {$chunk->finishReason->name}]";
                    if ($chunk->usage) {
                        $totalTokens = $chunk->usage->promptTokens + $chunk->usage->completionTokens;
                        $metadata .= "\n[Tokens: P:{$chunk->usage->promptTokens} | C:{$chunk->usage->completionTokens} | T:{$totalTokens}]";
                    }
                    yield ['delta' => $metadata];
                }
            }
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMessageRequest $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        //
    }
}
