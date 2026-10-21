export default defineNuxtRouteMiddleware(() => {
    const esAdmin = useCookie('esAdmin');

    if (!esAdmin.value) {
        return navigateTo('/login');
    }
});
