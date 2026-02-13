<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm, Link } from '@inertiajs/vue3'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import InputError from '@/Components/InputError.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SelectInput from '@/Components/SelectInput.vue'
import { ref } from 'vue'

const props = defineProps({
  chatbots: Array,
})

const form = useForm({
  name: '',
  chatbot_id: '',
  type: 'pdf',
  pdf: null,
  website: '',
})

const submit = () => {
    form.post(route('knowledge-sources.store'), {
        onSuccess: () => form.reset(),
    });
}
</script>

<template>
  <AppLayout title="Nueva Fuente de Conocimiento">
    <template #header>
      <h2 class="text-2xl font-bold leading-tight text-gray-800 dark:text-gray-100">
        Nueva Fuente de Conocimiento
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
                    <option value="" disabled>Selecciona un chatbot</option>
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
                placeholder="Ej: Manual de Usuario, Documentación API"
              />
              <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
              <InputLabel value="Tipo de Fuente" />
              <div class="mt-2 flex gap-4">
                <label class="flex items-center cursor-pointer">
                  <input type="radio" v-model="form.type" value="pdf" class="text-indigo-600 focus:ring-indigo-500 mr-2">
                  <span class="text-sm text-gray-700 dark:text-gray-300">PDF</span>
                </label>
                <label class="flex items-center cursor-pointer">
                  <input type="radio" v-model="form.type" value="website" class="text-indigo-600 focus:ring-indigo-500 mr-2">
                  <span class="text-sm text-gray-700 dark:text-gray-300">URL / Sitio Web</span>
                </label>
              </div>
            </div>

            <div v-if="form.type === 'pdf'">
                <InputLabel for="pdf" value="Archivo PDF" />
                <input 
                    id="pdf"
                    type="file" 
                    @input="form.pdf = $event.target.files[0]"
                    class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                    accept=".pdf"
                />
                <InputError class="mt-2" :message="form.errors.pdf" />
            </div>

            <div v-if="form.type === 'website'">
                <InputLabel for="website" value="URL del Sitio Web" />
                <TextInput
                    id="website"
                    v-model="form.website"
                    type="url"
                    class="mt-1 block w-full"
                    placeholder="https://ejemplo.com/docs"
                />
                <InputError class="mt-2" :message="form.errors.website" />
            </div>

            <div class="flex items-center justify-end pt-4">
              <Link :href="route('knowledge-sources.index')" class="mr-4 text-sm text-gray-600 hover:text-gray-900 underline">Cancelar</Link>
              <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Guardar Fuente
              </PrimaryButton>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
