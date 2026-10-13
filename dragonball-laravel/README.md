# Dragon Ball API

API REST hecha con Laravel para gestionar personajes y planetas de Dragon Ball. 

Los datos se importan de dragonball-api.com

La web que la usa está en el proyecto Nuxt.

## Tecnologías

- Laravel 12 (PHP 8.2)
- MySQL: La base de datos
- Sanctum: El login con tokens
- Swagger: La documentación
- PHPUnit: los tests

## Instalación

1. Instalación de las librerias con composer install
2. Creación del archivo de configuración: copy .env.example .env
3. Generar la clave de la app con php artisan key:generate
4. Creación de la base de datos llamada "dragonball" en MySQL.
5. Creación de las tablas y el usuario admin con php artisan migrate --seed
6. Importación de los personajes y planetas con php artisan dragonball:import

## Uso

- Arrancar el servidor con php artisan serve.
- Las rutas de la API empiezan por http://127.0.0.1:8000/api
- La documentación Swagger está en http://127.0.0.1:8000/api/documentation
- Usuario de prueba: admin@example.com con contraseña password
- Para el CRUD hace falta hacer login y usar el token(en Swagger, con el botón Authorize)

## Endpoints

- POST: ruta /api/login, inicia sesión.
- POST: ruta /api/logout, cierra sesión.
- GET: ruta /api/planets, lista los planetas (filtros: name, is_destroyed).
- GET: ruta /api/planets/{id}, ver un planeta.
- POST: ruta /api/planets, crea un planeta.
- PUT: ruta	/api/planets/{id}, modifica un planeta.
- DELETE: ruta /api/planets/{id}, borra un planeta.
- GET: ruta /api/characters, lista personajes.(filtros: name, race, gender, affiliation, planet_id)
- GET: ruta /api/characters/{id}, ver un personaje con su planeta.
- POST: ruta /api/characters, crea un personaje.
- PUT: ruta /api/characters/{id}, modifica un personaje o cambiarlo de planeta.
- DELETE: ruta /api/characters/{id}, borra un personaje.
- Los que no necesitan token son: POST de login y el GET.
- Los que si necesitan token son: POST de logout, POST de creación, PUT y DELETE.

## Tests

- Se ejecutan con php artisan test.
- Son 18 tests: de integración (planetas, personajes y login) y unitarios(modelos)
- Usan una base de datos de memoria, así que no tocan los datos reales.