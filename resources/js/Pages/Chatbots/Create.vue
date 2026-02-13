<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import FormSection from '@/Components/FormSection.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import ChatbotsForm from '@/Components/Chatbots/ChatbotsForm.vue'
import { createForm, store } from '@/Forms/chatbot.js'

const props = defineProps({
  chatbot: {
    type: Object,
    required: true,
  },
  models: {
    type: Array,
    required: true,
  },
})

const form = createForm(props.chatbot)

const handleSubmit = () => {
  store(form)
}
</script>

<template>
  <AppLayout title="Nuevo Chatbot">
    <template #header>
      <h1
        class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
      >
        Nuevo Chatbot
      </h1>
    </template>
    <section class="py-6" aria-label="Editar Chatbot">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <FormSection @submitted="handleSubmit">
          <template #title> Crear Chatbot</template>
          <template #description>
            Crea un nuevo chatbot para tu aplicación.
          </template>
          <template #form>
            <ChatbotsForm :form="form" :models="models" />
            <div class="col-span-6 mt-4 p-4 bg-indigo-50 dark:bg-indigo-900/30 rounded-xl border border-indigo-100 dark:border-indigo-800">
                <p class="text-sm text-indigo-700 dark:text-indigo-300 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Podrás agregar fuentes de conocimiento (PDFs y URLs) una vez que hayas creado el chatbot.
                </p>
            </div>
          </template>
          <template #actions>
            <PrimaryButton
              aria-label="Crear Chatbot"
              :class="{
                'cursor-not-allowed opacity-50':
                  form.processing,
              }"
              :disabled="form.processing"
            >
              Crear Chatbot
            </PrimaryButton>
          </template>
        </FormSection>
      </div>
    </section>
  </AppLayout>
</template>

<style scoped></style>
