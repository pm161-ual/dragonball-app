<template>
  <div class="min-h-screen bg-gray-900 text-white">
    <header class="bg-orange-500 p-4 flex gap-6 font-bold">
      <NuxtLink to="/">Dragon Ball</NuxtLink>
      <NuxtLink to="/personajes">Personajes</NuxtLink>
      <NuxtLink to="/planetas">Planetas</NuxtLink>
    </header>
    <main class="p-8">
      <slot />
    </main>
  </div>
</template>
