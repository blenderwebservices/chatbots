<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'

defineProps({
  recentChatbots: Array,
  stats: Object,
})
</script>

<template>
  <AppLayout title="Dashboard">
    <template #header>
      <h2 class="text-2xl font-bold leading-tight text-gray-800 dark:text-gray-100">
        Resumen de la Plataforma
      </h2>
    </template>

    <div class="space-y-8">
      <!-- Stats Grid -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-4">
                <div class="size-12 bg-indigo-500/10 rounded-xl flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Chatbots Totales</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.totalChatbots }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-4">
                <div class="size-12 bg-blue-500/10 rounded-xl flex items-center justify-center text-blue-600 dark:text-blue-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Mensajes Procesados</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.totalMessages }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-4">
                <div class="size-12 bg-emerald-500/10 rounded-xl flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Fuentes de Conocimiento</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.totalKnowledgeSources }}</p>
                </div>
            </div>
        </div>

        <Link :href="route('llm-models.index')" class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 transition hover:shadow-md group">
            <div class="flex items-center gap-4">
                <div class="size-12 bg-purple-500/10 rounded-xl flex items-center justify-center text-purple-600 dark:text-purple-400 group-hover:bg-purple-500 group-hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.691.31a2 2 0 00-1.029 2.148l.412 1.854a2 2 0 01-.19 1.402l-1.03 2.06c-.496.992-1.921.992-2.417 0l-1.03-2.06a2 2 0 01-.19-1.402l.412-1.854a2 2 0 00-1.03-2.148l-.69-.31a6 6 0 00-3.86-.517l-2.388.477a2 2 0 00-1.022.547l-1.16 1.45a2 2 0 01-2.9 0l-1.16-1.45c-.473-.591-1.258-.8-1.956-.5l-2.086.894a2 2 0 01-2.457-1.103l-1.341-3.218a2 2 0 01.385-2.222l1.585-1.78a2 2 0 00.5-1.355V10.5a6 6 0 016-6h1.5a2 2 0 001.355-.5l1.78-1.585a2 2 0 012.222-.385l3.218 1.341a2 2 0 011.103 2.457l-.894 2.086c-.3.698-.091 1.483.5 1.956l1.45 1.16a2 2 0 010 2.9l-1.45 1.16c-.591.473-.8 1.258-.5 1.956l.894 2.086a2 2 0 01-1.103 2.457l-3.218 1.341a2 2 0 01-2.222-.385l-1.78-1.585a2 2 0 00-1.355-.5H10.5a6 6 0 01-6-6v-1.5a2 2 0 00-.5-1.355l-1.585-1.78a2 2 0 01-.385-2.222l1.341-3.218a2 2 0 012.457-1.103l2.086.894c.698.3 1.483.091 1.956-.5l1.16-1.45a2 2 0 012.9 0l1.16 1.45a2 2 0 001.022.547l2.387.477a6 6 0 003.86-.517l.691-.31a2 2 0 001.029-2.148l-.412-1.854a2 2 0 01.19-1.402l1.03-2.06c.496-.992 1.921-.992 2.417 0l1.03 2.06a2 2 0 01.19 1.402l-.412 1.854a2 2 0 001.03 2.148l.69.31a6 6 0 003.86.517l2.388-.477a2 2 0 001.022-.547l1.16-1.45a2 2 0 012.9 0l1.16 1.45z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Modelos LLM</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.totalLlmModels }}</p>
                </div>
            </div>
        </Link>
      </div>

      <!-- Recent Chatbots -->
      <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Últimos Chatbots Registrados</h3>
            <Link :href="route('chatbots.index')" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">Ver todos</Link>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-900/50">
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nombre</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Modelo</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Creado el</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <tr v-for="bot in recentChatbots" :key="bot.id" class="hover:bg-gray-50 dark:hover:bg-gray-900/40 transition-colors text-sm">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ bot.name }}</td>
                        <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ bot.model }}</td>
                        <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ new Date(bot.created_at).toLocaleDateString() }}</td>
                        <td class="px-6 py-4">
                            <Link :href="route('chatbots.show', bot.id)" class="text-indigo-600 hover:underline">Gestionar</Link>
                        </td>
                    </tr>
                    <tr v-if="recentChatbots.length === 0">
                        <td colspan="4" class="px-6 py-10 text-center text-gray-500">No hay chatbots registrados aún.</td>
                    </tr>
                </tbody>
            </table>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
