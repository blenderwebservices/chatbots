<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
import {
  ChatBubbleBottomCenterIcon,
  ArrowTopRightOnSquareIcon,
  TrashIcon,
} from '@heroicons/vue/24/solid'

defineProps({
  chats: {
    type: Array,
    required: true,
  },
})

const formatDate = (date) => {
    return new Date(date).toLocaleDateString();
}
</script>

<template>
  <AppLayout title="Mis Conversaciones">
    <template #header>
      <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
          Mis Conversaciones
        </h1>
        <Link 
          :href="route('chatbots.index')" 
          class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 transition"
        >
          Nuevo Chat
        </Link>
      </div>
    </template>

    <section class="py-6">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-900/50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-3">Referencia / Chat</th>
                            <th class="px-6 py-3">Chatbot</th>
                            <th class="px-6 py-3">Fecha</th>
                            <th class="px-6 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="chat in chats" :key="chat.id" class="hover:bg-gray-50 dark:hover:bg-gray-900/40 transition-colors text-sm">
                            <td class="px-6 py-4">
                                <Link 
                                    :href="route('chats.edit', { chat: chat.id })"
                                    class="flex items-center gap-2 font-medium text-gray-900 dark:text-white hover:text-indigo-600 transition"
                                >
                                    <ChatBubbleBottomCenterIcon class="size-4 text-indigo-500" />
                                    {{ chat.name }}
                                </Link>
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                    {{ chat.chatbot.name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                                {{ formatDate(chat.created_at) }}
                            </td>
                            <td class="px-6 py-4 text-right space-x-3">
                                <Link 
                                    :href="route('chats.edit', { chat: chat.id })"
                                    class="text-indigo-600 hover:underline"
                                >
                                    Abrir
                                </Link>
                                <Link
                                  method="DELETE"
                                  preserve-scroll
                                  :href="route('chats.destroy', { chat: chat.id })"
                                  class="text-red-600 hover:text-red-900 transition"
                                  as="button"
                                >
                                  Eliminar
                                </Link>
                            </td>
                        </tr>
                        <tr v-if="chats.length === 0">
                            <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                No tienes conversaciones activas. ¡Crea un chatbot y empieza a chatear!
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
      </div>
    </section>
  </AppLayout>
</template>
