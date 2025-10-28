<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import ChatInput from '@/Components/Chats/ChatInput.vue'
import ChatMessage from '@/Components/Chats/ChatMessage.vue'
import ChatTitle from '@/Components/Chats/ChatTitle.vue'
import { nextTick, useTemplateRef } from 'vue'

const props = defineProps({
  chat: Object,
  messages: Array,
})
const chatContainer = useTemplateRef('chatContainer')
const scrollToBottom = () => {
  chatContainer.value.scrollTo({
    top: chatContainer.value.scrollHeight,
    behavior: 'smooth',
  })
}
const sendMessage = async message => {
  props.messages.push({
    content: message,
    role: 'user',
    created_at: new Date(),
  })
  await nextTick()
  // scroll
  scrollToBottom()
  // enviar mensaje al chat
  console.log(message)
}
</script>

<template>
  <AppLayout :title="chat.name">
    <template #header>
      <ChatTitle :chat="chat" />
    </template>
    <div
      class="flex h-[calc(100vh-140px)] flex-col bg-gray-100 dark:bg-gray-900"
    >
      <div
        ref="chatContainer"
        class="mx-auto max-w-7xl flex-1 overflow-y-auto p-6"
      >
        <ChatMessage
          :message="message"
          v-if="messages.length > 0"
          v-for="message in messages"
          :key="message.id"
        />
      </div>
      <ChatInput @messageSent="sendMessage" :chat="chat" />
    </div>
  </AppLayout>
</template>
