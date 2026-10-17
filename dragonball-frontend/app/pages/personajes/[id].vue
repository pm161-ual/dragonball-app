<script setup>
const route = useRoute();
const config = useRuntimeConfig();
const { mostrar } = useToast();


const { data } = await useFetch(`${config.public.apiBase}/characters/${route.params.id}`);

const token = useCookie('token');
const confirmando = ref(false);
const errorBorrar = ref('');

async function borrar() {
    errorBorrar.value = '';
    try {
        await $fetch(`${config.public.apiBase}/characters/${route.params.id}`, {
            method: 'DELETE',
            headers: {
                Authorization: `Bearer ${token.value}`
            }
        });
        navigateTo('/personajes');
    } catch {
        errorBorrar.value = 'No se ha podido borrar. Vuelve a iniciar sesión.';
    }
}
</script>

<template>
    <div class="p-8 relative overflow-hidden flex-1">

        <div class="absolute inset-0 bg-cover bg-center blur-sm opacity-20 scale-110"
            :style="{ backgroundImage: `url(${data?.image})` }"></div>

        <NuxtLink to="/personajes" class="relative text-orange-500 hover:underline">
            ← Volver a personajes
        </NuxtLink>

        <div class="relative flex flex-col md:flex-row items-center justify-center gap-12 mt-8">

            <img :src="data?.image" :alt="data?.name" class="h-[45vh] md:h-[70vh] object-contain" />

            <div class="max-w-2xl bg-black/40 rounded-xl p-6">
                <div class="flex justify-between items-start gap-6 flex-col sm:flex-row">
                    <div class="space-y-2 text-lg">
                        <h1 class="text-5xl font-bold text-orange-500 mb-4">{{ data?.name }}</h1>
                        <p><span class="text-gray-400">Raza:</span> {{ data?.race }}</p>
                        <p><span class="text-gray-400">Género:</span> {{ data?.gender }}</p>
                        <p><span class="text-gray-400">Afiliación:</span> {{ data?.affiliation }}</p>
                        <p><span class="text-gray-400">Ki:</span> {{ data?.ki }}</p>
                        <p><span class="text-gray-400">Ki máximo:</span> {{ data?.max_ki }}</p>
                    </div>
                    <NuxtLink v-if="data?.planet" :to="`/planetas/${data.planet.id}`"
                        class="flex flex-col items-center gap-2 shrink-0 hover:scale-105 transition mx-auto self-start">
                        <p class="text-orange-500 font-bold">{{ data.planet.name }}</p>
                        <img :src="data.planet.image" :alt="data.planet.name" class="h-52 w-52 object-contain">
                    </NuxtLink>
                </div>

                <p class="mt-6 text-gray-300 leading-relaxed text-justify">{{ data?.description }}</p>

                <div v-if="token" class="flex gap-4 mt-6">
                <NuxtLink :to="`/personajes/editar/${route.params.id}`"
                    class="rounded-full bg-orange-500 px-6 py-2 font-bold hover:bg-orange-600">
                    Editar
                </NuxtLink>
                <button @click="confirmando = true"
                    class="rounded-full bg-red-600 px-6 py-2 font-bold hover:bg-red-700">
                    Borrar personaje
                </button>
           </div>

            </div>

        </div>

        <div v-if="confirmando" @click.self="confirmando = false"
            class="fixed inset-0 z-50 bg-black/70 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-gray-800 border-2 border-orange-500 rounded-2xl p-8 max-w-sm w-full text-center shadow-2xl">
                <p class="text-5xl">🗑️</p>
                <h2 class="text-2xl font-bold text-orange-500 mt-4">Estás seguro de borrar a {{ data?.name }}?</h2>

                <p v-if="errorBorrar" class="text-red-500 text-sm mt-4">{{ errorBorrar }}</p>

                <div class="flex gap-4 justify-center mt-6">
                    <button @click="confirmando = false"
                        class="rounded-full bg-gray-600 px-6 py-2 font-bold hover:bg-gray-500">
                        Cancelar
                    </button>
                    <button @click="borrar"
                        class="rounded-full bg-red-600 px-6 py-2 font-bold hover:bg-red-700">
                        Sí, borrar
                    </button>
                </div>
            </div>
        </div>

    </div>
</template>
