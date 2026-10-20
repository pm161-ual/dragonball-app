<script setup>

const config = useRuntimeConfig();

const search = ref('');
const race = ref('');
const page = ref(1);
const token = useCookie('token');
const vista = useState('vista', () => 'fichas');
const sort = ref('');
const direction = ref('asc');
const gender = ref('');
const affiliation = ref('');
const planetId = ref('');


const { data } = await useFetch(`${config.public.apiBase}/characters`, {
    query: {
        name: search,
        race: race,
        sort: sort,
        direction: direction,
        page: page,
        gender: gender,
        affiliation: affiliation,
        planet_id: planetId,

    }
});

const { data: planetas } = await useFetch(`${config.public.apiBase}/planets`, {
    query: { per_page: 100 }
});





watch([search, race, sort, direction, gender, affiliation, planetId], () => {
    page.value = 1
})

function ordenar(columna) {
   if (sort.value === columna) {
        if (direction.value === 'asc') {
            // pasar a desc
            direction.value = 'desc';
        } else {
            // quitar orden
            sort.value = '';
            direction.value = 'asc';
        }
    } else {
        // esto se queda igual
        sort.value = columna;
        direction.value = 'asc';
    }

}


</script>
<template>
    <div class="p-8">
        <div class="flex justify-between items-center">
            <h1 class="text-4xl font-bold text-orange-500">Personajes</h1>
            <NuxtLink v-if="token" to="/personajes/nuevo" class="rounded-full bg-orange-500 px-6 py-2 font-bold hover:bg-orange-600"> Nuevo personaje</NuxtLink>
        </div>

        <div class="flex gap-4 mt-4 flex-col sm:flex-row">
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
           <button @click="vista = 'fichas'" :class="vista === 'fichas' ? 'bg-orange-500' : 'bg-gray-800'" class="rounded-full px-6 py-2 font-bold">Fichas</button>
           <button @click="vista = 'tabla'" :class="vista === 'tabla' ? 'bg-orange-500' : 'bg-gray-800'" class="rounded-full px-6 py-2 font-bold">Tabla</button>

        </div>
        <div v-if="vista === 'fichas'" class="grid gap-4 md:grid-cols-4 mt-8">
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
        <div v-else class="overflow-x-auto mt-8">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-orange-500">
                        <th class="p-2">Imagen</th>
                        <th class="p-2 cursor-pointer" @click="ordenar('name')">
                            <div class="flex items-center gap-2">
                                Nombre
                                <div class="flex flex-col text-[10px] leading-none">
                                    <span :class="sort === 'name' && direction === 'asc' ? 'text-white' : 'text-gray-600'">▲</span>
                                    <span :class="sort === 'name' && direction === 'desc' ? 'text-white' : 'text-gray-600'">▼</span>
                                </div>
                            </div>
                        </th>
                        <th class="p-2 cursor-pointer" @click="ordenar('race')">
                            <div class="flex items-center gap-2">
                                Raza
                                <div class="flex flex-col text-[10px] leading-none">
                                    <span :class="sort === 'race' && direction === 'asc' ? 'text-white' : 'text-gray-600'">▲</span>
                                    <span :class="sort === 'race' && direction === 'desc' ? 'text-white' : 'text-gray-600'">▼</span>
                                </div>
                            </div>
                        </th>
                        <th class="p-2 cursor-pointer" @click="ordenar('gender')">
                            <div class="flex items-center gap-2">
                                Género
                                <div class="flex flex-col text-[10px] leading-none">
                                    <span :class="sort === 'gender' && direction === 'asc' ? 'text-white' : 'text-gray-600'">▲</span>
                                    <span :class="sort === 'gender' && direction === 'desc' ? 'text-white' : 'text-gray-600'">▼</span>
                                </div>
                            </div>
                        </th>
                        <th class="p-2 cursor-pointer" @click="ordenar('affiliation')">
                            <div class="flex items-center gap-2">
                                Afiliación
                                <div class="flex flex-col text-[10px] leading-none">
                                    <span :class="sort === 'affiliation' && direction === 'asc' ? 'text-white' : 'text-gray-600'">▲</span>
                                    <span :class="sort === 'affiliation' && direction === 'desc' ? 'text-white' : 'text-gray-600'">▼</span>
                                </div>
                            </div>
                        </th>
                        <th class="p-2">Planeta</th>
                    </tr>
                    <tr>
                        <th class="p-2"></th>
                        <th class="p-2">
                            <input v-model="search" type="text" placeholder="Nombre" class="bg-gray-800 rounded px-2 py-1 text-sm font-normal text-white w-full" />
                        </th>
                        <th class="p-2">
                            <select v-model="race" class="bg-gray-800 rounded px-2 py-1 text-sm font-normal text-white w-full [&_option]:bg-gray-800">
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
                        </th>
                        <th class="p-2">
                            <select v-model="gender" class="bg-gray-800 rounded px-2 py-1 text-sm font-normal text-white w-full [&_option]:bg-gray-800">
                                <option value="">Todos</option>
                                <option value="Male">Masculino</option>
                                <option value="Female">Femenino</option>
                            </select>
                        </th>
                        <th class="p-2">
                            <select v-model="affiliation" class="bg-gray-800 rounded px-2 py-1 text-sm font-normal text-white w-full [&_option]:bg-gray-800">
                                <option value="">Todos</option>
                                <option value="Z Fighter">Guerrero Z</option>
                                <option value="Army of Frieza">Ejército de Frieza</option>
                                <option value="Freelancer">Indepediente</option>
                                <option value="Villain">Villano</option>
                                <option value="Pride Troopers">Tropas del Orgullo</option>
                                <option value="Other">Otros</option>
                            </select>
                        </th>
                        <th class="p-2">
                            <select v-model="planetId" class="bg-gray-800 rounded px-2 py-1 text-sm font-normal text-white w-full [&_option]:bg-gray-800">
                                <option value="">Todos</option>
                                <option v-for="planeta in planetas?.data" :key="planeta.id" :value="planeta.id">
                                    {{ planeta.name }}
                                </option>
                            </select>
                        </th>
                    </tr>

                </thead>
                <tbody>
                    <tr v-for="personaje in data?.data" :key="personaje.id" class="border-t border-gray-700 cursor-pointer hover:bg-gray-800" @click="navigateTo(`/personajes/${personaje.id}`)">
                        <td class="p-2">
                             <img :src="personaje.image" :alt="personaje.name" class="h-12 w-12 rounded object-cover object-top" />
                        </td>
                        <td class="p-2 font-bold">{{ personaje.name }}</td>
                        <td class="p-2">{{ personaje.race }}</td>
                        <td class="p-2">{{ personaje.gender }}</td>
                        <td class="p-2">{{ personaje.affiliation }}</td>
                        <td class="p-2">{{ personaje.planet?.name }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="flex justify-center items-center gap-4 mt-8">
            <button @click="page--" :disabled="page === 1"
                class="rounded-full bg-orange-500 px-4 sm:px-6 whitespace-nowrap py-2 font-bold hover:bg-orange-600 disabled:opacity-30">
                ← Anterior
            </button>

            <span>Página {{ page }} de {{ data?.last_page }}</span>

            <button @click="page++" :disabled="page === data?.last_page"
                class="rounded-full bg-orange-500 px-4 sm:px-6 whitespace-nowrap py-2 font-bold hover:bg-orange-600 disabled:opacity-30">
                Siguiente →
            </button>
        </div>
    </div>
</template>
