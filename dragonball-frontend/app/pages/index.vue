<template>
  <section class="relative -m-8 min-h-[85vh] bg-[url('/images/world.png')] bg-cover bg-center flex items-center justify-center">
    <div class="absolute inset-0 bg-black/60"></div>

    <div class="relative text-center px-4">
      <h1 class="text-5xl font-bold text-orange-500">Bienvenido al mundo de Dragon Ball</h1>
      <p class="mt-4 text-xl">Descubre sus personajes y planetas</p>
      <NuxtLink to="/personajes" class="mt-8 inline-block rounded-full bg-orange-500 px-8 py-3 font-bold hover:bg-orange-600">
        Ver personajes
      </NuxtLink>
    </div>
  </section>
</template>
