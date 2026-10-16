<script setup>
const route = useRoute();
const config = useRuntimeConfig();

const { data } = await useFetch(`${config.public.apiBase}/planets/${route.params.id}`);
</script>

<template>
    <div class="p-8 relative overflow-hidden flex-1 flex flex-col">

        <div class="absolute inset-0 bg-cover bg-center blur-sm opacity-20 scale-110"
            :style="{ backgroundImage: `url(${data?.image})` }"></div>

        <NuxtLink to="/planetas" class="relative text-orange-500 hover:underline">
            ← Volver a planetas
        </NuxtLink>

        <div class="relative flex flex-col md:flex-row items-stretch justify-center gap-12 mt-8 my-auto">

            <img :src="data?.image" :alt="data?.name" class="w-full max-w-md rounded-xl object-contain" />

            <div class="max-w-2xl bg-white/10 backdrop-blur-md border border-white/20 rounded-xl p-6 flex flex-col justify-center">
                <h1 class="text-5xl font-bold text-orange-500 mb-4">{{ data?.name }}</h1>
                <p class="text-lg">
                    <span class="text-gray-400">Estado:</span>
                    {{ data?.is_destroyed ? 'Destruido' : 'Intacto' }}
                </p>
                <p class="mt-6 text-gray-300 leading-relaxed text-justify">{{ data?.description }}</p>
            </div>

        </div>

        <div v-if="data?.characters?.length > 0" class="relative mt-12">
            <h2 class="text-3xl font-bold text-orange-500">Habitantes</h2>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 mt-6">
                <NuxtLink
                    v-for="personaje in data.characters"
                    :key="personaje.id"
                    :to="`/personajes/${personaje.id}`"
                    class="flex items-center gap-4 p-4 bg-gray-800 rounded-xl border border-gray-700 hover:bg-gray-700">
                    <img :src="personaje.image" :alt="personaje.name"
                        class="h-24 w-24 rounded-lg object-cover object-top bg-gray-700 shrink-0" />
                    <h3 class="text-xl font-bold text-orange-500">{{ personaje.name }}</h3>
                </NuxtLink>
            </div>
        </div>

    </div>
</template>
