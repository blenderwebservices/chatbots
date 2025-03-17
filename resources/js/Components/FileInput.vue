<script setup>
import { onMounted, ref } from 'vue'

defineProps({
  modelValue: Object,
})

defineEmits(['update:modelValue'])

const input = ref(null)

onMounted(() => {
  if (input.value.hasAttribute('autofocus')) {
    input.value.focus()
  }
})

defineExpose({ focus: () => input.value.focus() })
</script>

<template>
  <input
    ref="input"
    type="file"
    class="rounded-md border border-gray-300 p-2 shadow-sm file:mr-2 file:cursor-pointer file:rounded file:border-none file:bg-gray-200 file:px-3 file:py-1 file:text-gray-800 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 file:dark:bg-gray-700 file:dark:text-white dark:focus:border-indigo-600 dark:focus:ring-indigo-600"
    @input="
      $emit('update:modelValue', $event.target.files[0])
    "
  />
</template>
