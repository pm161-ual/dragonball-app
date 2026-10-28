# Manual de usuario – Dragon Ball App

Dragon Ball App es una aplicación web (también disponible como app Android) para explorar el universo de Dragon Ball. Permite consultar los personajes y planetas de la serie, buscarlos y filtrarlos, y chatear con los personajes gracias a la inteligencia artificial. Los administradores, además, pueden crear, editar y borrar personajes y planetas.

Este manual explica qué puede hacer cada tipo de usuario y cómo usar cada sección de la web.

## Roles

### Visitante

Puede navegar por la web sin cuenta:

- Ver la página de inicio.
- Consultar el listado de personajes (búsqueda, filtros, ordenación, paginación y vista en fichas o tabla) y el detalle de cada uno.
- Consultar el listado de planetas y el detalle de cada uno.
- Registrarse e iniciar sesión.

### Usuario registrado

Lo mismo que el visitante, más chatear con los personajes. Solo puede consultar, no modificar ni crear.

### Administrador

Puede hacer todo lo anterior y, además:

- Crear, editar y borrar personajes (al borrar se pide confirmación).
- Cambiar el planeta de un personaje.
- Crear, editar y borrar planetas.

## ¿Cómo se consigue cada rol?

- Visitante: entra sin cuenta.
- Registrado: se registra y confirma el email.
- Admin: cuenta creada por el sistema; no se puede registrar como admin.

## Secciones de la web

### Página de inicio

Es la primera pantalla de la web. Arriba está la cabecera, que aparece en todas las páginas, con el logo, el menú (Personajes y Planetas), los iconos de contacto y GitHub y el botón Acceder.

La página tiene tres partes:

- Una parte de Bienvenida: el título "Bienvenido al mundo de Dragon Ball" y un botón que lleva al listado de personajes.
- Una parte de Personajes destacados: una selección de personajes con su imagen y raza.
- Una parte de Explora los planetas: un botón que lleva al listado de planetas.

Desde el botón Acceder se puede iniciar sesión o, si no tienes cuenta, registrarte.

### Personajes

Se accede a esta página desde el menú Personajes.  Esta página muestra todos los personajes, con una paginación donde salen 12 personajes por cada página.

- Buscar y filtrar:
    - Buscador: escribe un nombre y la lista se filtra.
    - Raza: un desplegable para ver solo una raza (Humano, Saiyan, Namekian, etc.).
- Tipos de vista: con los botones Fichas y Tabla se cambia la forma de ver la lista:
    - Fichas: tarjetas con la imagen y el nombre de cada personaje.
    - Tabla: una fila por personaje con imagen, nombre, raza, género, afiliación y planeta.
        - Ordenar: al pulsar el título de una columna (Nombre, Raza, Género o Afiliación) se ordena. Un clic ordena ascendente, el segundo descendente y el tercero quita el orden.
        - Filtrar por columna: debajo de los títulos hay una fila de filtros (nombre, raza, género, afiliación y planeta).

- Paginación: abajo están los botones Anterior y Siguiente, y el texto "Página X de Y".
- Ver un personaje: al pulsar una ficha o una fila de la tabla se abre su detalle.
- Solo administrador: aparece el botón Nuevo personaje (se explica en la sección de administración).

### Detalle del personaje

Se abre al pulsar un personaje en el listado o en los destacados del inicio.

- Volver: el enlace "Volver a personajes" regresa al listado.
- Datos: nombre, raza, género, afiliación, ki, ki máximo y una descripción.
- Planeta: si el personaje tiene planeta, aparece su imagen y su nombre. Al pulsarlo se abre el detalle de ese planeta.
- Chat con el personaje: al final de la página está el apartado Habla con [nombre], donde puedes conversar con el personaje como si fuera él (con inteligencia artificial).
    - Sin sesión: aparece el mensaje "Inicia sesión para hablar con…" con un enlace al login.
    - Con sesión:
        1. Escribe en la caja "Escribe un mensaje..." y pulsa Enviar o Enter.
        2. Mientras responde se ve "[nombre] está escribiendo...", y el botón se desactiva.
        3. La respuesta aparece poco a poco, con la foto del personaje al lado.
        - El personaje recuerda lo que habéis hablado mientras sigas en la página (al recargar se borra).
        - Errores: si envías demasiados mensajes (más de 10 por minuto) verás "Demasiados mensajes. Espera un minuto." Si falla la IA verás "[nombre] no puede responder ahora mismo."
- Solo administrador: aparecen los botones Editar y Borrar personaje, que se explican en la sección de administración.

### Planetas

Se accede desde el menú Planetas. Funciona igual que el listado de personajes, con estas diferencias:

- Buscador: busca planetas por nombre.
- Estado: un desplegable para ver todos los planetas, solo los intactos o solo los destruidos.
- Vista Fichas: tarjeta con la imagen y el nombre del planeta.
- Vista Tabla: columnas de imagen, nombre, estado y número de habitantes. Se puede ordenar por nombre, estado o habitantes, con el mismo sistema de clics que en personajes.
- Paginación, ver un planeta al pulsarlo y, solo para el administrador, el botón Nuevo planeta.

### Detalle del planeta

- Volver: el enlace "Volver a planetas" regresa al listado.
- Datos: nombre, estado (Intacto o Destruido) y descripción.
- Habitantes: los personajes que viven en ese planeta, con su imagen y su nombre. Al pulsar uno se abre su detalle.
- Solo administrador: botones Editar y Borrar planeta.

### Registro

Se llega desde el botón Acceder y luego el enlace Regístrate.

1. Rellena Nombre, Email, Contraseña (mínimo 8 caracteres) y Repite la contraseña.
2. Pulsa el botón Registrarse. Aparece la pantalla ¡Cuenta creada!
3. Abre tu correo y pulsa el enlace de confirmación. Te llevará a la pantalla de login.
4. Ya puedes iniciar sesión.

Si algo falla (email ya usado, las contraseñas no coinciden…), aparece un mensaje en rojo encima del botón.

### Inicio de sesión

Se llega desde el botón Acceder de la cabecera.

1. Escribe tu Email y tu Contraseña y pulsa Login.
2. Si todo va bien, entras al listado de personajes y en la cabecera aparece Cerrar sesión en lugar de Acceder.

Posibles errores:
- "Email o contraseña incorrectos".
- "Debes verificar tu email antes de iniciar sesión": todavía no has pulsado el enlace del correo.

3. Cerrar sesión: pulsa Cerrar sesión en la cabecera.

## Zona de administración

Solo los usuarios con rol administrador ven estas opciones. Después de cada acción aparece un aviso en pantalla durante unos segundos (por ejemplo, "Personaje creado").

### Crear un personaje

1. En el listado de personajes, pulsa Nuevo personaje.
2. Rellena todos los campos: Nombre, Raza, Género, Ki, Ki máximo, Afiliación, URL de la imagen, Planeta de origen y Descripción.
3. Pulsa el botón de crear personaje. Se abre la ficha del personaje recién creado.

### Editar un personaje / cambiar de planeta

1. En el detalle del personaje, pulsa Editar.
2. El formulario aparece con los datos actuales. Cambia lo que quieras.
3. Para cambiar de planeta, elige otro en el desplegable Planeta de origen (o "Sin planeta").
4. Guarda los cambios. Vuelves a la ficha con los cambios aplicados.

### Borrar un personaje

1. En el detalle del personaje, pulsa Borrar personaje.
2. Aparece una ventana de confirmación: "¿Estás seguro de borrar a [nombre]?".
3. Pulsa Sí, borrar para confirmar, o Cancelar (o haz clic fuera de la ventana) para no hacer nada.
4. Vuelves al listado de personajes.

### Gestionar planetas

Funciona igual que con los personajes:

- Crear: botón Nuevo planeta en el listado. Campos: Nombre, Destruido (casilla), URL de la imagen y Descripción.
- Editar: botón Editar en el detalle del planeta.
- Borrar: botón Borrar planeta, con la misma ventana de confirmación. Los personajes que vivían en ese planeta no se borran: se quedan sin planeta.