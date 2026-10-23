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
    apiBase: 'http://127.0.0.1:8000/api',
  },
  },
    $env: {
      android: {
        ssr: false,
        runtimeConfig: {
          public: {
            apiBase: 'https://dragonball-app-two.vercel.app/api',
          },
        },
      },
    },


})

