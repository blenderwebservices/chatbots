<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LlmModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $providers = [
            ['name' => 'OpenAI', 'identifier' => 'openai'],
            ['name' => 'Anthropic', 'identifier' => 'anthropic'],
            ['name' => 'Google', 'identifier' => 'google'],
            ['name' => 'Groq', 'identifier' => 'groq'],
            ['name' => 'xAI', 'identifier' => 'xai'],
            ['name' => 'Microsoft', 'identifier' => 'microsoft'],
        ];

        foreach ($providers as $p) {
            \App\Models\Provider::updateOrCreate(
                ['identifier' => $p['identifier']],
                ['name' => $p['name'], 'active' => true]
            );
        }

        $models = [
            // OpenAI
            ['name' => 'GPT-4o', 'identifier' => 'gpt-4o', 'provider' => 'openai'],
            ['name' => 'GPT-4o Mini', 'identifier' => 'gpt-4o-mini', 'provider' => 'openai'],
            ['name' => 'GPT-4 Turbo', 'identifier' => 'gpt-4-turbo', 'provider' => 'openai'],
            ['name' => 'o1-preview', 'identifier' => 'o1-preview', 'provider' => 'openai'],
            ['name' => 'o1-mini', 'identifier' => 'o1-mini', 'provider' => 'openai'],

            // Anthropic
            ['name' => 'Claude 3.5 Sonnet', 'identifier' => 'claude-3-5-sonnet-latest', 'provider' => 'anthropic'],
            ['name' => 'Claude 3.5 Haiku', 'identifier' => 'claude-3-5-haiku-latest', 'provider' => 'anthropic'],
            ['name' => 'Claude 3 Opus', 'identifier' => 'claude-3-opus-latest', 'provider' => 'anthropic'],
            ['name' => 'Claude 3 Haiku', 'identifier' => 'claude-3-haiku-20240307', 'provider' => 'anthropic'],

            // Google
            ['name' => 'Gemini 1.5 Pro', 'identifier' => 'gemini-1.5-pro', 'provider' => 'google'],
            ['name' => 'Gemini 1.5 Flash', 'identifier' => 'gemini-1.5-flash', 'provider' => 'google'],
            ['name' => 'Gemini 2.0 Flash', 'identifier' => 'gemini-2.0-flash-exp', 'provider' => 'google'],

            // xAI
            ['name' => 'Grok Beta', 'identifier' => 'grok-beta', 'provider' => 'xai'],

            // Groq
            ['name' => 'DeepSeek R1 Distill Llama 70B (Groq)', 'identifier' => 'deepseek-r1-distill-llama-70b', 'provider' => 'groq'],
            ['name' => 'DeepSeek R1 Distill Qwen 32B (Groq)', 'identifier' => 'deepseek-r1-distill-qwen-32b', 'provider' => 'groq'],
            ['name' => 'Llama 3.3 70B (Groq)', 'identifier' => 'llama-3.3-70b-versatile', 'provider' => 'groq'],
            ['name' => 'Llama 3.1 70B (Groq)', 'identifier' => 'llama-3.1-70b-versatile', 'provider' => 'groq'],
            ['name' => 'Llama 3.1 8B (Groq)', 'identifier' => 'llama-3.1-8b-instant', 'provider' => 'groq'],
            ['name' => 'Llama 3.2 11B Vision (Groq)', 'identifier' => 'llama-3.2-11b-vision-preview', 'provider' => 'groq'],
            ['name' => 'Llama 3.2 3B (Groq)', 'identifier' => 'llama-3.2-3b-preview', 'provider' => 'groq'],
            ['name' => 'Llama 3.2 1B (Groq)', 'identifier' => 'llama-3.2-1b-preview', 'provider' => 'groq'],
            ['name' => 'Mixtral 8x7b (Groq)', 'identifier' => 'mixtral-8x7b-32768', 'provider' => 'groq'],
            ['name' => 'Gemma 2 9B (Groq)', 'identifier' => 'gemma2-9b-it', 'provider' => 'groq'],

            // Microsoft
            ['name' => 'Copilot GPT-4o', 'identifier' => 'gpt-4o', 'provider' => 'microsoft'],
        ];

        foreach ($models as $modelData) {
            $provider = \App\Models\Provider::where('identifier', $modelData['provider'])->first();

            \App\Models\LlmModel::updateOrCreate(
                ['identifier' => $modelData['identifier']],
                [
                    'name' => $modelData['name'],
                    'provider_id' => $provider->id,
                    'provider' => $modelData['provider'],
                    'active' => true
                ]
            );
        }
    }
}
