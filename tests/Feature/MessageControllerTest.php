<?php

namespace Tests\Feature;

use App\Models\Chat;
use App\Models\Chatbot;
use App\Models\LlmModel;
use App\Models\User;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Prism\Prism\Enums\Provider;

class MessageControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_function_creates_user_message_and_streams_response()
    {
        $user = User::factory()->create();
        
        // Mock relations required by the controller
        $providerRelation = \App\Models\Provider::create(['name' => 'Microsoft', 'identifier' => 'microsoft']);
        
        $llmModel = LlmModel::create([
            'provider_id' => $providerRelation->id,
            'provider' => 'microsoft',
            'name' => 'GPT 4 mini',
            'identifier' => 'gpt-4o-mini',
            'api_key' => 'test-api-key',
            'configuration' => ['base_url' => 'https://api.openai.com/v1']
        ]);
        
        $chatbot = Chatbot::create([
            'name' => 'Support Bot',
            'user_id' => $user->id,
            'llm_model_id' => $llmModel->id,
            'system_prompt' => 'You are a helpful assistant',
            'temperature' => 0.7,
            'model' => 'gpt-4o-mini'
        ]);
        
        $chat = Chat::factory()->create([
            'name' => 'Test Chat',
            'user_id' => $user->id,
            'chatbot_id' => $chatbot->id,
        ]);

        // Note: Prism stream requests can be difficult to mock completely in standard HTTP tests 
        // without mocking the Http client or the Prism facade. But we can ensure that 
        // it initializes properly and the user message gets saved.

        $pendingRequestMock = \Mockery::mock(\Prism\Prism\Text\PendingRequest::class);
        $pendingRequestMock->shouldReceive('using')->andReturnSelf();
        $pendingRequestMock->shouldReceive('withSystemPrompt')->andReturnSelf();
        $pendingRequestMock->shouldReceive('usingTemperature')->andReturnSelf();
        $pendingRequestMock->shouldReceive('withMessages')->andReturnSelf();
        $pendingRequestMock->shouldReceive('onComplete')->andReturnSelf();
        
        $pendingRequestMock->shouldReceive('asStream')->andReturnUsing(function () {
            yield new \Prism\Prism\Streaming\Events\TextDeltaEvent(
                id: '1',
                timestamp: time(),
                delta: 'Hello!',
                messageId: 'msg-1'
            );
            yield new \Prism\Prism\Streaming\Events\StreamEndEvent(
                id: '1',
                timestamp: time(),
                finishReason: \Prism\Prism\Enums\FinishReason::Stop,
                usage: new \Prism\Prism\ValueObjects\Usage(2, 2)
            );
        });

        \Prism\Prism\Facades\Prism::shouldReceive('text')->andReturn($pendingRequestMock);

        $response = $this->actingAs($user)->postJson(route('chats.messages.store', ['chat' => $chat->id]), [
            'message' => 'Hello AI',
        ]);

        $response->assertStatus(200);

        // Read the streamed response content to see if there are internal errors overriding the save string
        $content = $response->streamedContent();
        if (str_contains($content, 'llm-error')) {
            dd($content);
        }

        // Assert the user message is saved in DB
        $this->assertDatabaseHas('messages', [
            'chat_id' => $chat->id,
            'role' => 'user',
            'content' => 'Hello AI',
        ]);
        
        // Assert the assistant message is also saved by the end of the generator processing
        $this->assertDatabaseHas('messages', [
            'chat_id' => $chat->id,
            'role' => 'assistant',
            'content' => 'Hello!',
        ]);
    }
    
    public function test_store_function_handles_missing_llm_model_gracefully()
    {
        $user = User::factory()->create();
        
        // Chatbot with NO llm_model relation
        $chatbot = Chatbot::create([
            'name' => 'Bad Bot',
            'user_id' => $user->id,
            'llm_model_id' => null,
            'system_prompt' => 'You are a bad assistant',
            'temperature' => 0.5,
            'model' => 'gpt-4o-mini'
        ]);
        
        $chat = Chat::factory()->create([
            'name' => 'Test chat 2',
            'user_id' => $user->id,
            'chatbot_id' => $chatbot->id,
        ]);

        // It streams, so it returns 200, but the event stream contains the error
        $response = $this->actingAs($user)->postJson(route('chats.messages.store', ['chat' => $chat->id]), [
            'message' => 'Hello AI'
        ]);

        $response->assertStatus(200);
        
        // Read the streamed response content
        $streamedContent = $response->streamedContent();
        $this->assertStringContainsString('No LLM model configured for this chatbot', $streamedContent);
    }
}
