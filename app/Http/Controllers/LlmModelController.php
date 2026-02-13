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
            'llmModels' => LlmModel::latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('LlmModels/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'identifier' => 'required|string|max:255',
            'provider' => 'required|string|max:255',
            'active' => 'boolean',
        ]);

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
            'provider' => 'required|string|max:255',
            'active' => 'boolean',
        ]);

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
