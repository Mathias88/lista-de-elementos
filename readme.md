## Listado de elemento SPA

Simple SPA de listado de elementos donde se puede agregar, editar y eliminar elementos. También, permite ordenarlos con drag&drop y muestra cantidad de elementos añadidos.

## Tecnologías utilizadas

Para el desarrollo de la aplicación se utilizó:

- **Laravel 5.7**
- **MySql**
- **jQuery**
- **[html5sortable](https://github.com/lukasoppermann/html5sortable)**
- **Bootstrap 4**
- **HTML5**

## Instalación

Para ejecutar la aplicación debe seguir los siguientes pasos:

- Clonar repositorio
```
git clone https://github.com/Mathias88/lista-de-elementos.git
```
- Instalar dependencias
```
composer install
npm install
npm run prod
```
- Modificar archivo .env con los datos de tu base de datos.

- Ejecutar migraciones
```
php artisan migrate
```

- Generar key
```
php artisan key:generate
```

- Generar link simbólico
```
php artisan storage:link
```
