<script setup>
const route = useRoute();
const config = useRuntimeConfig();

const { data } = await useFetch(`${config.public.apiBase}/characters/${route.params.id}`);
</script>

<template>
    <div class="p-8 relative overflow-hidden flex-1">

        <div class="absolute inset-0 bg-cover bg-center blur-sm opacity-20 scale-110"
            :style="{ backgroundImage: `url(${data?.image})` }"></div>

        <NuxtLink to="/personajes" class="relative text-orange-500 hover:underline">
            ← Volver a personajes
        </NuxtLink>

        <div class="relative flex flex-col md:flex-row items-center justify-center gap-12 mt-8">

            <img :src="data?.image" :alt="data?.name" class="h-[70vh] object-contain" />

            <div class="max-w-2xl bg-black/40 rounded-xl p-6">
                <div class="flex justify-between items-start gap-6">
                    <div class="space-y-2 text-lg">
                        <h1 class="text-5xl font-bold text-orange-500 mb-4">{{ data?.name }}</h1>
                        <p><span class="text-gray-400">Raza:</span> {{ data?.race }}</p>
                        <p><span class="text-gray-400">Género:</span> {{ data?.gender }}</p>
                        <p><span class="text-gray-400">Afiliación:</span> {{ data?.affiliation }}</p>
                        <p><span class="text-gray-400">Ki:</span> {{ data?.ki }}</p>
                        <p><span class="text-gray-400">Ki máximo:</span> {{ data?.max_ki }}</p>
                    </div>
                    <NuxtLink v-if="data?.planet" :to="`/planetas/${data.planet.id}`" class="flex flex-col items-center gap-2 shrink-0 hover:scale-105 transition mx-auto self-start">
                       <p class="text-orange-500 font-bold">{{ data?.planet.name }}</p>
                       <img :src="data?.planet.image" :alt="data?.planet.name" class="h-52 w-52 object-contain">
                    </NuxtLink>
               </div>
               <p class="mt-6 text-gray-300 leading-relaxed text-justify">{{ data?.description }}</p>
            </div>

        </div>
    </div>
</template>
