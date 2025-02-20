<script setup>
import DialogModal from '@/Components/DialogModal.vue'
import KnowledgeSourcesForm from '@/Components/KnowledgeSources/KnowledgeSourcesForm.vue'
import { useForm } from '@inertiajs/vue3'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'

const emit = defineEmits(['close'])

const props = defineProps({
  show: {
    type: Boolean,
    required: true,
  },
  chatbotId: {
    type: String,
    required: true,
  },
})

const form = useForm({
  name: '',
  type: 'pdf',
  pdf: null,
  website: '',
})
const handleSubmit = () => {
  form.post(
    route('chatbots.knowledge-sources.store', {
      chatbot: props.chatbotId,
    }),
    {
      preserveScroll: true,
      onSuccess: () => {
        emit('close')
      },
    }
  )
}
</script>

<template>
  <DialogModal :show="show" @close="emit('close')">
    <template #title>
      Agregar fuente de conocimiento
    </template>
    <template #content>
      <pre>{{ form.errors }}</pre>
      <KnowledgeSourcesForm :form="form" />
    </template>
    <template #footer>
      <div class="flex w-full justify-between space-x-4">
        <SecondaryButton
          aria-label="Cerrar"
          @click="emit('close')"
        >
          Cerrar
        </SecondaryButton>
        <PrimaryButton
          @click="handleSubmit"
          aria-label="Guardar"
        >
          Guardar
        </PrimaryButton>
      </div>
    </template>
  </DialogModal>
</template>

<style scoped></style>
