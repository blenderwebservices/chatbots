<script setup>
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import TextArea from '@/Components/TextArea.vue'
import SelectInput from '@/Components/SelectInput.vue'
import InputError from '@/Components/InputError.vue'

defineProps({
  form: {
    type: Object,
    required: true,
  },
  models: {
    type: Array,
    required: true,
  },
})
</script>

<template>
  <div class="col-span-6 sm:col-span-4">
    <InputLabel for="name" value="Nombre" />
    <TextInput
      id="name"
      v-model="form.name"
      class="mt-1 block w-full"
    />
    <InputError :message="form.errors.name" class="mt-2" />
  </div>
  <div class="col-span-6 sm:col-span-4">
    <InputLabel for="system_prompt" value="System Prompt" />
    <TextArea
      id="system_prompt"
      v-model="form.system_prompt"
      class="mt-1 block w-full"
    />
    <InputError
      :message="form.errors.system_prompt"
      class="mt-2"
    />
  </div>
  <div class="col-span-6 sm:col-span-4">
    <InputLabel for="llm_model_id" value="Model" />
    <SelectInput
      id="llm_model_id"
      v-model="form.llm_model_id"
      class="mt-1 block w-full"
      placeholder="Select a model"
      :options="
        models.map(model => ({
          label: model.name,
          value: model.id,
        }))
      "
    />
    <InputError :message="form.errors.llm_model_id" class="mt-2" />
  </div>
  <div class="col-span-6 sm:col-span-4">
    <InputLabel for="model" value="Temperatura" />
    <TextInput
      id="temperature"
      type="number"
      max="1"
      min="0"
      step="0.1"
      v-model="form.temperature"
      class="mt-1 block w-full"
    />
    <InputError
      :message="form.errors.temperature"
      class="mt-2"
    />
  </div>
</template>

<style scoped></style>
