<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm, Link } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import InputError from '@/Components/InputError.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import Checkbox from '@/Components/Checkbox.vue'

const props = defineProps({
  llmModel: Object,
  providers: Array,
})

const form = useForm({
  name: props.llmModel.name,
  identifier: props.llmModel.identifier,
  api_key: props.llmModel.api_key || '',
  provider_id: props.llmModel.provider_id,
  active: props.llmModel.active,
  configuration: props.llmModel.configuration || { base_url: '' },
})

const checkStatus = ref(null)
const checkMessage = ref('')
const isChecking = ref(false)

const currentExecutionString = computed(() => {
  const provider = props.providers.find(p => p.id === form.provider_id)
  const providerIdentifier = provider ? provider.identifier : ''
  const apiKey = form.api_key || 'MISSING_API_KEY'
  const model = form.identifier || 'MODEL_ID'
  const baseUrl = form.configuration.base_url

  if (providerIdentifier === 'google') {
    return `curl "https://generativelanguage.googleapis.com/v1beta/models/${model}:generateContent?key=${apiKey}" \\\n  -H "Content-Type: application/json" \\\n  -d '{\n    "contents": [{"parts": [{"text": "Hello"}]}]\n  }'`
  }

  if (providerIdentifier === 'openai' || providerIdentifier === 'microsoft') {
    return `curl ${baseUrl || 'https://api.openai.com/v1'}/chat/completions \\\n  -H "Content-Type: application/json" \\\n  -H "Authorization: Bearer ${apiKey}" \\\n  -d '{\n    "model": "${model}",\n    "messages": [{"role": "user", "content": "Hello"}],\n    "max_tokens": 5\n  }'`
  }

  if (providerIdentifier === 'anthropic') {
    return `curl https://api.anthropic.com/v1/messages \\\n  -H "x-api-key: ${apiKey}" \\\n  -H "anthropic-version: 2023-06-01" \\\n  -H "Content-Type: application/json" \\\n  -d '{\n    "model": "${model}",\n    "max_tokens": 5,\n    "messages": [{"role": "user", "content": "Hello"}]\n  }'`
  }

  if (providerIdentifier === 'groq') {
    return `curl https://api.groq.com/openai/v1/chat/completions \\\n  -H "Authorization: Bearer ${apiKey}" \\\n  -H "Content-Type: application/json" \\\n  -d '{\n    "messages": [{"role": "user", "content": "Hello"}],\n    "model": "${model}"\n  }'`
  }

  return providerIdentifier ? `Comando curl no disponible para este proveedor (${providerIdentifier}).` : 'Seleccione un proveedor para ver el comando.'
})

const checkConnection = async () => {
  if (!form.api_key) {
    checkStatus.value = 'error'
    checkMessage.value = 'Por favor, introduce una API Key'
    return
  }

  isChecking.value = true
  checkStatus.value = null
  checkMessage.value = ''

  try {
    const response = await fetch(route('llm-models.check'), {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
      },
      body: JSON.stringify({
        api_key: form.api_key,
        identifier: form.identifier,
        provider_id: form.provider_id,
        configuration: form.configuration,
      }),
    })

    const data = await response.json()
    
    if (data.status === 'success') {
      checkStatus.value = 'success'
      checkMessage.value = data.message
    } else {
      checkStatus.value = 'error'
      checkMessage.value = data.message
    }
  } catch (error) {
    checkStatus.value = 'error'
    checkMessage.value = 'Error al verificar la conexión'
  } finally {
    isChecking.value = false
  }
}

const copyExecutionString = () => {
  navigator.clipboard.writeText(currentExecutionString.value)
}

const submit = () => {
  form.put(route('llm-models.update', props.llmModel.id))
}
</script>

<template>
  <AppLayout :title="`Editar Modelo: ${llmModel.name}`">
    <template #header>
        <h2 class="text-2xl font-bold leading-tight text-gray-800 dark:text-gray-100">
            Editar Modelo: {{ llmModel.name }}
        </h2>
    </template>

    <div class="max-w-2xl bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
      <form @submit.prevent="submit" class="space-y-6">
        <div>
          <InputLabel for="name" value="Nombre del Modelo" />
          <TextInput
            id="name"
            v-model="form.name"
            type="text"
            class="mt-1 block w-full"
            required
            autofocus
          />
          <InputError class="mt-2" :message="form.errors.name" />
        </div>

        <div>
          <InputLabel for="identifier" value="Identificador de la API" />
          <TextInput
            id="identifier"
            v-model="form.identifier"
            type="text"
            class="mt-1 block w-full"
            required
          />
          <InputError class="mt-2" :message="form.errors.identifier" />
        </div>

        <div>
          <InputLabel for="api_key" value="API Key" />
          <TextInput
            id="api_key"
            v-model="form.api_key"
            type="password"
            class="mt-1 block w-full"
            placeholder="Introduce la clave de API"
          />
          <InputError class="mt-2" :message="form.errors.api_key" />
          <p class="mt-1 text-xs text-gray-500">La clave privada para autenticar con el proveedor.</p>
        </div>

        <div>
          <InputLabel for="base_url" value="Base URL (Opcional)" />
          <TextInput
            id="base_url"
            v-model="form.configuration.base_url"
            type="text"
            class="mt-1 block w-full"
            placeholder="Ej: https://api.openai.com/v1"
          />
          <InputError class="mt-2" :message="form.errors['configuration.base_url']" />
          <p class="mt-1 text-xs text-gray-500">URL base personalizada para proveedores genéricos o compatibles.</p>
        </div>

        <div>
          <InputLabel for="provider_id" value="Proveedor" />
          <select
            id="provider_id"
            v-model="form.provider_id"
            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
            required
          >
            <option value="" disabled>Seleccione un proveedor</option>
            <option v-for="provider in providers" :key="provider.id" :value="provider.id">
              {{ provider.name }}
            </option>
          </select>
          <InputError class="mt-2" :message="form.errors.provider_id" />
        </div>

        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
          <button
            type="button"
            @click="checkConnection"
            :disabled="!form.api_key || isChecking"
            class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50"
          >
            <span v-if="isChecking">Verificando...</span>
            <span v-else>Verificar Conexión</span>
          </button>

          <div v-if="checkStatus" class="mt-3 p-3 rounded-lg" :class="checkStatus === 'success' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200'">
            <p class="text-sm font-medium">{{ checkMessage }}</p>
          </div>

          <div class="mt-4">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Comando de ejecución:</p>
            <div class="relative group">
              <pre class="p-4 bg-gray-900 text-gray-300 rounded-xl overflow-x-auto text-[10px] font-mono border border-gray-700 shadow-inner"><code>{{ currentExecutionString }}</code></pre>
              <button 
                type="button"
                @click="copyExecutionString"
                class="absolute top-2 right-2 p-1.5 bg-gray-800 hover:bg-gray-700 text-gray-400 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity"
                title="Copiar comando"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
              </button>
            </div>
          </div>
        </div>

        <div class="block">
          <label class="flex items-center">
            <Checkbox v-model:checked="form.active" name="active" />
            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Modelo Activo</span>
          </label>
        </div>

        <div class="flex items-center justify-end">
          <Link :href="route('llm-models.index')" class="mr-4 text-sm text-gray-600 hover:text-gray-900 underline">Cancelar</Link>
          <PrimaryButton 
            :class="{ 'opacity-25': form.processing || (!form.api_key) }" 
            :disabled="form.processing || (!form.api_key)"
          >
            Actualizar Modelo
          </PrimaryButton>
        </div>
      </form>
    </div>
  </AppLayout>
</template>
