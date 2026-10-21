<script setup>
const config = useRuntimeConfig();

const name = ref('');
const email = ref('');
const password = ref('');
const passwordConfirmation = ref('');
const error = ref('');
const enviado = ref(false);

async function registrar() {
    error.value = '';
    try {
        await $fetch(`${config.public.apiBase}/register`, {
            method: 'POST',
            body: {
                name: name.value,
                email: email.value,
                password: password.value,
                password_confirmation: passwordConfirmation.value
            }
        });
        enviado.value = true;
    } catch (e) {
        error.value = e.data?.message || 'Error al registrarse';
    }
}
</script>

<template>
    <div class="flex-1 flex items-center justify-center p-8">
        <form v-if="!enviado" @submit.prevent="registrar" class="bg-gray-800 rounded-xl p-8 w-full max-w-sm flex flex-col gap-4">
            <h1 class="text-3xl font-bold text-orange-500 text-center">Crear cuenta</h1>

            <label class="flex flex-col gap-1">
                <span class="text-sm text-gray-400">Nombre</span>
                <input v-model="name" type="text" required class="bg-gray-700 rounded px-4 py-2" />
            </label>

            <label class="flex flex-col gap-1">
                <span class="text-sm text-gray-400">Email</span>
                <input v-model="email" type="email" required class="bg-gray-700 rounded px-4 py-2" />
            </label>

            <label class="flex flex-col gap-1">
                <span class="text-sm text-gray-400">Contraseña</span>
                <input v-model="password" type="password" required minlength="8" class="bg-gray-700 rounded px-4 py-2" />
            </label>

            <label class="flex flex-col gap-1">
                <span class="text-sm text-gray-400">Repite la contraseña</span>
                <input v-model="passwordConfirmation" type="password" required minlength="8" class="bg-gray-700 rounded px-4 py-2" />
            </label>

            <p v-if="error" class="text-sm text-red-500">{{ error }}</p>

            <button type="submit" class="rounded-full bg-orange-500 px-6 py-2 font-bold hover:bg-orange-600">
                Registrarse
            </button>

            <p class="text-sm text-gray-400 text-center">
                ¿Ya tienes cuenta?
                <NuxtLink to="/login" class="text-orange-500 hover:underline">Inicia sesión</NuxtLink>
            </p>
        </form>

        <div v-else class="bg-gray-800 rounded-xl p-8 w-full max-w-sm flex flex-col gap-4 text-center">
            <h1 class="text-3xl font-bold text-orange-500">¡Cuenta creada!</h1>
            <p class="text-gray-300">Revisa tu email y pulsa el enlace para confirmar tu cuenta. Después podrás iniciar sesión.</p>
            <NuxtLink to="/login" class="rounded-full bg-orange-500 px-6 py-2 font-bold hover:bg-orange-600">Ir a iniciar sesión</NuxtLink>
        </div>
    </div>
</template>
