<script setup>
const route = useRoute();
const config = useRuntimeConfig();
const token = useCookie('token');
const { mostrar } = useToast();

const { data: personaje } = await useFetch(`${config.public.apiBase}/characters/${route.params.id}`);

const form = ref({
    name: personaje.value?.name ?? '',
    race: personaje.value?.race ?? '',
    gender: personaje.value?.gender ?? '',
    affiliation: personaje.value?.affiliation ?? '',
    ki: personaje.value?.ki ?? '',
    max_ki: personaje.value?.max_ki ?? '',
    image: personaje.value?.image ?? '',
    description: personaje.value?.description ?? '',
    planet_id: personaje.value?.planet_id ?? ''
});

const errores = ref({});

const { data: planetas } = await useFetch(`${config.public.apiBase}/planets`, {
    query: { per_page: 100 }
});

async function guardar() {
    errores.value = {};
    try {
        await $fetch(`${config.public.apiBase}/characters/${route.params.id}`, {
            method: 'PUT',
            headers: { Authorization: `Bearer ${token.value}` },
            body: form.value
        });
        mostrar('Personaje actualizado');
        navigateTo(`/personajes/${route.params.id}`);
    } catch (e) {
        errores.value = e.data?.errors ?? {};
    }
}



</script>

<template>
    <div class="flex-1 p-8">
        <NuxtLink :to="`/personajes/${route.params.id}`" class="text-orange-500 hover:underline">
            ← Volver a la ficha
        </NuxtLink>

        <form @submit.prevent="guardar" class="bg-gray-800 rounded-xl p-8 w-full max-w-2xl flex flex-col gap-4 mx-auto mt-8">
            <h1 class="text-3xl font-bold text-orange-500">✏️ Editar personaje</h1>

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
                    <input v-model="form.ki" type="text" class="bg-gray-700 rounded px-4 py-2" />
                </label>
                <label class="flex flex-col gap-1">
                    <span class="text-sm text-gray-400">Ki máximo</span>
                    <input v-model="form.max_ki" type="text" class="bg-gray-700 rounded px-4 py-2" />
                </label>
            </div>

            <label class="flex flex-col gap-1">
                <span class="text-sm text-gray-400">Afiliación</span>
                <input v-model="form.affiliation" type="text" class="bg-gray-700 rounded px-4 py-2" />
            </label>

            <label class="flex flex-col gap-1">
                <span class="text-sm text-gray-400">URL de la imagen</span>
                <input v-model="form.image" type="url" class="bg-gray-700 rounded px-4 py-2" />
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
                <textarea v-model="form.description" rows="6" class="bg-gray-700 rounded px-4 py-2"></textarea>
            </label>

            <button type="submit" class="self-start rounded-full bg-orange-500 px-6 py-2 font-bold hover:bg-orange-600">
                Guardar cambios
            </button>
        </form>
    </div>
</template>
