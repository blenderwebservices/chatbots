<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
import { ChatBubbleLeftRightIcon } from '@heroicons/vue/24/solid'

defineProps({
  chatbots: {
    type: Array,
    required: true,
  },
})
</script>

<template>
  <AppLayout title="Nueva Conversación">
    <template #header>
      <h2 class="text-2xl font-bold leading-tight text-gray-800 dark:text-gray-100">
        Iniciar Nueva Conversación
      </h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div 
            v-for="chatbot in chatbots" 
            :key="chatbot.id"
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 flex flex-col justify-between hover:shadow-md transition"
          >
            <div>
              <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ chatbot.name }}</h3>
              <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-3 mb-4">
                {{ chatbot.system_prompt }}
              </p>
            </div>
            
            <Link
              method="POST"
              :href="route('chats.store', { chatbot_id: chatbot.id })"
              as="button"
              class="w-full inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 transition"
            >
              <ChatBubbleLeftRightIcon class="size-4 mr-2" />
              Chatear ahora
            </Link>
          </div>
          
          <div v-if="chatbots.length === 0" class="col-span-full py-20 text-center">
            <p class="text-gray-500 dark:text-gray-400 mb-4">No tienes chatbots creados aún.</p>
            <Link 
              :href="route('chatbots.create')"
              class="text-indigo-600 hover:underline font-medium"
            >
              Crear mi primer chatbot
            </Link>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
