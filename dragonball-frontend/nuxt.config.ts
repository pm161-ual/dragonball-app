// https://nuxt.com/docs/api/configuration/nuxt-config
import tailwindcss from '@tailwindcss/vite'

export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },
  css: ['~/assets/css/dragonball.css'],
  vite: {
    plugins: [tailwindcss()],
  },
  runtimeConfig: {
  public: {
    apiBase: 'http://127.0.0.1:8000/api', // Es la URL base de la API de Laravel para que asi funcione en todas las paginas
  },
},

})

