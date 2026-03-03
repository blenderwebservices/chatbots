<?php

use App\Models\LlmModel;
use App\Models\Provider;
use App\Services\LlmService;
use Prism\Prism\Facades\Prism;
use Prism\Prism\Testing\TextResponseFake;

it('includes the real API key in the execution string for Google/Gemini', function () {
    // Setup
    $provider = Provider::create([
        'name' => 'Google',
        'identifier' => 'google',
        'active' => true,
    ]);

    $llmModel = new LlmModel([
        'name' => 'Gemini Test',
        'identifier' => 'gemini-1.5-pro',
        'api_key' => 'TEST_API_KEY_123',
        'provider_id' => $provider->id,
    ]);
    $llmModel->setRelation('providerRelation', $provider);

    // Mock Prism call
    Prism::fake([
        TextResponseFake::make()->withText('Yes'),
    ]);

    $service = new LlmService();
    $result = $service->checkConnection($llmModel);

    expect($result)->toHaveKey('execution_string');
    expect($result['execution_string'])->toContain('https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-pro:generateContent?key=TEST_API_KEY_123');
});

it('includes the real API key in the execution string for OpenAI', function () {
    // Setup
    $provider = Provider::create([
        'name' => 'OpenAI',
        'identifier' => 'openai',
        'active' => true,
    ]);

    $llmModel = new LlmModel([
        'name' => 'OpenAI Test',
        'identifier' => 'gpt-4o',
        'api_key' => 'OPENAI_KEY_TEST',
        'provider_id' => $provider->id,
    ]);
    $llmModel->setRelation('providerRelation', $provider);

    // Mock Prism call
    Prism::fake([
        TextResponseFake::make()->withText('Yes'),
    ]);

    $service = new LlmService();
    $result = $service->checkConnection($llmModel);

    expect($result)->toHaveKey('execution_string');
    expect($result['execution_string'])->toContain('Authorization: Bearer OPENAI_KEY_TEST');
});
