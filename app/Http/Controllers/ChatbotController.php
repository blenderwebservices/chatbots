<?php

namespace App\Http\Controllers;

use App\Models\Chatbot;
use App\Models\LlmModel;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChatbotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Inertia::render('Chatbots/Index', [
            'chatbots' => $request->user()->chatbots,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Chatbots/Create', [
            'chatbot' => new Chatbot,
            'models' => LlmModel::where('active', true)->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'system_prompt' => 'required|string',
            'model' => 'required|string',
            'temperature' => 'required|numeric|min:0|max:2',
        ]);

        $chatbot = $request->user()
            ->chatbots()
            ->create($validated);

        return to_route('chatbots.show', $chatbot);
    }

    /**
     * Display the specified resource.
     */
    public function show(Chatbot $chatbot)
    {
        $chatbot->load('knowledgeSources');

        return Inertia::render('Chatbots/Show', [
            'chatbot' => $chatbot,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chatbot $chatbot)
    {
        return Inertia::render('Chatbots/Edit', [
            'chatbot' => $chatbot,
            'models' => LlmModel::where('active', true)->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chatbot $chatbot)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'system_prompt' => 'required|string',
            'model' => 'required|string',
            'temperature' => 'required|numeric|min:0|max:2',
        ]);

        $chatbot->update($validated);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chatbot $chatbot)
    {
        //
    }
}
