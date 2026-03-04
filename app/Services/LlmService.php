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
                'execution_string' => $this->generateCurlCommand($llmModel),
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
                'execution_string' => $this->generateCurlCommand($llmModel),
            ];
        }
    }

    /**
     * Generate a curl command representation for the connection check.
     */
    public function generateCurlCommand(LlmModel $llmModel, array $messages = []): string
    {
        $providerIdentifier = $llmModel->providerRelation->identifier;
        $config = $llmModel->configuration ?? [];
        $apiKey = $llmModel->api_key ?: 'MISSING_API_KEY';
        $baseUrl = $config['base_url'] ?? null;
        $model = $llmModel->identifier;

        // Use the last message content if available, otherwise default to "Hello"
        $prompt = 'Hello';
        if (!empty($messages)) {
            $lastMessage = end($messages);
            if ($lastMessage instanceof \Prism\Prism\ValueObjects\Messages\UserMessage) {
                $prompt = $lastMessage->content;
            } elseif (is_array($lastMessage) && isset($lastMessage['content'])) {
                $prompt = $lastMessage['content'];
            }
        }

        return match ($providerIdentifier) {
            'microsoft', 'openai' => sprintf(
                "curl %s/chat/completions \\\n  -H \"Content-Type: application/json\" \\\n  -H \"Authorization: Bearer %s\" \\\n  -d '{\n    \"model\": \"%s\",\n    \"messages\": [{\"role\": \"user\", \"content\": \"%s\"}],\n    \"max_tokens\": 5\n  }'",
                $baseUrl ?: 'https://api.openai.com/v1',
                $apiKey,
                $model,
                addslashes($prompt)
            ),
            'google' => sprintf(
                "curl \"https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent?key=%s\" \\\n  -H \"Content-Type: application/json\" \\\n  -d '{\n    \"contents\": [{\"parts\": [{\"text\": \"%s\"}]}]\n  }'",
                $model,
                $apiKey,
                addslashes($prompt)
            ),
            'anthropic' => sprintf(
                "curl https://api.anthropic.com/v1/messages \\\n  -H \"x-api-key: %s\" \\\n  -H \"anthropic-version: 2023-06-01\" \\\n  -H \"Content-Type: application/json\" \\\n  -d '{\n    \"model\": \"%s\",\n    \"max_tokens\": 5,\n    \"messages\": [{\"role\": \"user\", \"content\": \"%s\"}]\n  }'",
                $apiKey,
                $model,
                addslashes($prompt)
            ),
            'groq' => sprintf(
                "curl https://api.groq.com/openai/v1/chat/completions \\\n  -H \"Authorization: Bearer %s\" \\\n  -H \"Content-Type: application/json\" \\\n  -d '{\n    \"messages\": [{\"role\": \"user\", \"content\": \"%s\"}],\n    \"model\": \"%s\"\n  }'",
                $apiKey,
                addslashes($prompt),
                $model
            ),
            default => "Comando curl no disponible para este proveedor ($providerIdentifier).",
        };
    }
}
