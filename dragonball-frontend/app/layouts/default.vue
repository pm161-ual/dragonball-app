<template>
  <div class="min-h-screen bg-gray-900 text-white flex flex-col">
    <header class="fondo-espacio px-6 py-3 flex items-center gap-8 border-b-4 border-orange-500">
        <NuxtLink to="/">
            <img src="/images/Dragon_Ball_Logo.png" alt="Dragon Ball - Inicio" class="h-12">
        </NuxtLink>
        <nav class="flex gap-6 font-bold">
            <NuxtLink to="/personajes" class="hover:text-orange-500">Personajes</NuxtLink>
            <NuxtLink to="/planetas" class="hover:text-orange-500">Planetas</NuxtLink>
        </nav>
        
        <div class="ml-auto flex items-center gap-5">
            <a href="mailto:patrickmbaanderson@gmail.com" class="hover:text-orange-500" aria-label="Contact"><svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg></a>

            <a href="https://github.com/pm161-ual" target="_blank" rel="noopener" aria-label="GitHub" class="hover:text-orange-500">
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor"><path d="M12 .5a12 12 0 0 0-3.8 23.4c.6.1.8-.3.8-.6v-2.1c-3.3.7-4-1.6-4-1.6-.6-1.4-1.4-1.8-1.4-1.8-1.1-.7.1-.7.1-.7 1.2.1 1.8 1.2 1.8 1.2 1.1 1.8 2.8 1.3 3.5 1 .1-.8.4-1.3.8-1.6-2.7-.3-5.5-1.3-5.5-5.9 0-1.3.5-2.4 1.2-3.2-.1-.3-.5-1.5.1-3.2 0 0 1-.3 3.3 1.2a11.5 11.5 0 0 1 6 0C17 4.6 18 4.9 18 4.9c.7 1.7.3 2.9.1 3.2.8.8 1.2 1.9 1.2 3.2 0 4.6-2.8 5.6-5.5 5.9.4.4.8 1.1.8 2.2v3.3c0 .3.2.7.8.6A12 12 0 0 0 12 .5Z"/></svg>
            </a>

            <a href="https://instagram.com" target="_blank" rel="noopener" aria-label="Instagram" class="hover:text-orange-500">
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor"/></svg>
            </a>

            <NuxtLink to="/login" class="rounded-full bg-orange-500 px-5 py-2 font-bold hover:bg-orange-600">
            Acceder
            </NuxtLink>
        </div>
    </header>

    <main class="flex-1 flex flex-col">
      <slot />
    </main>
    <footer>
      <div class="bg-gray-800 text-gray-400 text-center py-4 border-t">
         <nav class="flex gap-6 font-bold justify-center mb-4">
            <NuxtLink to="/personajes" class="hover:text-orange-500">Personajes</NuxtLink>
            <NuxtLink to="/planetas" class="hover:text-orange-500">Planetas</NuxtLink>
        </nav>
        <p>&copy; 2026 Dragon Ball App</p>
        <p class="text-sm">Datos de dragonball-api.com</p>
      </div>
    </footer>
  </div>
</template>
