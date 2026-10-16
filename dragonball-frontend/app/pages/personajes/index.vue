<script setup>

const config = useRuntimeConfig();

const search = useState('personaje-busqueda', () => '');
const race = useState('personaje-raza', () => '');
const page = useState('personaje-pagina', () => 1);
const token = useCookie('token');

const { data } = await useFetch(`${config.public.apiBase}/characters`, {
    query: {
        name: search,
        race: race,
        page: page
    }
});

watch([search, race], () => {
    page.value = 1
})


</script>
<template>
    <div class="p-8">
        <div class="flex justify-between items-center">
            <h1 class="text-4xl font-bold text-orange-500">Personajes</h1>
            <NuxtLink v-if="token" to="/personajes/nuevo" class="rounded-full bg-orange-500 px-6 py-2 font-bold hover:bg-orange-600"> Nuevo personaje</NuxtLink>
        </div>

        <div class="flex gap-4 mt-4">
            <input type="text" v-model="search" placeholder="Buscar personaje..."
                class="bg-gray-800 rounded px-4 py-2" />
            <select v-model="race"
                class="bg-gray-800 text-white rounded px-4 py-2 [&_option]:bg-gray-800">
                <option value="">Todas las razas</option>
                <option value="Human">Humano</option>
                <option value="Saiyan">Saiyan</option>
                <option value="Namekian">Namekian</option>
                <option value="Angel">Angel</option>
                <option value="Android">Android</option>
                <option value="Evil">Demonio</option>
                <option value="Frieza Race">Raza Frieza</option>
                <option value="God">Dios</option>
                <option value="Jiren Race">Raza Jiren</option>
                <option value="Majin">Majin</option>
            </select>
        </div>
        <div class="grid gap-4 md:grid-cols-4 mt-8">
            <NuxtLink
                v-for="personaje in data?.data"
                :key="personaje.id"
                :to="`/personajes/${personaje.id}`"
                class="flex items-center gap-4 p-4 bg-gray-800 rounded-xl border border-gray-700 hover:bg-gray-700">
                <img :src="personaje.image" :alt="personaje.name"
                    class="h-24 w-24 rounded-lg object-cover object-top bg-gray-700 shrink-0" />
                <h2 class="text-xl font-bold text-orange-500">{{ personaje.name }}</h2>
            </NuxtLink>
        </div>
        <div class="flex justify-center items-center gap-4 mt-8">
            <button @click="page--" :disabled="page === 1"
                class="rounded-full bg-orange-500 px-6 py-2 font-bold hover:bg-orange-600 disabled:opacity-30">
                ← Anterior
            </button>

            <span>Página {{ page }} de {{ data?.last_page }}</span>

            <button @click="page++" :disabled="page === data?.last_page"
                class="rounded-full bg-orange-500 px-6 py-2 font-bold hover:bg-orange-600 disabled:opacity-30">
                Siguiente →
            </button>
        </div>
    </div>
</template>
