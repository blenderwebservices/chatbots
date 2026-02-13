<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import { 
    PlusIcon, 
    PencilIcon, 
    TrashIcon, 
    ChevronUpIcon, 
    ChevronDownIcon,
    DocumentIcon,
    GlobeAltIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
  knowledgeSources: Array,
  chatbots: Array,
})

const sortField = ref('name')
const sortOrder = ref('asc')
const groupByChatbot = ref(false)

const handleSort = (field) => {
    if (sortField.value === field) {
        sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc'
    } else {
        sortField.value = field
        sortOrder.value = 'asc'
    }
}

const sortedSources = computed(() => {
    let result = [...props.knowledgeSources];
    
    result.sort((a, b) => {
        let valA = a[sortField.value];
        let valB = b[sortField.value];
        
        if (sortField.value === 'chatbot') {
            valA = a.chatbot?.name || '';
            valB = b.chatbot?.name || '';
        }

        if (valA < valB) return sortOrder.value === 'asc' ? -1 : 1;
        if (valA > valB) return sortOrder.value === 'asc' ? 1 : -1;
        return 0;
    });

    return result;
})

const groupedSources = computed(() => {
    if (!groupByChatbot.ref) return null;
    
    const groups = {};
    sortedSources.value.forEach(source => {
        const chatbotName = source.chatbot?.name || 'Sin Chatbot';
        if (!groups[chatbotName]) groups[chatbotName] = [];
        groups[chatbotName].push(source);
    });
    return groups;
})

const deleteSource = (id) => {
    if (confirm('¿Estás seguro de eliminar esta fuente?')) {
        router.delete(route('knowledge-sources.destroy', id), {
            preserveScroll: true
        });
    }
}

const formatDate = (date) => {
    return new Date(date).toLocaleDateString();
}
</script>

<template>
  <AppLayout title="Fuentes de Conocimiento">
    <template #header>
      <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
          Fuentes de Conocimiento
        </h1>
        <div class="flex items-center gap-4">
            <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 cursor-pointer">
                <input type="checkbox" v-model="groupByChatbot" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                Agrupar por Chatbot
            </label>
            <Link 
              :href="route('knowledge-sources.create')" 
              class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 transition"
            >
              <PlusIcon class="size-4 mr-2" />
              Nueva Fuente
            </Link>
        </div>
      </div>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            
            <!-- Standard Table View -->
            <div v-if="!groupByChatbot" class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-900/50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <th @click="handleSort('name')" class="px-6 py-3 cursor-pointer hover:text-indigo-600 transition">
                                <div class="flex items-center gap-1">
                                    Nombre
                                    <ChevronUpIcon v-if="sortField === 'name' && sortOrder === 'asc'" class="size-3" />
                                    <ChevronDownIcon v-if="sortField === 'name' && sortOrder === 'desc'" class="size-3" />
                                </div>
                            </th>
                            <th @click="handleSort('type')" class="px-6 py-3 cursor-pointer hover:text-indigo-600 transition">
                                <div class="flex items-center gap-1">
                                    Tipo
                                    <ChevronUpIcon v-if="sortField === 'type' && sortOrder === 'asc'" class="size-3" />
                                    <ChevronDownIcon v-if="sortField === 'type' && sortOrder === 'desc'" class="size-3" />
                                </div>
                            </th>
                            <th @click="handleSort('chatbot')" class="px-6 py-3 cursor-pointer hover:text-indigo-600 transition">
                                <div class="flex items-center gap-1">
                                    Chatbot
                                    <ChevronUpIcon v-if="sortField === 'chatbot' && sortOrder === 'asc'" class="size-3" />
                                    <ChevronDownIcon v-if="sortField === 'chatbot' && sortOrder === 'desc'" class="size-3" />
                                </div>
                            </th>
                            <th @click="handleSort('created_at')" class="px-6 py-3 cursor-pointer hover:text-indigo-600 transition">
                                <div class="flex items-center gap-1">
                                    Fecha
                                    <ChevronUpIcon v-if="sortField === 'created_at' && sortOrder === 'asc'" class="size-3" />
                                    <ChevronDownIcon v-if="sortField === 'created_at' && sortOrder === 'desc'" class="size-3" />
                                </div>
                            </th>
                            <th class="px-6 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="source in sortedSources" :key="source.id" class="hover:bg-gray-50 dark:hover:bg-gray-900/40 transition-colors text-sm">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <DocumentIcon v-if="source.type === 'pdf'" class="size-4 text-red-500" />
                                    <GlobeAltIcon v-else class="size-4 text-blue-500" />
                                    <span class="font-medium text-gray-900 dark:text-white">{{ source.name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="capitalize text-gray-600 dark:text-gray-400">{{ source.type }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span v-if="source.chatbot" class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                    {{ source.chatbot.name }}
                                </span>
                                <span v-else class="text-gray-400 italic text-xs">Sin asignar</span>
                            </td>
                            <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                                {{ formatDate(source.created_at) }}
                            </td>
                            <td class="px-6 py-4 text-right space-x-3">
                                <Link :href="route('knowledge-sources.edit', source.id)" class="text-indigo-600 hover:text-indigo-900 transition">
                                    <PencilIcon class="size-4 inline" />
                                </Link>
                                <button @click="deleteSource(source.id)" class="text-red-600 hover:text-red-900 transition font-medium">
                                    <TrashIcon class="size-4 inline" />
                                </button>
                            </td>
                        </tr>
                        <tr v-if="knowledgeSources.length === 0">
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">No hay fuentes de conocimiento registradas.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Grouped View -->
            <div v-else class="p-6 space-y-8">
                <div v-for="(sources, chatbotName) in groupedSources" :key="chatbotName">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                        {{ chatbotName }}
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div v-for="source in sources" :key="source.id" class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700 flex flex-col justify-between">
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <DocumentIcon v-if="source.type === 'pdf'" class="size-5 text-red-500" />
                                    <GlobeAltIcon v-else class="size-5 text-blue-500" />
                                    <span class="font-bold text-gray-900 dark:text-white truncate max-w-[150px]">{{ source.name }}</span>
                                </div>
                                <span class="text-[10px] text-gray-400 uppercase font-bold">{{ source.type }}</span>
                            </div>
                            <div class="flex items-center justify-between mt-4">
                                <span class="text-xs text-gray-500">{{ formatDate(source.created_at) }}</span>
                                <div class="flex gap-2">
                                    <Link :href="route('knowledge-sources.edit', source.id)" class="p-1.5 text-indigo-600 hover:bg-white dark:hover:bg-gray-800 rounded-lg transition shadow-sm">
                                        <PencilIcon class="size-3.5" />
                                    </Link>
                                    <button @click="deleteSource(source.id)" class="p-1.5 text-red-600 hover:bg-white dark:hover:bg-gray-800 rounded-lg transition shadow-sm">
                                        <TrashIcon class="size-3.5" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="knowledgeSources.length === 0" class="text-center py-10 text-gray-500">No hay fuentes de conocimiento registradas.</div>
            </div>

        </div>
      </div>
    </div>
  </AppLayout>
</template>
