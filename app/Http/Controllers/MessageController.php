<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Models\Chat;
use App\Models\Message;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Prism;

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
        $chat->load('chatbot');

        $chat->messages()->create([
            'role' => 'user',
            'user_id' => $request->user()->id,
            'content' => $request->message,
        ]);

        return response()->eventStream(function () use ($chat) {
            return Prism::text()
                ->using(Provider::OpenAI, $chat->chatbot->model)
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
