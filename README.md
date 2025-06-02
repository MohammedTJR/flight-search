# Flylow.es - Comparador de Precios de Vuelos

                                                                                                         ![Logo Flylow](https://flylow.es/img/favicon.png)

## Introducción

Flylow.es es una plataforma web diseñada para facilitar la comparación de precios de vuelos entre diferentes aerolíneas y agencias. Con una interfaz clara, accesible y optimizada para todo tipo de dispositivos, Flylow.es va más allá de un simple buscador, incorporando funcionalidades de valor añadido como:

- Filtros inteligentes de búsqueda
- Visualización detallada de escalas y clases
- Integración con radar aéreo en tiempo real
- Historial de precios con análisis de tendencias

![Interfaz de búsqueda](https://via.placeholder.com/800x400?text=Interfaz+de+B%C3%BAsqueda)

Este proyecto ha sido desarrollado como parte del módulo de Proyecto Integrado del Ciclo Formativo de Grado Superior en Desarrollo de Aplicaciones Web (DAW), representando una solución realista y útil con potencial de crecimiento en el mercado digital actual.

## Principales Características

- **Comparador de precios** entre múltiples aerolíneas y agencias
- **Filtros avanzados** por precio, duración, escalas y más
- **Radar aéreo** con seguimiento en tiempo real
- **Historial de precios** con análisis predictivo
- **Alertas personalizables** de bajadas de precio
- **Diseño responsive** para todos los dispositivos

![Resultados de búsqueda](https://via.placeholder.com/800x500?text=Resultados+de+B%C3%BAsqueda)

## Guía de Instalación Local

### Requisitos Previos

- PHP 8.2 o superior
- Composer
- Node.js y npm
- MySQL 5.7 o superior
- Git (opcional)

![Diagrama de arquitectura](https://via.placeholder.com/600x300?text=Arquitectura+del+Sistema)

### Proceso de Instalación

1. **Clonar o descargar el repositorio**

```shell script
git clone [url-del-repositorio]
```


O descomprima el archivo ZIP en la ubicación deseada.

2. **Navegar al directorio del proyecto**

```shell script
cd flylow
```


3. **Configurar el entorno**

```shell script
cp .env.example .env
```


Edite el archivo `.env` con la configuración de su entorno local:

```
APP_NAME="FlyLow"
   APP_ENV=local
   APP_DEBUG=true
   APP_URL=http://localhost
   
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=vuelos
   DB_USERNAME=tu_usuario
   DB_PASSWORD=tu_contraseña
```


![Configuración del entorno](https://via.placeholder.com/600x300?text=Configuraci%C3%B3n+del+Entorno)

4. **Instalar dependencias**

```shell script
composer install
   npm install
```


5. **Generar clave de aplicación**

```shell script
php artisan key:generate
```


6. **Preparar la base de datos**

   Cree una base de datos MySQL con el nombre configurado en `.env` y ejecute:

```shell script
php artisan migrate
   php artisan db:seed
```


![Estructura de base de datos](https://via.placeholder.com/600x400?text=Estructura+DB)

7. **Compilar assets**

   Para desarrollo:
```shell script
npm run dev
```


Para producción:
```shell script
npm run build
```


8. **Iniciar el servidor**

```shell script
php artisan serve
```


La aplicación estará disponible en http://localhost:8000

![Panel de control](https://via.placeholder.com/800x400?text=Panel+de+Control)

### Configuración Adicional

- **Permisos de carpetas**
```shell script
chmod -R 777 storage
  chmod -R 777 bootstrap/cache
```


- **Claves de API**: Revise el archivo `apikeys.txt` incluido para configurar correctamente las claves de API necesarias.

### Solución de Problemas

Si encuentra problemas durante la instalación:

- **Limpiar cachés**:
```shell script
php artisan config:clear
  php artisan cache:clear
  php artisan view:clear
  php artisan route:clear
```


- **Verificar versiones** de PHP y Node.js
- **Comprobar credenciales** de la base de datos
- **Ajustar permisos** de archivos y carpetas

![FAQ y Ayuda](https://via.placeholder.com/800x400?text=Secci%C3%B3n+de+Ayuda)

Para más información o soporte, contacte con el desarrollador: Mohammed Said Touijar.
