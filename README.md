# Chatbots OpenAI & Multi-LLM Platform

Esta aplicación es una plataforma robusta para la creación y gestión de Chatbots inteligentes, diseñada para integrarse con múltiples modelos de lenguaje (LLMs). Inicialmente optimizada para **OpenAI**, la arquitectura permite la expansión hacia otros proveedores de IA.

## 🚀 Funcionalidades Principales
- **Gestión de Chatbots**: Crea y configura múltiples instancias de bots con comportamientos específicos.
- **Fuentes de Conocimiento**: Entrena a tus bots mediante la carga de PDFs o el rastreo de URLs de sitios web.
- **Respuestas en Tiempo Real**: Notarás una experiencia fluida gracias al soporte de respuestas en *streaming*.
- **Gestión de Equipos**: Incluye un sistema completo de equipos y perfiles de usuario.

## 🛠️ Stack Tecnológico

### Backend (PHP & Laravel)
- **PHP 8.2+**: Lenguaje base centrado en el rendimiento.
- **Laravel 12 (LTS)**: Framework principal que orquestra toda la lógica del negocio.
- **Laravel Jetstream**: Implementación de autenticación avanzada y gestión de equipos.
- **Laravel Inertia.js**: Puente para construir SPAs usando renderizado clásico del lado del servidor.
- **Prism PHP**: Librería de abstracción para gestionar diferentes proveedores de LLM de forma unificada.
- **OpenAI PHP Laravel**: Integración nativa y optimizada para el ecosistema OpenAI.
- **Spatie PDF to Text**: Herramienta para la extracción de texto eficiente desde documentos PDF.
- **Symfony Dom Crawler**: Utilizado para el procesamiento y extracción de datos de sitios web (Web Scraping).

### Frontend (Vue.js & CSS)
- **Vue.js 3 (Composition API)**: Framework reactivo para una interfaz de usuario dinámica.
- **Tailwind CSS**: Utilizado para un diseño moderno, responsivo y altamente personalizable.
- **Vite**: Motor de compilación ultra-rápido para el desarrollo frontend.
- **Inertia.js Vue Adapter**: Sincronización transparente de datos entre Laravel y Vue.
- **@laravel/stream-vue**: Gestiona el flujo de datos en tiempo real para las respuestas de la IA.

### Infraestructura y Otros
- **Laravel Sanctum**: Gestión de la seguridad y API Tokens.
- **Ziggy**: Permite usar las rutas de Laravel directamente en el código Vue.
- **SQLite/MySQL**: Soporte para bases de datos relacionales robustas.

---

Desarrollado con pasión utilizando las mejores prácticas del ecosistema Laravel.
