# Dragon Ball Web

Web hecha con Nuxt que usa la API de Laravel para mostrar los personajes y planetas de Dragon Ball.

Se pueden ver, buscar y filtrar, y si inicias sesión también se pueden crear, editar y borrar.

## Tecnologías

- Nuxt 4.5.2
- Tailwind CSS 4

Uso la versión 4.5.2 de Nuxt porque la 4.6.0 daba error al arrancar.

## Instalación

1. Tener la API de Laravel funcionando (ver su README).
2. Instalar las librerías con npm install
3. Arrancar la web con npm run dev
4. Abrir en el navegador http://localhost:3000

La dirección de la API se cambia en el archivo nuxt.config.ts

## Páginas

- Portada: personajes destacados con animaciones.
- Personajes: lista con buscador, filtro por raza y páginas.
- Ficha del personaje: sus datos y su planeta.
- Planetas: lista con buscador, filtro de destruidos y páginas.
- Ficha del planeta: sus datos y sus habitantes.
- Login: para entrar a la zona de administración.

## Zona de administración

Solo se puede entrar con login.

- Crear personajes y planetas.
- Editar personajes y planetas.
- Cambiar un personaje de planeta.
- Borrar personajes y planetas.

## Usuario de prueba

- Email: admin@example.com
- Contraseña: password
