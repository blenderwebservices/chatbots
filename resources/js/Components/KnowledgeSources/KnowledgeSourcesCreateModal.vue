<script setup>
import DialogModal from '@/Components/DialogModal.vue'
import KnowledgeSourcesForm from '@/Components/KnowledgeSources/KnowledgeSourcesForm.vue'
import { useForm } from '@inertiajs/vue3'

const emit = defineEmits(['close'])

defineProps({
  show: {
    type: Boolean,
    required: true,
  },
})

const form = useForm({
  name: '',
  type: 'pdf',
  pdf: null,
  website: '',
})
</script>

<template>
  <DialogModal :show="show" @close="emit('close')">
    <template #title>
      Agregar fuente de conocimiento
    </template>
    <template #content>
      <KnowledgeSourcesForm :form="form" />
    </template>
    <template #footer>botones</template>
  </DialogModal>
</template>

<style scoped></style>
