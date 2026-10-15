<script setup>
const route = useRoute();
const config = useRuntimeConfig();

const { data } = await useFetch(`${config.public.apiBase}/characters/${route.params.id}`);
</script>

<template>
    <div class="p-8 relative overflow-hidden min-h-full">

        <!-- Fondo: la foto enorme, borrosa y oscura -->
        <div class="absolute inset-0 bg-cover bg-center blur-sm opacity-20 scale-110"
            :style="{ backgroundImage: `url(${data?.image})` }"></div>

        <!-- Volver -->
        <NuxtLink to="/personajes" class="relative text-orange-500 hover:underline">
            ← Volver a personajes
        </NuxtLink>

        <!-- Ficha: foto a la izquierda, datos a la derecha -->
        <div class="relative flex flex-col md:flex-row items-center justify-center gap-12 mt-8">

            <img :src="data?.image" :alt="data?.name" class="h-[70vh] object-contain" />

            <div class="max-w-2xl bg-black/40 rounded-xl p-6">
                <h1 class="text-5xl font-bold text-orange-500">{{ data?.name }}</h1>

                <div class="mt-6 space-y-2 text-lg">
                    <p><span class="text-gray-400">Raza:</span> {{ data?.race }}</p>
                    <p><span class="text-gray-400">Género:</span> {{ data?.gender }}</p>
                    <p><span class="text-gray-400">Afiliación:</span> {{ data?.affiliation }}</p>
                    <p><span class="text-gray-400">Ki:</span> {{ data?.ki }}</p>
                    <p><span class="text-gray-400">Ki máximo:</span> {{ data?.max_ki }}</p>
                    <p v-if="data?.planet">
                        <span class="text-gray-400">Planeta:</span>
                        <NuxtLink :to="`/planetas/${data.planet.id}`" class="text-orange-500 hover:underline">
                            {{ data.planet.name }}
                        </NuxtLink>
                    </p>
                </div>

                <p class="mt-6 text-gray-300 leading-relaxed">{{ data?.description }}</p>
            </div>

        </div>
    </div>
</template>
