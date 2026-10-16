<script setup>

const config = useRuntimeConfig();

const {data} = await useFetch(`${config.public.apiBase}/characters`);

const characters = computed(() =>data.value?.data?.slice(0, 6) ?? []);


</script>

<template>
<div class="flex-1">
   <section class="relative min-h-[calc(100vh-76px)] bg-[url('/images/world.png')] bg-cover bg-center flex items-center justify-center">
        <div class="absolute inset-0 bg-black/60"></div>
        <div class="relative text-center px-4">
        <h1 class="text-5xl font-bold text-orange-500">Bienvenido al mundo de Dragon Ball</h1>
        <p class="mt-4 text-xl">Descubre sus personajes y planetas</p>
        <NuxtLink to="/personajes" class="mt-8 inline-block rounded-full bg-orange-500 px-8 py-3 font-bold hover:bg-orange-600">
            Ver personajes
        </NuxtLink>
        </div>
   </section>
  <section>
    <div class="max-w-6xl mx-auto py-12 px-4">
      <h2 class="text-3xl font-bold text-center mb-8">Personajes destacados</h2>
      <div class="grid md:grid-cols-3 gap-16">
       <div v-for="(character, index) in characters" :key="character.id" class="flex items-center gap-6 flex-col text-center">
          <AparecerAlBajar :desde="index % 2 === 1 ? 'derecha' : 'izquierda'">
             <img :src="character.image" :alt="character.name" class="h-80 object-contain">
          </AparecerAlBajar>
          <AparecerAlBajar :desde="index % 2 === 1 ? 'izquierda' : 'derecha'">
                <div class="p-4">
                    <h3 class="text-5xl text-orange-500">{{ character.name }}</h3>
                    <p class="text-gray-400">{{ character.race}}
                    </p>
                </div>
           </AparecerAlBajar>
        </div>
      </div>
    </div>
  </section>
  <section class="text-center py-24 px-4">
      <h2 class="text-3xl font-bold text-center mb-8">Explora los planetas</h2>
      <p class="text-gray-400 mt-4">Desde la Tierra hasta Namek: descubre dónde viven tus personajes favoritos</p>
      <NuxtLink to="/planetas" class="mt-8 inline-block rounded-full bg-orange-500 px-8 py-3 font-bold hover:bg-orange-600">
            Ver planetas
        </NuxtLink>
  </section>
</div>
</template>
