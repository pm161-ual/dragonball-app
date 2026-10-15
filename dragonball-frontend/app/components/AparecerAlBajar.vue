<script setup>

const props =defineProps({
    desde: {
        type: String,
        default: 'izquierda'
    }
});

const caja = ref(null);
const visible = ref(false);

onMounted(() => {
    const vigilante = new IntersectionObserver(([entrada]) =>{
        if (entrada.isIntersecting) {
            visible.value = true
            vigilante.disconnect()
        }
    }, { threshold: 0.3 });
    vigilante.observe(caja.value)
})

</script>


<template>

<div ref="caja" class="transition-all duration-1000" :class="visible  ?  'opacity-100 translate-x-0'  :  ( desde === 'derecha'  ?  'opacity-0 translate-x-24'  :  'opacity-0 -translate-x-24' )">
<slot/>
</div>
    
</template>