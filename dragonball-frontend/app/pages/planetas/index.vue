<script setup>

const config = useRuntimeConfig();

const search = ref('');
const destroyed = ref('');
const page = ref(1);
const esAdmin = useCookie('esAdmin');
const token = useCookie('token');
const vista = useState('vistaPlanetas', () => 'fichas');
const sort = ref('');
const direction = ref('asc');

const { data } = await useFetch(`${config.public.apiBase}/planets`, {
    query: {
        name: search,
        is_destroyed: destroyed,
        page: page,
        sort: sort,
        direction: direction,

    }
});

watch([search, destroyed, sort, direction], () => {
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
    <div class="p-4 sm:p-8">
        <div class="flex justify-between items-center flex-wrap gap-4">
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
            <div class="flex gap-2">
                <button @click="vista = 'fichas'" :class="vista === 'fichas' ? 'bg-orange-500' : 'bg-gray-800'" class="rounded-full px-6 py-2 font-bold">Fichas</button>
                <button @click="vista = 'tabla'" :class="vista === 'tabla' ? 'bg-orange-500' : 'bg-gray-800'" class="rounded-full px-6 py-2 font-bold">Tabla</button>
            </div>
        </div>
        <div class="grid gap-4 md:grid-cols-4 mt-8" v-if="vista === 'fichas'">
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
        <div v-else class="overflow-x-auto mt-8">
        <table class="w-full whitespace-nowrap">
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
                    <th class="p-2 cursor-pointer" @click="ordenar('is_destroyed')">
                        <div class="flex items-center gap-2">
                            Estado
                            <div class="flex flex-col text-[10px] leading-none">
                                <span :class="sort === 'is_destroyed' && direction === 'asc' ? 'text-white' : 'text-gray-600'">▲</span>
                                <span :class="sort === 'is_destroyed' && direction === 'desc' ? 'text-white' : 'text-gray-600'">▼</span>
                            </div>
                        </div>
                    </th>
                    <th class="p-2 cursor-pointer" @click="ordenar('characters_count')">
                        <div class="flex items-center gap-2">
                            Habitantes
                            <div class="flex flex-col text-[10px] leading-none">
                                <span :class="sort === 'characters_count' && direction === 'asc' ? 'text-white' : 'text-gray-600'">▲</span>
                                <span :class="sort === 'characters_count' && direction === 'desc' ? 'text-white' : 'text-gray-600'">▼</span>
                            </div>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="planet in data?.data" :key="planet.id" @click="navigateTo(`/planetas/${planet.id}`)" class="border-t border-gray-700 cursor-pointer hover:bg-gray-800">
                    <td class="p-2">
                        <img :src="planet.image" :alt="planet.name" class="h-12 w-20 rounded object-cover" />
                    </td>
                    <td class="p-2 font-bold">{{ planet.name }}</td>
                    <td class="p-2">
                        <span :class="planet.is_destroyed ? 'text-red-500' : 'text-green-500'">
                            {{ planet.is_destroyed ? 'Destruido' : 'Intacto' }}
                        </span>
                    </td>
                    <td class="p-2">{{ planet.characters_count }}</td>
                </tr>
            </tbody>
        </table>
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
