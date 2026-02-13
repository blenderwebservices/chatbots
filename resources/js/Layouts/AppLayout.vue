<script setup>
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import ApplicationMark from '@/Components/ApplicationMark.vue'
import Banner from '@/Components/Banner.vue'
import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'
import NavLink from '@/Components/NavLink.vue'
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue'

defineProps({
  title: String,
})

const showingNavigationDropdown = ref(false)
const sidebarOpen = ref(false)

const switchToTeam = team => {
  router.put(
    route('current-team.update'),
    {
      team_id: team.id,
    },
    {
      preserveState: false,
    }
  )
}

const logout = () => {
  router.post(route('logout'))
}
</script>

<template>
  <div class="min-h-screen bg-gray-100 dark:bg-gray-900 lg:flex">
    <Head :title="title" />

    <Banner />

    <!-- Sidebar Overlay (Mobile) -->
    <div v-if="sidebarOpen" class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden" @click="sidebarOpen = false"></div>

    <!-- Sidebar -->
    <aside 
      :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
      class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 transition-transform duration-300 transform lg:translate-x-0 lg:static lg:inset-0"
    >
      <div class="flex items-center justify-center h-16 border-b border-gray-200 dark:border-gray-700">
        <Link :href="route('dashboard')" class="flex items-center gap-2">
          <ApplicationMark class="block h-9 w-auto" />
          <span class="text-xl font-bold text-gray-800 dark:text-white">Chatbots</span>
        </Link>
      </div>

      <nav class="mt-6 px-4 space-y-2">
        <NavLink 
          :href="route('dashboard')" 
          :active="route().current('dashboard')"
          class="flex items-center px-4 py-3 rounded-xl transition-all"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="size-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
          </svg>
          Dashboard
        </NavLink>

        <NavLink 
          :href="route('chats.all')" 
          :active="route().current('chats.all')"
          class="flex items-center px-4 py-3 rounded-xl transition-all"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="size-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
          </svg>
          Conversaciones
        </NavLink>

        <NavLink 
          :href="route('chatbots.index')" 
          :active="route().current('chatbots.*')"
          class="flex items-center px-4 py-3 rounded-xl transition-all"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="size-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
          </svg>
          Chatbots
        </NavLink>

        <NavLink 
          :href="route('llm-models.index')" 
          :active="route().current('llm-models.*')"
          class="flex items-center px-4 py-3 rounded-xl transition-all"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="size-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.691.31a2 2 0 00-1.029 2.148l.412 1.854a2 2 0 01-.19 1.402l-1.03 2.06c-.496.992-1.921.992-2.417 0l-1.03-2.06a2 2 0 01-.19-1.402l.412-1.854a2 2 0 00-1.03-2.148l-.69-.31a6 6 0 00-3.86-.517l-2.388.477a2 2 0 00-1.022.547l-1.16 1.45a2 2 0 01-2.9 0l-1.16-1.45c-.473-.591-1.258-.8-1.956-.5l-2.086.894a2 2 0 01-2.457-1.103l-1.341-3.218a2 2 0 01.385-2.222l1.585-1.78a2 2 0 00.5-1.355V10.5a6 6 0 016-6h1.5a2 2 0 001.355-.5l1.78-1.585a2 2 0 012.222-.385l3.218 1.341a2 2 0 011.103 2.457l-.894 2.086c-.3.698-.091 1.483.5 1.956l1.45 1.16a2 2 0 010 2.9l-1.45 1.16c-.591.473-.8 1.258-.5 1.956l.894 2.086a2 2 0 01-1.103 2.457l-3.218 1.341a2 2 0 01-2.222-.385l-1.78-1.585a2 2 0 00-1.355-.5H10.5a6 6 0 01-6-6v-1.5a2 2 0 00-.5-1.355l-1.585-1.78a2 2 0 01-.385-2.222l1.341-3.218a2 2 0 012.457-1.103l2.086.894c.698.3 1.483.091 1.956-.5l1.16-1.45a2 2 0 012.9 0l1.16 1.45a2 2 0 001.022.547l2.387.477a6 6 0 003.86-.517l.691-.31a2 2 0 001.029-2.148l-.412-1.854a2 2 0 01.19-1.402l1.03-2.06c.496-.992 1.921-.992 2.417 0l1.03 2.06a2 2 0 01.19 1.402l-.412 1.854a2 2 0 001.03 2.148l.69.31a6 6 0 003.86.517l2.388-.477a2 2 0 001.022-.547l1.16-1.45a2 2 0 012.9 0l1.16 1.45z" />
          </svg>
          Modelos LLM
        </NavLink>

        <NavLink 
          :href="route('providers.index')" 
          :active="route().current('providers.*')"
          class="flex items-center px-4 py-3 rounded-xl transition-all"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="size-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
          </svg>
          Proveedores
        </NavLink>
      </nav>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
      <!-- Top navbar -->
      <header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8">
        <button 
          @click="sidebarOpen = true" 
          class="lg:hidden p-2 rounded-md text-gray-500 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
        >
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>

        <div class="flex items-center gap-4 ml-auto">
          <!-- Teams Dropdown -->
          <Dropdown v-if="$page.props.jetstream.hasTeamFeatures" align="right" width="60">
            <template #trigger>
              <button class="flex items-center text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                {{ $page.props.auth.user.current_team.name }}
                <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
              </button>
            </template>
            <template #content>
              <div class="w-60">
                <div class="block px-4 py-2 text-xs text-gray-400">Manage Team</div>
                <DropdownLink :href="route('teams.show', $page.props.auth.user.current_team)">Team Settings</DropdownLink>
                <DropdownLink v-if="$page.props.jetstream.canCreateTeams" :href="route('teams.create')">Create New Team</DropdownLink>
                <div class="border-t border-gray-200 dark:border-gray-600" />
                <div class="block px-4 py-2 text-xs text-gray-400">Switch Teams</div>
                <template v-for="team in $page.props.auth.user.all_teams" :key="team.id">
                  <form @submit.prevent="switchToTeam(team)">
                    <DropdownLink as="button">
                      <div class="flex items-center">
                        <svg v-if="team.id == $page.props.auth.user.current_team_id" class="mr-2 h-5 w-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <div>{{ team.name }}</div>
                      </div>
                    </DropdownLink>
                  </form>
                </template>
              </div>
            </template>
          </Dropdown>

          <!-- Settings Dropdown -->
          <Dropdown align="right" width="48">
            <template #trigger>
              <button v-if="$page.props.jetstream.managesProfilePhotos" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                <img class="h-8 w-8 rounded-full object-cover" :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name" />
              </button>
              <span v-else class="inline-flex rounded-md">
                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition">
                  {{ $page.props.auth.user.name }}
                  <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                </button>
              </span>
            </template>
            <template #content>
              <div class="block px-4 py-2 text-xs text-gray-400">Manage Account</div>
              <DropdownLink :href="route('profile.show')">Profile</DropdownLink>
              <DropdownLink v-if="$page.props.jetstream.hasApiFeatures" :href="route('api-tokens.index')">API Tokens</DropdownLink>
              <div class="border-t border-gray-200 dark:border-gray-600" />
              <form @submit.prevent="logout">
                <DropdownLink as="button">Log Out</DropdownLink>
              </form>
            </template>
          </Dropdown>
        </div>
      </header>

      <!-- Page Content -->
      <main class="flex-1 overflow-y-auto bg-gray-100 dark:bg-gray-900 p-4 sm:p-6 lg:p-8">
        <!-- Page Heading Slot -->
        <div v-if="$slots.header" class="mb-8">
          <slot name="header" />
        </div>
        
        <!-- Main Slot -->
        <slot />
      </main>
    </div>
  </div>
</template>

<style>
/* Adjust layout to be sidebar + main content flex */
#app > div {
  display: flex;
}
</style>
