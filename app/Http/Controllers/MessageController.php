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
            'microsoft' => Provider::OpenAI, // Prism doesn't have a direct Microsoft provider, typically used via OpenAI-compatible API
            default => Provider::from($providerIdentifier),
        };

        return response()->eventStream(function () use ($chat, $llmModel, $provider) {
            return Prism::text()
                ->using($provider, $llmModel->identifier, [
                    'api_key' => $llmModel->api_key,
                ])
                ->withSystemPrompt($chat->chatbot->buildSystemPrompt())
                ->usingTemperature($chat->chatbot->temperature)
                ->withMessages($chat->getPrismMessages())
                ->onComplete(function ($pendingRequest, $messages) use ($chat) {
                    $chat->messages()->create([
                        'role' => 'assistant',
                        'content' => $messages->first()->content,
                    ]);
                })
                ->asStream();
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
