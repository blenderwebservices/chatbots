<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm, Link } from '@inertiajs/vue3'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import InputError from '@/Components/InputError.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import Checkbox from '@/Components/Checkbox.vue'

const form = useForm({
  name: '',
  identifier: '',
  active: true,
})

const submit = () => {
  form.post(route('providers.store'))
}
</script>

<template>
  <AppLayout title="Nuevo Proveedor">
    <template #header>
        <h2 class="text-2xl font-bold leading-tight text-gray-800 dark:text-gray-100">
            Crear Proveedor
        </h2>
    </template>

    <div class="max-w-2xl bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
      <form @submit.prevent="submit" class="space-y-6">
        <div>
          <InputLabel for="name" value="Nombre del Proveedor" />
          <TextInput
            id="name"
            v-model="form.name"
            type="text"
            class="mt-1 block w-full"
            required
            autofocus
            placeholder="Ej: OpenAI"
          />
          <InputError class="mt-2" :message="form.errors.name" />
        </div>

        <div>
          <InputLabel for="identifier" value="Identificador" />
          <TextInput
            id="identifier"
            v-model="form.identifier"
            type="text"
            class="mt-1 block w-full"
            required
            placeholder="Ej: openai"
          />
          <InputError class="mt-2" :message="form.errors.identifier" />
          <p class="mt-1 text-xs text-gray-500">Identificador interno (ej: groq, google).</p>
        </div>

        <div class="block">
          <label class="flex items-center">
            <Checkbox v-model:checked="form.active" name="active" />
            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Proveedor Activo</span>
          </label>
        </div>

        <div class="flex items-center justify-end">
          <Link :href="route('providers.index')" class="mr-4 text-sm text-gray-600 hover:text-gray-900 underline">Cancelar</Link>
          <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
            Guardar Proveedor
          </PrimaryButton>
        </div>
      </form>
    </div>
  </AppLayout>
</template>
