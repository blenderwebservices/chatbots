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

        return response()->eventStream(function () use ($chat) {
            $llmModel = $chat->chatbot?->llmModel;
            $messages = [];

            try {
                if (!$llmModel) {
                    throw new \Exception('No LLM model configured for this chatbot');
                }

                $providerRelation = $llmModel->providerRelation;
                if (!$providerRelation) {
                    throw new \Exception('No provider configured for the selected LLM model');
                }

                $providerIdentifier = $providerRelation->identifier;

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

                $fullUrl = rtrim((string) $baseUrl, '/') . $endpoint;

                yield ['delta' => "[API URL: {$fullUrl}]\n\n"];

                $messages = $chat->getPrismMessages();

                $prismRequest = Prism::text()
                    ->using($provider, $llmModel->identifier, array_filter([
                        'api_key' => $llmModel->api_key,
                        'url' => $config['base_url'] ?? null,
                    ]))
                    ->withSystemPrompt($chat->chatbot->buildSystemPrompt())
                    ->usingTemperature($chat->chatbot->temperature)
                    ->withMessages($messages)
                    ->onComplete(function ($pendingRequest, $messages) use ($chat) {
                        $assistantMessage = $messages->first();
                        $chat->messages()->create([
                            'role' => 'assistant',
                            'content' => $assistantMessage ? $assistantMessage->content : '',
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
            } catch (\Exception $e) {
                \Log::error("Chat API Error: " . $e->getMessage(), [
                    'chat_id' => $chat->id,
                    'exception' => $e
                ]);

                $llmService = new \App\Services\LlmService();
                $executionString = $llmModel ? $llmService->generateCurlCommand($llmModel, $messages) : 'No se pudo generar el comando curl.';

                yield [
                    'event' => 'llm-error',
                    'data' => json_encode([
                        'message' => $e->getMessage(),
                        'execution_string' => $executionString,
                    ])
                ];
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
