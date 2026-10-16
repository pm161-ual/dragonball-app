<script setup>
const config = useRuntimeConfig();
const token = useCookie('token');
const { mostrar } = useToast();


const form = ref({
    name: '',
    race: '',
    gender: '',
    affiliation: '',
    ki: '',
    max_ki: '',
    image: '',
    description: '',
    planet_id: ''
});

const errores = ref({});

const { data: planetas } = await useFetch(`${config.public.apiBase}/planets`, {
    query: { per_page: 100 }
});

async function guardar() {
    errores.value = {};
    try {
        const nuevo = await $fetch(`${config.public.apiBase}/characters`, {
            method: 'POST',
            headers: { Authorization: `Bearer ${token.value}` },
            body: form.value
        });
        mostrar('Personaje creado');
        navigateTo(`/personajes/${nuevo.id}`);
    } catch (e) {
        errores.value = e.data?.errors ?? {};
    }
}
</script>

<template>
    <div class="flex-1 p-8">
         <NuxtLink to="/personajes" class="text-orange-500 hover:underline">← Volver a personajes</NuxtLink>
        <form @submit.prevent="guardar" class="bg-gray-800 rounded-xl p-8 w-full max-w-2xl flex flex-col gap-4 mx-auto mt-8">
            <h1 class="text-3xl font-bold text-orange-500">Nuevo personaje</h1>

            <label class="flex flex-col gap-1">
                <span class="text-sm text-gray-400">Nombre *</span>
                <input v-model="form.name" type="text" class="bg-gray-700 rounded px-4 py-2" />
                <span v-if="errores.name" class="text-sm text-red-500">{{ errores.name[0] }}</span>
            </label>

            <div class="grid sm:grid-cols-2 gap-4">
                <label class="flex flex-col gap-1">
                    <span class="text-sm text-gray-400">Raza</span>
                    <input v-model="form.race" type="text" class="bg-gray-700 rounded px-4 py-2" />
                </label>
                <label class="flex flex-col gap-1">
                    <span class="text-sm text-gray-400">Género</span>
                    <input v-model="form.gender" type="text" class="bg-gray-700 rounded px-4 py-2" />
                </label>
                <label class="flex flex-col gap-1">
                    <span class="text-sm text-gray-400">Ki</span>
                    <input v-model="form.ki" type="text" placeholder="60.000.000" class="bg-gray-700 rounded px-4 py-2" />
                </label>
                <label class="flex flex-col gap-1">
                    <span class="text-sm text-gray-400">Ki máximo</span>
                    <input v-model="form.max_ki" type="text" placeholder="90 Septillion" class="bg-gray-700 rounded px-4 py-2" />
                </label>
            </div>

            <label class="flex flex-col gap-1">
                <span class="text-sm text-gray-400">Afiliación</span>
                <input v-model="form.affiliation" type="text" class="bg-gray-700 rounded px-4 py-2" />
            </label>

            <label class="flex flex-col gap-1">
                <span class="text-sm text-gray-400">URL de la imagen</span>
                <input v-model="form.image" type="url" placeholder="https://..." class="bg-gray-700 rounded px-4 py-2" />
                <span v-if="errores.image" class="text-sm text-red-500">{{ errores.image[0] }}</span>
            </label>

            <label class="flex flex-col gap-1">
                <span class="text-sm text-gray-400">Planeta de origen</span>
                <select v-model="form.planet_id" class="bg-gray-700 text-white rounded px-4 py-2 [&_option]:bg-gray-700">
                    <option value="">Sin planeta</option>
                    <option v-for="planeta in planetas?.data" :key="planeta.id" :value="planeta.id">
                        {{ planeta.name }}
                    </option>
                </select>
                <span v-if="errores.planet_id" class="text-sm text-red-500">{{ errores.planet_id[0] }}</span>
            </label>

            <label class="flex flex-col gap-1">
                <span class="text-sm text-gray-400">Descripción</span>
                <textarea v-model="form.description" rows="4" class="bg-gray-700 rounded px-4 py-2"></textarea>
            </label>

            <button type="submit" class="self-start rounded-full bg-orange-500 px-6 py-2 font-bold hover:bg-orange-600">
                Crear personaje
            </button>
        </form>
    </div>
</template>
