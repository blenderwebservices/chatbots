<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Support\Facades\Http;

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
        $chat->messages()->create([
            'role' => 'user',
            'user_id' => $request->user()->id,
            'content' => $request->message,
        ]);

        // Send message to OpenAI and get the response
        // curl https://api.openai.com/v1/responses \
        //  -H "Content-Type: application/json" \
        //  -H "Authorization: Bearer $OPENAI_API_KEY" \
        //  -d '{
        //    "model": "gpt-5",
        //    "input": "Write a short bedtime story about a unicorn."
        //  }'

        $res = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.config('services.openai.key'),
        ])->post('https://api.openai.com/v1/responses', [
            'model' => 'gpt-5-nano',
            'input' => $request->message,
        ])->json();

        dd($res);

        return back();
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
