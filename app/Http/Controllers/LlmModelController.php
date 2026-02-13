<?php

namespace App\Http\Controllers;

use App\Models\LlmModel;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LlmModelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('LlmModels/Index', [
            'llmModels' => LlmModel::with('providerRelation')->latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('LlmModels/Create', [
            'providers' => \App\Models\Provider::where('active', true)->orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'identifier' => 'required|string|max:255',
            'api_key' => 'nullable|string',
            'provider_id' => 'required|exists:providers,id',
            'active' => 'boolean',
        ]);

        $provider = \App\Models\Provider::find($validated['provider_id']);
        $validated['provider'] = $provider->identifier; // Kept for BC for now

        LlmModel::create($validated);

        return to_route('llm-models.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LlmModel $llmModel)
    {
        return Inertia::render('LlmModels/Edit', [
            'llmModel' => $llmModel,
            'providers' => \App\Models\Provider::where('active', true)->orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LlmModel $llmModel)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'identifier' => 'required|string|max:255',
            'api_key' => 'nullable|string',
            'provider_id' => 'required|exists:providers,id',
            'active' => 'boolean',
        ]);

        $provider = \App\Models\Provider::find($validated['provider_id']);
        $validated['provider'] = $provider->identifier; // Kept for BC for now

        $llmModel->update($validated);

        return to_route('llm-models.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LlmModel $llmModel)
    {
        $llmModel->delete();

        return back();
    }
}
