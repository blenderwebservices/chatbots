<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import FormSection from '@/Components/FormSection.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import ActionMessage from '@/Components/ActionMessage.vue'
import ChatbotsForm from '@/Components/Chatbots/ChatbotsForm.vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
  chatbot: {
    type: Object,
    required: true,
  },
})

const form = useForm({
  name: props.chatbot.name,
  system_prompt: props.chatbot.system_prompt,
  model: props.chatbot.model,
  temperature: String(props.chatbot.temperature),
})

const handleSubmit = () => {
  form.put(route('chatbots.update', props.chatbot.id), {
    preserveScroll: true,
  })
}
</script>

<template>
  <AppLayout :title="`Editar Chatbot: ${chatbot.name}`">
    <template #header>
      <h1
        class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
      >
        Chatbot: {{ chatbot.name }}
      </h1>
    </template>
    <section class="py-6" aria-label="Editar Chatbot">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <FormSection @submitted="handleSubmit">
          <template #title> Editar Chatbot</template>
          <template #description>
            Edita la información del chatbot.
          </template>
          <template #form>
            <ChatbotsForm :form="form" />
          </template>
          <template #actions>
            <ActionMessage
              :on="form.recentlySuccessful"
              class="me-3"
            >
              Cambios guardados.
            </ActionMessage>
            <PrimaryButton
              aria-label="Guardar Cambios"
              :class="{
                'cursor-not-allowed opacity-50':
                  form.processing,
              }"
              :disabled="form.processing"
            >
              Guardar Cambios
            </PrimaryButton>
          </template>
        </FormSection>
      </div>
    </section>
  </AppLayout>
</template>

<style scoped></style>
