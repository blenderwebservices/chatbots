<?php

namespace App\Http\Controllers;

use App\Models\LlmModel;
use App\Services\LlmService;
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
            'configuration' => 'nullable|array',
            'configuration.base_url' => 'nullable|url',
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
            'configuration' => 'nullable|array',
            'configuration.base_url' => 'nullable|url',
        ]);

        $provider = \App\Models\Provider::find($validated['provider_id']);
        $validated['provider'] = $provider->identifier; // Kept for BC for now

        $llmModel->update($validated);

        return to_route('llm-models.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LlmModel $llmModel)
    {
        $llmModel->delete();

        return back();
    }

    /**
     * Check the connection to the LLM provider.
     */
    public function check(Request $request)
    {
        $validated = $request->validate([
            'api_key' => 'required|string',
            'identifier' => 'required|string',
            'provider_id' => 'required|exists:providers,id',
            'configuration' => 'nullable|array',
            'configuration.base_url' => 'nullable|url',
        ]);

        // Create a temporary LlmModel instance or use existing if ID provided
        // For now, we simulate with a temporary instance or just pass data to service if refactored
        // But LlmService expects LlmModel. Let's create a temporary one or mock it.
        // Actually, better to just use the service with data if possible, but let's stick to LlmModel for now.
        // If checking an existing model, we could use that.
        // If checking a new model form, we don't have an ID.
        
        $provider = \App\Models\Provider::find($validated['provider_id']);
        
        $llmModel = new LlmModel([
            'api_key' => $validated['api_key'],
            'identifier' => $validated['identifier'],
            'provider_id' => $validated['provider_id'],
            'configuration' => $validated['configuration'] ?? [],
        ]);
        
        // We need to manually set relation for the service to work without saving
        $llmModel->setRelation('providerRelation', $provider);

        $service = new \App\Services\LlmService();
        $result = $service->checkConnection($llmModel);

        return response()->json($result);
    }
}
