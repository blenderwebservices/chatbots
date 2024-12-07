import { useForm } from '@inertiajs/vue3'

export const createForm = (chatbot = {}) =>
  useForm({
    name: chatbot.name,
    system_prompt: chatbot.system_prompt,
    model: chatbot.model,
    temperature: String(chatbot.temperature),
  })

export const store = (form, options = {}) =>
  form.post(route('chatbots.store'), getOptions(options))

export const update = (form, chatbotId, options = {}) =>
  form.put(
    route('chatbots.update', chatbotId),
    getOptions(options)
  )

const getOptions = options => ({
  preserveScroll: true,
  ...options,
})
