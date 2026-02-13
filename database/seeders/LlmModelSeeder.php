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
        $models = [
            // OpenAI
            ['name' => 'GPT-4o', 'identifier' => 'gpt-4o', 'provider' => 'openai'],
            ['name' => 'GPT-4o Mini', 'identifier' => 'gpt-4o-mini', 'provider' => 'openai'],
            ['name' => 'GPT-4 Turbo', 'identifier' => 'gpt-4-turbo', 'provider' => 'openai'],
            
            // Anthropic
            ['name' => 'Claude 3.5 Sonnet', 'identifier' => 'claude-3-5-sonnet-latest', 'provider' => 'anthropic'],
            ['name' => 'Claude 3 Opus', 'identifier' => 'claude-3-opus-latest', 'provider' => 'anthropic'],
            
            // Google
            ['name' => 'Gemini 1.5 Pro', 'identifier' => 'gemini-1.5-pro', 'provider' => 'google'],
            ['name' => 'Gemini 1.5 Flash', 'identifier' => 'gemini-1.5-flash', 'provider' => 'google'],
            
            // xAI
            ['name' => 'Grok Beta', 'identifier' => 'grok-beta', 'provider' => 'xai'],
            
            // Groq (Llama)
            ['name' => 'Llama 3.3 70B (Groq)', 'identifier' => 'llama-3.3-70b-versatile', 'provider' => 'groq'],
            ['name' => 'Llama 3.1 8B (Groq)', 'identifier' => 'llama-3.1-8b-instant', 'provider' => 'groq'],
            ['name' => 'Mixtral 8x7b (Groq)', 'identifier' => 'mixtral-8x7b-32768', 'provider' => 'groq'],

            // Copilot (often used as Microsoft provider)
            ['name' => 'Copilot GPT-4o', 'identifier' => 'gpt-4o', 'provider' => 'microsoft'],
        ];

        foreach ($models as $model) {
            \App\Models\LlmModel::updateOrCreate(
                ['identifier' => $model['identifier'], 'provider' => $model['provider']],
                ['name' => $model['name'], 'active' => true]
            );
        }
    }
}
