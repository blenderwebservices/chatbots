<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link, router } from '@inertiajs/vue3'

defineProps({
  llmModels: Array,
})

const deleteModel = (id) => {
  if (confirm('¿Estás seguro de que deseas eliminar este modelo?')) {
    router.delete(route('llm-models.destroy', id))
  }
}
</script>

<template>
  <AppLayout title="Modelos LLM">
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold leading-tight text-gray-800 dark:text-gray-100">
          Modelos de Lenguaje (LLM)
        </h2>
        <Link 
          :href="route('llm-models.create')" 
          class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
        >
          Nuevo Modelo
        </Link>
      </div>
    </template>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-gray-50 dark:bg-gray-900/50">
              <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nombre</th>
              <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Identificador</th>
              <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Proveedor</th>
              <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
              <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            <tr v-for="model in llmModels" :key="model.id" class="hover:bg-gray-50 dark:hover:bg-gray-900/40 transition-colors text-sm">
              <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ model.name }}</td>
              <td class="px-6 py-4 text-gray-600 dark:text-gray-400"><code>{{ model.identifier }}</code></td>
              <td class="px-6 py-4">
                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 uppercase">
                  {{ model.provider }}
                </span>
              </td>
              <td class="px-6 py-4">
                <span 
                  class="px-2 py-1 text-xs font-semibold rounded-full"
                  :class="model.active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                >
                  {{ model.active ? 'Activo' : 'Inactivo' }}
                </span>
              </td>
              <td class="px-6 py-4 space-x-3">
                <Link :href="route('llm-models.edit', model.id)" class="text-indigo-600 hover:text-indigo-900 font-medium">Editar</Link>
                <button @click="deleteModel(model.id)" class="text-red-600 hover:text-red-900 font-medium">Eliminar</button>
              </td>
            </tr>
            <tr v-if="llmModels.length === 0">
              <td colspan="5" class="px-6 py-10 text-center text-gray-500">No hay modelos registrados aún.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AppLayout>
</template>
