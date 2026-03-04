<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import ChatInput from '@/Components/Chats/ChatInput.vue'
import ChatMessage from '@/Components/Chats/ChatMessage.vue'
import ChatTitle from '@/Components/Chats/ChatTitle.vue'
import { nextTick, ref, useTemplateRef, computed } from 'vue'
import { useStream } from '@laravel/stream-vue'
import { createParser } from 'eventsource-parser'

const props = defineProps({
  chat: Object,
  messages: Array,
})
const chatContainer = useTemplateRef('chatContainer')
const assistantMessage = ref('')
const errorMessage = ref('')
const errorExecutionString = ref('')

const currentExecutionString = computed(() => {
  const llmModel = props.chat.chatbot?.llm_model
  if (!llmModel) return 'No hay modelo configurado'

  const providerIdentifier = llmModel.provider_relation?.identifier
  const apiKey = llmModel.api_key || 'MISSING_API_KEY'
  const model = llmModel.identifier || 'MODEL_ID'
  const config = llmModel.configuration || {}
  const baseUrl = config.base_url

  // If we have an error execution string from the last failed attempt, show that
  if (errorExecutionString.value) {
    return errorExecutionString.value
  }

  // Use the last message content if available, otherwise default to "Hola"
  const lastUserMessage = [...props.messages].reverse().find(m => m.role === 'user')
  const prompt = lastUserMessage ? lastUserMessage.content : 'Hola'

  if (providerIdentifier === 'google') {
    return `curl "https://generativelanguage.googleapis.com/v1beta/models/${model}:generateContent?key=${apiKey}" \\\n  -H "Content-Type: application/json" \\\n  -d '{\n    "contents": [{"parts": [{"text": "${prompt.replace(/'/g, "'\\''")}"}]}]\n  }'`
  }

  if (providerIdentifier === 'openai' || providerIdentifier === 'microsoft') {
    return `curl ${baseUrl || 'https://api.openai.com/v1'}/chat/completions \\\n  -H "Content-Type: application/json" \\\n  -H "Authorization: Bearer ${apiKey}" \\\n  -d '{\n    "model": "${model}",\n    "messages": [{"role": "user", "content": "${prompt.replace(/'/g, "'\\''")}"}],\n    "max_tokens": 100\n  }'`
  }

  if (providerIdentifier === 'anthropic') {
    return `curl https://api.anthropic.com/v1/messages \\\n  -H "x-api-key: ${apiKey}" \\\n  -H "anthropic-version: 2023-06-01" \\\n  -H "Content-Type: application/json" \\\n  -d '{\n    "model": "${model}",\n    "max_tokens": 100,\n    "messages": [{"role": "user", "content": "${prompt.replace(/'/g, "'\\''")}"}]\n  }'`
  }

  if (providerIdentifier === 'groq') {
    return `curl https://api.groq.com/openai/v1/chat/completions \\\n  -H "Authorization: Bearer ${apiKey}" \\\n  -H "Content-Type: application/json" \\\n  -d '{\n    "messages": [{"role": "user", "content": "${prompt.replace(/'/g, "'\\''")}"}],\n    "model": "${model}"\n  }'`
  }

  return providerIdentifier ? `Comando curl no disponible para este proveedor (${providerIdentifier}).` : 'Información de proveedor no disponible.'
})

const scrollToBottom = () => {
  chatContainer.value.style.paddingBottom =
    'calc(100vh - 340px)'
  chatContainer.value.scrollTo({
    top: chatContainer.value.scrollHeight,
    behavior: 'smooth',
  })
}
const streamUrl = route('chats.messages.store', {
  chat: props.chat.id,
})

const parser = createParser({
  onEvent: event => {
    if (event.data.includes('</stream>')) {
      return
    }

    if (event.event === 'llm-error') {
      try {
        const errorData = JSON.parse(event.data)
        errorMessage.value = errorData.message
        errorExecutionString.value = errorData.execution_string
      } catch (e) {
        errorMessage.value = 'Error al procesar los datos de error del servidor.'
      }
      return
    }

    try {
      const data = JSON.parse(event.data)
      // Prism uses 'delta' in toArray() for TextDeltaEvent, 
      // but let's be flexible and support common variations
      const text = data.delta || data.text_delta || data.textDelta
      
      if (text !== undefined) {
        assistantMessage.value += text
      }
    } catch (e) {
      // Ignore parsing errors for individual chunks if they are not JSON
    }
  },
})

const { send, isFetching, isStreaming } = useStream(
  streamUrl,
  {
    onData: data => {
      parser.feed(data)
    },
    onError: error => {
      console.error(error)
      // Only set generic error if we don't have a specific one from llm-error
      if (!errorMessage.value) {
        errorMessage.value = 'Ocurrió un error al procesar la solicitud.'
      }
    },
    onFinish: () => {
      if (assistantMessage.value) {
        props.messages.push({
          content: assistantMessage.value,
          role: 'assistant',
          created_at: new Date(),
        })
        assistantMessage.value = ''
      }
    },
  }
)

const copyErrorCommand = () => {
  navigator.clipboard.writeText(currentExecutionString.value)
}

const sendMessage = async message => {
  errorMessage.value = ''
  errorExecutionString.value = ''
  
  props.messages.push({
    content: message,
    role: 'user',
    created_at: new Date(),
  })
  await nextTick()
  // scroll
  scrollToBottom()
  // enviar mensaje al chat
  send({ message })
}
</script>

<template>
  <AppLayout :title="chat.name">
    <template #header>
      <ChatTitle :chat="chat" />
    </template>
    <div
      class="flex h-[calc(100vh-140px)] flex-col bg-gray-100 dark:bg-gray-900"
    >
      <div
        ref="chatContainer"
        class="mx-auto max-w-7xl flex-1 space-y-4 overflow-y-auto p-6"
      >
        <ChatMessage
          :message="message"
          v-if="messages.length > 0"
          v-for="message in messages"
          :key="message.id"
        />
        <ChatMessage
          v-if="assistantMessage"
          :message="{
            role: 'assistant',
            content: assistantMessage,
            created_at: new Date(),
          }"
        />
        
        <!-- Error Display -->
        <div v-if="errorMessage" class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-red-200 dark:border-red-900/50 my-4">
          <div class="flex items-center gap-3 text-red-600 dark:text-red-400 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <h3 class="font-bold">Error del API de LLM</h3>
          </div>
          
          <p class="text-sm text-gray-700 dark:text-gray-300">{{ errorMessage }}</p>
        </div>

        <!-- Connection String Display (Always Visible) -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 my-4">
          <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Comando de ejecución:</p>
          <div class="relative group">
            <pre class="p-4 bg-gray-900 text-gray-300 rounded-xl overflow-x-auto text-[10px] font-mono border border-gray-700 shadow-inner"><code>{{ currentExecutionString }}</code></pre>
            <button 
              type="button"
              @click="copyErrorCommand"
              class="absolute top-2 right-2 p-1.5 bg-gray-800 hover:bg-gray-700 text-gray-400 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity"
              title="Copiar comando"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
              </svg>
            </button>
          </div>
        </div>

        <ChatMessage
          v-if="(isStreaming || isFetching) && !errorMessage"
          :message="{ role: 'assistant' }"
        >
          <span class="flex gap-1">
            <span
              v-for="i in 3"
              :key="i"
              class="block size-1 animate-pulse rounded-full bg-indigo-500 dark:bg-indigo-400"
              :style="{ animationDelay: i * 0.25 + 's' }"
            />
          </span>
        </ChatMessage>
      </div>
      <ChatInput @messageSent="sendMessage" :chat="chat" />
    </div>
  </AppLayout>
</template>
