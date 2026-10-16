export function useToast() {
    const mensaje = useState('toast', () => '');

    function mostrar(texto) {
        mensaje.value = texto;
        setTimeout(() => {
            mensaje.value = '';
        }, 3000);
    }

    return { mensaje, mostrar };
}
