<script setup>
import { Head, Link } from '@inertiajs/vue3'

defineProps({
  canLogin: {
    type: Boolean,
  },
  canRegister: {
    type: Boolean,
  },
  laravelVersion: {
    type: String,
    required: true,
  },
  phpVersion: {
    type: String,
    required: true,
  },
})
</script>

<template>
  <Head title="Chatbots - Multi-LLM Platform" />
  
  <div class="min-h-screen bg-slate-950 text-slate-200 selection:bg-indigo-500 selection:text-white font-sans">
    
    <!-- Decorative background -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
      <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-indigo-900/20 blur-[120px] rounded-full"></div>
      <div class="absolute top-[20%] -right-[10%] w-[30%] h-[50%] bg-violet-900/20 blur-[120px] rounded-full"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-6 lg:px-8">
      
      <!-- Header / Nav -->
      <header class="flex items-center justify-between py-8">
        <div class="flex items-center gap-2">
            <div class="size-10 bg-gradient-to-br from-indigo-500 to-violet-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
            </div>
            <span class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-white to-slate-400">Chatbots</span>
        </div>
        
        <nav v-if="canLogin" class="flex gap-4">
          <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="text-sm font-medium hover:text-indigo-400 transition-colors">
            Dashboard
          </Link>
          <template v-else>
            <Link :href="route('login')" class="text-sm font-medium hover:text-indigo-400 transition-colors">
              Iniciar Sesión
            </Link>
            <Link v-if="canRegister" :href="route('register')" class="text-sm font-medium px-4 py-2 bg-white text-slate-950 rounded-lg hover:bg-indigo-50 transition-colors">
              Registrarse
            </Link>
          </template>
        </nav>
      </header>

      <!-- Hero Section -->
      <section class="py-20 lg:py-32 text-center">
        <h1 class="text-5xl lg:text-7xl font-extrabold tracking-tight mb-6">
            Arquitecto de <span class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-400 to-violet-400">Inteligencia Artificial</span>
        </h1>
        <p class="text-lg lg:text-xl text-slate-400 max-w-3xl mx-auto mb-10 leading-relaxed">
            Crea, entrena y despliega chatbots personalizados con múltiples LLMs. Define su personalidad, controla su creatividad y alinéalos con tus objetivos de negocio.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <Link :href="route('register')" class="w-full sm:w-auto px-8 py-4 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-500 transition-all shadow-lg shadow-indigo-600/25">
                Comenzar ahora
            </Link>
            <a href="#how-it-works" class="w-full sm:w-auto px-8 py-4 bg-slate-900 text-slate-300 font-semibold rounded-xl border border-slate-800 hover:bg-slate-800 transition-all">
                Ver funcionamiento
            </a>
        </div>
      </section>

      <!-- Concepts Section -->
      <section id="how-it-works" class="py-20 grid lg:grid-cols-3 gap-8">
        <!-- The Prompt -->
        <div class="p-8 bg-slate-900/50 border border-slate-800 rounded-3xl hover:border-indigo-500/50 transition-colors">
            <div class="size-12 bg-indigo-500/10 rounded-2xl flex items-center justify-center mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
            <h3 class="text-2xl font-bold mb-4 text-white">El Prompt Maestro</h3>
            <p class="text-slate-400 leading-relaxed mb-6">
                El prompt de sistema es el ADN de tu bot. Define quién es, qué sabe y cómo debe reaccionar ante cada situación. Es el manual de instrucciones maestro.
            </p>
            <ul class="space-y-3 text-sm text-slate-300">
                <li class="flex items-center gap-2">
                    <div class="size-1.5 bg-indigo-500 rounded-full"></div>
                    Definición de rol y personalidad
                </li>
                <li class="flex items-center gap-2">
                    <div class="size-1.5 bg-indigo-500 rounded-full"></div>
                    Restricciones y protocolos de seguridad
                </li>
            </ul>
        </div>

        <!-- Temperature -->
        <div class="p-8 bg-slate-900/50 border border-slate-800 rounded-3xl hover:border-violet-500/50 transition-colors">
            <div class="size-12 bg-violet-500/10 rounded-2xl flex items-center justify-center mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <h3 class="text-2xl font-bold mb-4 text-white">Temperatura</h3>
            <p class="text-slate-400 leading-relaxed mb-6">
                Controla la aleatoriedad de las respuestas. Un valor bajo (0.0) garantiza precisión técnica, mientras que un valor alto (1.0) fomenta la creatividad.
            </p>
            <div class="space-y-4">
                <div class="h-2 w-full bg-slate-800 rounded-full overflow-hidden flex">
                    <div class="h-full w-1/3 bg-indigo-500"></div>
                    <div class="h-full w-2/3 bg-violet-500"></div>
                </div>
                <div class="flex justify-between text-xs text-slate-500 font-medium">
                    <span>CONCISO / MÉDICO</span>
                    <span>CREATIVO / AUTOR</span>
                </div>
            </div>
        </div>

        <!-- Knowledge -->
        <div class="p-8 bg-slate-900/50 border border-slate-800 rounded-3xl hover:border-emerald-500/50 transition-colors">
            <div class="size-12 bg-emerald-500/10 rounded-2xl flex items-center justify-center mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <h3 class="text-2xl font-bold mb-4 text-white">Fuentes de Datos</h3>
            <p class="text-slate-400 leading-relaxed mb-6">
                Nutre a tu bot con información específica. Sube PDFs o ingresa URLs para que el asistente responda basándose en tu propia documentación privada.
            </p>
            <div class="flex gap-2">
                <span class="px-3 py-1 bg-slate-800 rounded-full text-xs text-slate-300">PDFs</span>
                <span class="px-3 py-1 bg-slate-800 rounded-full text-xs text-slate-300">Sitios Web</span>
                <span class="px-3 py-1 bg-slate-800 rounded-full text-xs text-slate-300">Bases de datos</span>
            </div>
        </div>
      </section>

      <!-- Business Use Cases -->
      <section class="py-20 border-t border-slate-800/50">
        <h2 class="text-3xl lg:text-4xl font-bold text-center mb-16">Objetivos según tu <span class="bg-indigo-500/10 text-indigo-400 px-4 py-1 rounded-full border border-indigo-400/20">Industria</span></h2>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- E-commerce -->
            <div class="group relative p-1 rounded-2xl bg-gradient-to-b from-slate-800 to-transparent hover:from-indigo-500 transition-all duration-500">
                <div class="bg-slate-950 p-6 rounded-[calc(1rem-1px)] h-full">
                    <h4 class="font-bold text-white mb-3">E-commerce</h4>
                    <p class="text-sm text-slate-400 leading-relaxed">Automatizar cierres de venta, seguimiento de pedidos y sugerencias personalizadas.</p>
                </div>
            </div>
            <!-- Real Estate -->
            <div class="group relative p-1 rounded-2xl bg-gradient-to-b from-slate-800 to-transparent hover:from-violet-500 transition-all duration-500">
                <div class="bg-slate-950 p-6 rounded-[calc(1rem-1px)] h-full">
                    <h4 class="font-bold text-white mb-3">Inmobiliaria</h4>
                    <p class="text-sm text-slate-400 leading-relaxed">Calificar prospectos, agendar visitas a propiedades y responder detalles técnicos de inmuebles.</p>
                </div>
            </div>
            <!-- Education -->
            <div class="group relative p-1 rounded-2xl bg-gradient-to-b from-slate-800 to-transparent hover:from-emerald-500 transition-all duration-500">
                <div class="bg-slate-950 p-6 rounded-[calc(1rem-1px)] h-full">
                    <h4 class="font-bold text-white mb-3">Educación</h4>
                    <p class="text-sm text-slate-400 leading-relaxed">Tutoría personalizada 24/7, resolución de dudas sobre materiales y apoyo en procesos de inscripción.</p>
                </div>
            </div>
            <!-- Customer Support -->
            <div class="group relative p-1 rounded-2xl bg-gradient-to-b from-slate-800 to-transparent hover:from-orange-500 transition-all duration-500">
                <div class="bg-slate-950 p-6 rounded-[calc(1rem-1px)] h-full">
                    <h4 class="font-bold text-white mb-3">Soporte Técnico</h4>
                    <p class="text-sm text-slate-400 leading-relaxed">Resolución de tickets frecuentes basándose en manuales de producto y bases de conocimiento.</p>
                </div>
            </div>
        </div>
      </section>

      <!-- Footer -->
      <footer class="py-12 border-t border-slate-900 flex flex-col md:flex-row items-center justify-between text-slate-500 text-xs">
        <p>© 2026 Chatbots Multi-LLM Platform. Todos los derechos reservados.</p>
        <div class="mt-4 md:mt-0 flex gap-6">
            <span>Laravel v{{ laravelVersion }}</span>
            <span>PHP v{{ phpVersion }}</span>
        </div>
      </footer>

    </div>
  </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

.font-sans {
    font-family: 'Inter', sans-serif;
}
</style>
