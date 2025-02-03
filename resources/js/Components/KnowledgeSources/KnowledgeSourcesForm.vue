<script setup>
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import InputError from '@/Components/InputError.vue'
import SelectInput from '@/Components/SelectInput.vue'

defineProps({
  form: Object,
})
const sourceTypes = [
  { value: 'pdf', label: 'PDF' },
  { value: 'website', label: 'Website' },
]
</script>

<template>
  <div class="space-y-4">
    <div class="col-span-6 sm:col-span-4">
      <InputLabel for="name" value="Nombre" />
      <TextInput
        id="name"
        v-model="form.name"
        class="mt-1 block w-full"
      />
      <InputError
        :message="form.errors.name"
        class="mt-2"
      />
    </div>
    <div class="col-span-6 sm:col-span-4">
      <InputLabel for="type" value="Tipo" />
      <SelectInput
        id="type"
        :options="sourceTypes"
        v-model="form.type"
        class="mt-1 block w-full"
      />

      <InputError
        :message="form.errors.type"
        class="mt-2"
      />
    </div>
    <div
      v-if="form.type === 'pdf'"
      class="col-span-6 sm:col-span-4"
    >
      <InputLabel for="pdf" value="PDF" />
      <TextInput
        id="pdf"
        type="file"
        v-model="form.pdf"
        class="mt-1 block w-full"
      />
      <InputError :message="form.errors.pdf" class="mt-2" />
    </div>
    <div
      v-if="form.type === 'website'"
      class="col-span-6 sm:col-span-4"
    >
      <InputLabel for="website" value="Website" />
      <TextInput
        id="website"
        v-model="form.website"
        class="mt-1 block w-full"
      />
      <InputError
        :message="form.errors.website"
        class="mt-2"
      />
    </div>
  </div>
</template>

<style scoped></style>
