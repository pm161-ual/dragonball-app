<script setup>

const config = useRuntimeConfig();

const search = ref('');
const destroyed = ref('');
const page = ref(1);
const esAdmin = useCookie('esAdmin');
const token = useCookie('token');
const { data } = await useFetch(`${config.public.apiBase}/planets`, {
    query: {
        name: search,
        is_destroyed: destroyed,
        page: page
    }
});

watch([search, destroyed], () => {
    page.value = 1
})


</script>
<template>
    <div class="p-8">
        <div class="flex justify-between items-center">
            <h1 class="text-4xl font-bold text-orange-500">Planetas</h1>
            <NuxtLink v-if="esAdmin" to="/planetas/nuevo" class="rounded-full bg-orange-500 px-6 py-2 font-bold hover:bg-orange-600">
                Nuevo planeta
            </NuxtLink>

        </div>

        <div class="flex gap-4 mt-4 flex-col sm:flex-row">
            <input type="text" v-model="search" placeholder="Buscar planeta..."
                class="bg-gray-800 rounded px-4 py-2" />
            <select v-model="destroyed"
                class="bg-gray-800 text-white rounded px-4 py-2 [&_option]:bg-gray-800">
                <option value="">Todos los planetas</option>
                <option value="0">Intactos</option>
                <option value="1">Destruidos</option>
            </select>
        </div>
        <div class="grid gap-4 md:grid-cols-4 mt-8">
            <NuxtLink
                v-for="planet in data?.data"
                :key="planet.id"
                :to="`/planetas/${planet.id}`"
                class="flex items-center gap-4 p-4 bg-gray-800 rounded-xl border border-gray-700 hover:bg-gray-700">
                <img :src="planet.image" :alt="planet.name"
                    class="h-24 w-36 rounded-lg object-cover bg-gray-700 shrink-0" />
                <h2 class="text-xl font-bold text-orange-500">{{ planet.name }}</h2>
            </NuxtLink>
        </div>
        <div class="flex justify-center items-center gap-4 mt-8">
            <button @click="page--" :disabled="page === 1"
                class="rounded-full bg-orange-500 px-4 sm:px-6 whitespace-nowrap  py-2 font-bold hover:bg-orange-600 disabled:opacity-30">
                ← Anterior
            </button>

            <span class="text-sm whitespace-nowrap">Página {{ page }} de {{ data?.last_page }}</span>

            <button @click="page++" :disabled="page === data?.last_page"
                class="rounded-full bg-orange-500 px-4 sm:px-6 whitespace-nowrap  py-2 font-bold hover:bg-orange-600 disabled:opacity-30">
                Siguiente →
            </button>
        </div>
    </div>
</template>
