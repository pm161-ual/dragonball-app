<script setup>
const route = useRoute();
const config = useRuntimeConfig();
const token = useCookie('token');
const confirmando = ref(false);
const errorBorrar = ref('');
const { mostrar } = useToast();


async function borrar() {
    errorBorrar.value = '';
    try {
        await $fetch(`${config.public.apiBase}/planets/${route.params.id}`, {
            method: 'DELETE',
            headers: {
                Authorization: `Bearer ${token.value}`
            }
        });
        mostrar('Planeta borrado');
        navigateTo('/planetas');
    } catch (error) {
        if (error.statusCode === 409) {
            errorBorrar.value = error.data?.message ?? 'Este planeta tiene habitantes, no se puede borrar.';
        } else if (error.statusCode === 401) {
            errorBorrar.value = 'Tu sesión ha caducado. Vuelve a iniciar sesión.';
        } else {
            errorBorrar.value = 'No se ha podido borrar el planeta.';
        }
    }
}

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

            <div class="max-w-2xl bg-white/10 backdrop-blur-md border border-white/20 rounded-xl p-6 flex flex-col justify-center max-w-full">
                <h1 class="text-5xl font-bold text-orange-500 mb-4">{{ data?.name }}</h1>
                <p class="text-lg">
                    <span class="text-gray-400">Estado:</span>
                    {{ data?.is_destroyed ? 'Destruido' : 'Intacto' }}
                </p>
                <p class="mt-6 text-gray-300 leading-relaxed text-justify">{{ data?.description }}</p>
                <div v-if="token" class="flex gap-4 mt-6">
                    <NuxtLink :to="`/planetas/editar/${route.params.id}`"
                        class="rounded-full bg-orange-500 px-6 py-2 font-bold hover:bg-orange-600">
                        Editar
                    </NuxtLink>
                    <button @click="confirmando = true"
                        class="rounded-full bg-red-600 px-6 py-2 font-bold hover:bg-red-700">
                        Borrar planeta
                    </button>
                </div>
            </div> 
        </div>

        <div v-if="data?.characters?.length > 0" class="relative mt-12">
            <h2 class="text-3xl font-bold text-orange-500">Habitantes</h2>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 mt-6">
                <NuxtLink
                    v-for="personaje in data.characters"
                    :key="personaje.id"
                    :to="`/personajes/${personaje.id}`"
                    class="flex items-center gap-4 p-4 flex-col sm:flex-row text-center sm:text-left bg-gray-800 rounded-xl border border-gray-700 hover:bg-gray-700">
                    <img :src="personaje.image" :alt="personaje.name"
                        class="h-20 w-20 sm:h-24 sm:w-24 rounded-lg object-cover object-top bg-gray-700 shrink-0"/>
                    <h3 class="text-base sm:text-xl font-bold text-orange-500">{{ personaje.name }}</h3>
                </NuxtLink>
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
