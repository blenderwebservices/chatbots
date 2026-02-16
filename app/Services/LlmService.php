<?php

namespace App\Services;

use App\Models\LlmModel;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Facades\Prism;
use Exception;
use Illuminate\Support\Facades\Log;

class LlmService
{
    /**
     * Check the connection to the LLM provider.
     *
     * @param LlmModel $llmModel
     * @return array
     */
    public function checkConnection(LlmModel $llmModel): array
    {
        try {
            $providerIdentifier = $llmModel->providerRelation->identifier;
            
            // Map internal provider identifiers to Prism Provider enum values
            // Currently supporting OpenAI and Gemini as per existing logic, but made generic
            $provider = match ($providerIdentifier) {
                'google' => Provider::Gemini,
                'microsoft' => Provider::OpenAI,
                default => Provider::tryFrom($providerIdentifier) ?? Provider::OpenAI,
            };

            $config = $llmModel->configuration ?? [];
            $apiKey = $llmModel->api_key;

            // Prism configuration for the request
            // We use a simple text generation request to verify connection
            // For some providers, listing models might be better but it's not standard in Prism yet across all
            $response = Prism::text()
                ->using($provider, $llmModel->identifier, [
                    'api_key' => $apiKey,
                    'url' => $config['base_url'] ?? null,
                ])
                ->withPrompt('Hello, are you online? Reply with "Yes".')
                ->usingTemperature(0)
                ->withMaxTokens(5)
                ->generate();

            $llmModel->update([
                'last_check_status' => 'success',
                'last_check_at' => now(),
            ]);

            return [
                'status' => 'success',
                'message' => 'Connection successful',
            ];

        } catch (Exception $e) {
            Log::error('LLM Connection Check Failed: ' . $e->getMessage());

            $llmModel->update([
                'last_check_status' => 'failed',
                'last_check_at' => now(),
            ]);

            return [
                'status' => 'error',
                'message' => 'Connection failed: ' . $e->getMessage(),
            ];
        }
    }
}
