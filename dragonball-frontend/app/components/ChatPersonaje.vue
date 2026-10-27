<script setup>
const props = defineProps({
    personaje: Object
});

const config = useRuntimeConfig();
const token = useCookie('token');

const mensajes = ref([]);
const texto = ref('');
const escribiendo = ref(false);
const error = ref('');

async function enviar() {
    if (!texto.value.trim() || escribiendo.value) return;

    const mensaje = texto.value;
    const historial = mensajes.value.slice(-20);

    mensajes.value.push({ role: 'user', text: mensaje });
    texto.value = '';
    escribiendo.value = true;
    error.value = '';

    try {
        const respuesta = await fetch(`${config.public.apiBase}/chat/stream`, {
            method: 'POST',
            headers: { Authorization: `Bearer ${token.value}`, 'Content-Type': 'application/json', Accept: 'application/json' },
            body: JSON.stringify({
                character_id: props.personaje.id,
                message: mensaje,
                history: historial
            })
        });
        if (!respuesta.ok) {
            throw {statusCode: respuesta.status};
        }
        mensajes.value.push({ role: 'model', text: '' });
        const actual = mensajes.value[mensajes.value.length - 1];
               const lector = respuesta.body.getReader();
        const decodificador = new TextDecoder();
        let pendiente = '';

        while (true) {
            const { done, value } = await lector.read();
            if (done) break;

            pendiente += decodificador.decode(value, { stream: true });
            const pedazos = pendiente.split('\n\n');
            pendiente = pedazos.pop();

            for (const pedazo of pedazos) {
                if (!pedazo.startsWith('data: ')) continue;

                const datos = pedazo.slice(6);
                if (datos === '[DONE]') continue;

                actual.text += JSON.parse(datos).reply;
            }
        }

    } catch (e) {
        if (e.statusCode === 429) {
            error.value = 'Demasiados mensajes. Espera un minuto.';
        } else {
            error.value = `${props.personaje.name} no puede responder ahora mismo.`;
        }
    } finally {
        escribiendo.value = false;
    }
}

</script>

<template>
    <div class="relative bg-gray-800 rounded-xl p-4 mt-8 max-w-2xl mx-auto w-full">
        <h2 class="text-2xl font-bold text-orange-500 mb-4">Habla con {{ personaje.name }}</h2>

        <p v-if="!token" class="text-gray-400">
            <NuxtLink to="/login" class="text-orange-500 hover:underline">Inicia sesión</NuxtLink>
            para hablar con {{ personaje.name }}.
        </p>

        <template v-else>
            <div class="flex flex-col gap-3 h-80 overflow-y-auto mb-4">
                <p v-if="mensajes.length === 0" class="text-gray-500 text-center mt-auto mb-auto">
                    Escribe algo para empezar la conversación.
                </p>

                <div v-for="(m, i) in mensajes" :key="i"
                    :class="m.role === 'user' ? 'flex justify-end' : 'flex justify-start gap-2'">
                    <img v-if="m.role === 'model'" :src="personaje.image" :alt="personaje.name"
                        class="h-8 w-8 rounded-full object-cover object-top shrink-0" />
                    <p :class="m.role === 'user' ? 'bg-orange-500' : 'bg-gray-700'"
                        class="rounded-2xl px-4 py-2 max-w-[80%] whitespace-pre-line">
                        {{ m.text }}
                    </p>
                </div>

                <p v-if="escribiendo" class="text-gray-400 text-sm animate-pulse">
                    {{ personaje.name }} está escribiendo...
                </p>
            </div>

            <p v-if="error" class="text-red-500 text-sm mb-2">{{ error }}</p>

            <form @submit.prevent="enviar" class="flex gap-2">
                <input v-model="texto" type="text" maxlength="1000" placeholder="Escribe un mensaje..."
                    class="flex-1 bg-gray-700 rounded-full px-4 py-2" />
                <button type="submit" :disabled="escribiendo || !texto.trim()"
                    class="rounded-full bg-orange-500 px-5 py-2 font-bold hover:bg-orange-600 disabled:opacity-30">
                    Enviar
                </button>
            </form>
        </template>
    </div>
</template>
