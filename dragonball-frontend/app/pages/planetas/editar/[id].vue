<script setup>
definePageMeta({ middleware: 'auth' });

const route = useRoute();
const config = useRuntimeConfig();
const token = useCookie('token');
const { mostrar } = useToast();

const { data: planeta } = await useFetch(`${config.public.apiBase}/planets/${route.params.id}`);

const form = ref({
    name: planeta.value?.name ?? '',
    is_destroyed: planeta.value?.is_destroyed ?? false,
    image: planeta.value?.image ?? '',
    description: planeta.value?.description ?? ''
});

const errores = ref({});

async function guardar() {
    errores.value = {};
    try {
        await $fetch(`${config.public.apiBase}/planets/${route.params.id}`, {
            method: 'PUT',
            headers: { Authorization: `Bearer ${token.value}` },
            body: form.value
        });
        mostrar('Planeta actualizado');
        navigateTo(`/planetas/${route.params.id}`);
    } catch (e) {
        errores.value = e.data?.errors ?? {};
    }
}
</script>

<template>
    <div class="flex-1 p-8">
        <NuxtLink :to="`/planetas/${route.params.id}`" class="text-orange-500 hover:underline">← Volver a la ficha</NuxtLink>

        <form @submit.prevent="guardar" class="bg-gray-800 rounded-xl p-8 w-full max-w-2xl flex flex-col gap-4 mx-auto mt-8">
            <h1 class="text-3xl font-bold text-orange-500">Editar planeta</h1>

            <label class="flex flex-col gap-1">
                <span class="text-sm text-gray-400">Nombre *</span>
                <input required v-model="form.name" type="text" class="bg-gray-700 rounded px-4 py-2 border border-transparent user-invalid:border-red-500" :class="errores.name ? 'border-red-500' : ''" />
                <span v-if="errores.name" class="text-sm text-red-500">{{ errores.name[0] }}</span>
            </label>

            <label class="flex items-center gap-3">
                <input v-model="form.is_destroyed" type="checkbox" class="h-5 w-5 accent-orange-500" />
                <span>Destruido</span>
            </label>

            <label class="flex flex-col gap-1">
                <span class="text-sm text-gray-400">URL de la imagen</span>
                <input v-model="form.image" required type="url" class="bg-gray-700 rounded px-4 py-2 border border-transparent user-invalid:border-red-500" :class="errores.image ? 'border-red-500' : ''" />
                <span v-if="errores.image" class="text-sm text-red-500">{{ errores.image[0] }}</span>
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
