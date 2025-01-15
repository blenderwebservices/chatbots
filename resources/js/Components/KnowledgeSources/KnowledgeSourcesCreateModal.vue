<script setup>
import DialogModal from '@/Components/DialogModal.vue'

const emit = defineEmits(['close'])

defineProps({
  show: {
    type: Boolean,
    required: true,
  },
})
</script>

<template>
  <DialogModal :show="show" @close="emit('close')">
    <template #title>
      Agregar fuente de conocimiento
    </template>
    <template #content>form fields</template>
    <template #footer>botones</template>
  </DialogModal>
</template>

<style scoped></style>
