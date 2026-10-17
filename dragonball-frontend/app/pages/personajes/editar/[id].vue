<script setup>

definePageMeta({ middleware: 'auth' })
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
            <h1 class="text-3xl font-bold text-orange-500">Editar personaje</h1>

            <label class="flex flex-col gap-1">
                <span class="text-sm text-gray-400">Nombre *</span>
                <input v-model="form.name" type="text" required class="bg-gray-700 rounded px-4 py-2 border border-transparent user-invalid:border-red-500" :class="errores.name ? 'border-red-500' : ''" />
                <span v-if="errores.name" class="text-sm text-red-500">{{ errores.name[0] }}</span>
            </label>

            <div class="grid sm:grid-cols-2 gap-4">
                <label class="flex flex-col gap-1">
                    <span class="text-sm text-gray-400">Raza</span>
                    <input v-model="form.race" required type="text" class="bg-gray-700 rounded px-4 py-2 border border-transparent user-invalid:border-red-500" :class="errores.race ? 'border-red-500' : ''" />
                </label>
                <label class="flex flex-col gap-1">
                    <span class="text-sm text-gray-400">Género</span>
                    <select v-model="form.gender" required class="bg-gray-700 text-white rounded px-4 py-2 [&_option]:bg-gray-700 border border-transparent user-invalid:border-red-500" :class="errores.gender ? 'border-red-500' : ''">
                        <option value="">Elija un género</option>
                        <option value="Male">Masculino</option>
                        <option value="Female">Femenino</option>
                    </select>
                </label>
                <label class="flex flex-col gap-1">
                    <span class="text-sm text-gray-400">Ki</span>
                    <input v-model="form.ki" required type="text" class="bg-gray-700 rounded px-4 py-2 border border-transparent user-invalid:border-red-500" :class="errores.ki ? 'border-red-500' : ''" />
                </label>
                <label class="flex flex-col gap-1">
                    <span class="text-sm text-gray-400">Ki máximo</span>
                    <input v-model="form.max_ki" required type="text" class="bg-gray-700 rounded px-4 py-2 border border-transparent user-invalid:border-red-500" :class="errores.max_ki ? 'border-red-500' : ''" />
                </label>
            </div>

            <label class="flex flex-col gap-1">
                <span class="text-sm text-gray-400">Afiliación</span>
                <input v-model="form.affiliation" required type="text" class="bg-gray-700 rounded px-4 py-2 border border-transparent user-invalid:border-red-500" :class="errores.affiliation ? 'border-red-500' : ''" />
            </label>

            <label class="flex flex-col gap-1">
                <span class="text-sm text-gray-400">URL de la imagen</span>
                <input v-model="form.image" required type="url" class="bg-gray-700 rounded px-4 py-2 border border-transparent user-invalid:border-red-500" :class="errores.image ? 'border-red-500' : ''" />
                <span v-if="errores.image" class="text-sm text-red-500" required>{{ errores.image[0] }}</span>
            </label>

            <label class="flex flex-col gap-1">
                <span class="text-sm text-gray-400">Planeta de origen</span>
                <select v-model="form.planet_id" required class="bg-gray-700 text-white rounded px-4 py-2 [&_option]:bg-gray-700 border border-transparent user-invalid:border-red-500" :class="errores.planet_id ? 'border-red-500' : ''">
                    <option value="">Sin planeta</option>
                    <option v-for="planeta in planetas?.data" :key="planeta.id" :value="planeta.id">
                        {{ planeta.name }}
                    </option>
                </select>
                <span v-if="errores.planet_id" class="text-sm text-red-500">{{ errores.planet_id[0] }}</span>
            </label>

            <label class="flex flex-col gap-1">
                <span class="text-sm text-gray-400">Descripción</span>
                <textarea v-model="form.description" required rows="6" class="bg-gray-700 rounded px-4 py-2 border border-transparent user-invalid:border-red-500" :class="errores.description ? 'border-red-500' : ''"></textarea>
            </label>

            <button type="submit" class="self-start rounded-full bg-orange-500 px-6 py-2 font-bold hover:bg-orange-600">
                Guardar cambios
            </button>
        </form>
    </div>
</template>
