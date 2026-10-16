<script setup>
const config = useRuntimeConfig();

const email = ref('');
const password = ref('');
const error = ref('');
const { mostrar } = useToast();


const token = useCookie('token');

async function entrar() {
    error.value = '';
    try {
        const respuesta = await $fetch(`${config.public.apiBase}/login`, {
            method: 'POST',
            body: {
                email: email.value,
                password: password.value
            }
        });
        token.value = respuesta.token;
        mostrar('Bienvenido');
        navigateTo('/personajes');
    } catch {
        error.value = 'Email o contraseña incorrectos';
    }
}
</script>

<template>
    <div class="flex-1 flex items-center justify-center p-8">
        <form @submit.prevent="entrar" class="bg-gray-800 rounded-xl p-8 w-full max-w-sm flex flex-col gap-4">
            <h1 class="text-3xl font-bold text-orange-500 text-center">Acceder</h1>

            <label class="flex flex-col gap-1">
                <span class="text-sm text-gray-400">Email</span>
                <input v-model="email" type="email" required class="bg-gray-700 rounded px-4 py-2" />
            </label>

            <label class="flex flex-col gap-1">
                <span class="text-sm text-gray-400">Contraseña</span>
                <input v-model="password" type="password" required class="bg-gray-700 rounded px-4 py-2" />
            </label>

            <p v-if="error" class="text-sm text-red-500">{{ error }}</p>

            <button type="submit" class="rounded-full bg-orange-500 px-6 py-2 font-bold hover:bg-orange-600">
                Login
            </button>
        </form>
    </div>
</template>
