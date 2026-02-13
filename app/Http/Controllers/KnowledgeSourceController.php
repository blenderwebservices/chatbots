<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKnowledgeSourceRequest;
use App\Http\Requests\UpdateKnowledgeSourceRequest;
use App\Jobs\ProcessKnowledgeSourceJob;
use App\Models\Chatbot;
use App\Models\KnowledgeSource;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class KnowledgeSourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('KnowledgeSources/Index', [
            'knowledgeSources' => KnowledgeSource::with('chatbot')->latest()->get(),
            'chatbots' => Chatbot::latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('KnowledgeSources/Create', [
            'chatbots' => Chatbot::latest()->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKnowledgeSourceRequest $request, ?Chatbot $chatbot = null)
    {
        $validated = $request->validated();

        $chatbotId = $chatbot?->id ?? $validated['chatbot_id'] ?? null;

        if (!$chatbotId) {
            return back()->withErrors(['chatbot_id' => 'Debe seleccionar un chatbot.']);
        }

        $knowledgeSource = new KnowledgeSource([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'chatbot_id' => $chatbotId,
        ]);

        $knowledgeSource->path = match ($validated['type']) {
            'pdf' => $validated['pdf']->store('pdfs'),
            'website' => $validated['website'],
        };

        $knowledgeSource->save();

        ProcessKnowledgeSourceJob::dispatch($knowledgeSource);

        if ($request->header('X-Inertia-Partial-Component')) {
            return back()->with('flash.banner', 'Fuente agregada.');
        }

        return to_route('knowledge-sources.index')->with('flash.banner', 'Fuente agregada.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Chatbot $chatbot, KnowledgeSource $knowledgeSource)
    {
        if ($knowledgeSource->type === 'pdf') {
            return Storage::response(
                $knowledgeSource->path,
                $knowledgeSource->name,
                [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline',
                ]
            );
        }

        return redirect($knowledgeSource->path);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KnowledgeSource $knowledgeSource)
    {
        return Inertia::render('KnowledgeSources/Edit', [
            'knowledgeSource' => $knowledgeSource,
            'chatbots' => Chatbot::latest()->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKnowledgeSourceRequest $request, KnowledgeSource $knowledgeSource)
    {
        $validated = $request->validated();

        $knowledgeSource->update([
            'name' => $validated['name'],
            'chatbot_id' => $validated['chatbot_id'] ?? $knowledgeSource->chatbot_id,
        ]);

        return to_route('knowledge-sources.index')->with('flash.banner', 'Fuente de conocimiento actualizada.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chatbot $chatbot, KnowledgeSource $knowledgeSource)
    {
        // authorization
        $deleted = $knowledgeSource->delete();

        if (!$deleted) {
            return back()->with('flash', [
                'banner' => 'Hubo un problema al eliminar la fuente de conocimiento.',
                'bannerStyle' => 'danger',
            ]);
        }

        if ($knowledgeSource->type === 'pdf') {
            Storage::delete($knowledgeSource->path);
        }

        return back()->with('flash.banner', 'Eliminado.');

    }
}
