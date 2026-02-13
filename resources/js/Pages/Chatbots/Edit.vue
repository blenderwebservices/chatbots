<script setup>
import { ref } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import FormSection from '@/Components/FormSection.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import ActionMessage from '@/Components/ActionMessage.vue'
import SectionBorder from '@/Components/SectionBorder.vue'
import ChatbotsForm from '@/Components/Chatbots/ChatbotsForm.vue'
import KnowledgeSourcesCreateModal from '@/Components/KnowledgeSources/KnowledgeSourcesCreateModal.vue'
import KnowledgeSourcesListItem from '@/Components/KnowledgeSources/KnowledgeSourcesListItem.vue'
import { PlusIcon } from '@heroicons/vue/24/solid'
import { createForm, update } from '@/Forms/chatbot.js'

const props = defineProps({
  chatbot: {
    type: Object,
    required: true,
  },
  models: {
    type: Array,
    required: true,
  },
})

const showModal = ref(false)
const form = createForm(props.chatbot)

const handleSubmit = () => {
  update(form, props.chatbot.id)
}
</script>

<template>
  <AppLayout :title="`Editar Chatbot: ${chatbot.name}`">
    <template #header>
      <h1
        class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
      >
        Chatbot: {{ chatbot.name }}
      </h1>
    </template>
    <section class="py-6" aria-label="Editar Chatbot">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <FormSection @submitted="handleSubmit">
          <template #title> Editar Chatbot</template>
          <template #description>
            Edita la información del chatbot.
          </template>
          <template #form>
            <ChatbotsForm :form="form" :models="models" />
          </template>
          <template #actions>
            <ActionMessage
              :on="form.recentlySuccessful"
              class="me-3"
            >
              Cambios guardados.
            </ActionMessage>
            <PrimaryButton
              aria-label="Guardar Cambios"
              :class="{
                'cursor-not-allowed opacity-50':
                  form.processing,
              }"
              :disabled="form.processing"
            >
              Guardar Cambios
            </PrimaryButton>
          </template>
        </FormSection>

        <SectionBorder />

        <div class="mt-10 sm:mt-0">
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <div class="px-4 sm:px-0">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Fuentes de Conocimiento</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Gestiona los documentos y sitios web de los que este chatbot aprende.
                        </p>
                    </div>
                </div>

                <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                        <div class="flex justify-end mb-4">
                            <PrimaryButton @click="showModal = true">
                                <PlusIcon class="size-4 mr-2" />
                                Agregar Fuente
                            </PrimaryButton>
                        </div>
                        
                        <div v-if="chatbot.knowledge_sources.length > 0" class="divide-y divide-gray-100 dark:divide-gray-700">
                            <KnowledgeSourcesListItem
                                :key="knowledgeSource.id"
                                v-for="knowledgeSource in chatbot.knowledge_sources"
                                :knowledge-source="knowledgeSource"
                            />
                        </div>
                        <div v-else class="text-center py-4 text-gray-500 text-sm">
                            No hay fuentes asignadas a este chatbot.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <KnowledgeSourcesCreateModal
          :show="showModal"
          :chatbot-id="chatbot.id"
          @close="showModal = false"
        />
      </div>
    </section>
  </AppLayout>
</template>

<style scoped></style>
