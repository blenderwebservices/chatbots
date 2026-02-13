<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm, Link } from '@inertiajs/vue3'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import InputError from '@/Components/InputError.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'

const props = defineProps({
  knowledgeSource: Object,
  chatbots: Array,
})

const form = useForm({
  name: props.knowledgeSource.name,
  chatbot_id: props.knowledgeSource.chatbot_id,
})

const submit = () => {
    form.put(route('knowledge-sources.update', props.knowledgeSource.id));
}
</script>

<template>
  <AppLayout title="Editar Fuente de Conocimiento">
    <template #header>
      <h2 class="text-2xl font-bold leading-tight text-gray-800 dark:text-gray-100">
        Editar Fuente: {{ knowledgeSource.name }}
      </h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
          <form @submit.prevent="submit" class="space-y-6">
            
            <div class="col-span-6">
                <InputLabel for="chatbot_id" value="Chatbot Asociado" />
                <select 
                    id="chatbot_id" 
                    v-model="form.chatbot_id" 
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    required
                >
                    <option v-for="bot in chatbots" :key="bot.id" :value="bot.id">
                        {{ bot.name }}
                    </option>
                </select>
                <InputError :message="form.errors.chatbot_id" class="mt-2" />
            </div>

            <div>
              <InputLabel for="name" value="Nombre de la Fuente" />
              <TextInput
                id="name"
                v-model="form.name"
                type="text"
                class="mt-1 block w-full"
                required
              />
              <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="p-4 bg-gray-50 dark:bg-gray-900/50 rounded-xl">
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    <strong class="text-gray-700 dark:text-gray-300">Tipo:</strong> {{ knowledgeSource.type.toUpperCase() }}<br>
                    <strong class="text-gray-700 dark:text-gray-300">Ruta:</strong> {{ knowledgeSource.path }}
                </p>
                <p class="mt-2 text-[10px] text-gray-400">
                    Por seguridad, el cambio de archivo o URL requiere crear una nueva fuente.
                </p>
            </div>

            <div class="flex items-center justify-end pt-4">
              <Link :href="route('knowledge-sources.index')" class="mr-4 text-sm text-gray-600 hover:text-gray-900 underline">Cancelar</Link>
              <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Actualizar Fuente
              </PrimaryButton>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
