# GameScore :video_game:
_Tutorial CRUD Mysql con PHP7, PDO y Sesiones_


## Antes de empezar :memo:
_Deberás tener todas las herramientas necesarias para empezar a trabajar con este proyecto_

### Herramientas :hammer_and_wrench:
_Puedes descargar e instalar manualmente todas estas herramientas, mediante apt u otro gestor de paquetes o puedes instalar una distribución que ya lo incluya como [XAMPP](https://www.apachefriends.org/es/index.html)_

* [Apache](https://httpd.apache.org/) - Necesitarás tener un servidor Apache. activo

* [PHP7](https://www.php.net/) - Necesitarás la versión PHP 7.

* [Mysql](https://www.mysql.com/) - Como motor de base de datos usaremos Mysql.

* [Composer](https://getcomposer.org/) - Para la instalación de la libreria de gráficas usaremos Composer.

* [highcharts](https://packagist.org/packages/ghunti/highcharts-php) - Usaremos esta librería para crear gráficas

## Base de datos :key:
_Necesitaremos crear la base de datos, usuarios y tablas que usarán la aplicación_

### Crear la base de datos
_Crearemos la base de datos que usaremos en la web. Lo podemos hacer desde phpmyadmin o con comandos en la consola mysql_

```
CREATE DATABASE gamescore CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

### Crear el usuario de la base de datos
_Creamos el usuario que accedera a la base de datos y le damos privilegios a la base de datos y sus tablas_

```
CREATE USER 'gamescore'@'%' IDENTIFIED BY 'g@m€sc0r3';
GRANT ALL PRIVILEGES ON gamescore.* TO 'gamescore'@'%';
```

### Crear las tablas
_Creamos las tablas que utilizaremos. En el repositorio está el fichero con la descripcion de tablas para importarlo directamente [gamescore.sql](https://github.com/manugineta/gamescore/blob/master/gamescore.sql)_

### Insertar el usuario admin
_Creamos el usuario admin en la tabla de usuarios para la aplicacion. Tanto el usuario como la contraseña son 'admin'_

```
INSERT INTO gamescore.usuarios(usuario, password, email, nivel, estado) VALUES ('admin','94662ad7fef2a7fdedeae615485dcc4ea3d73d97','admin@admin.es','100','1');
```

## Repositorio Github :file_folder:
_Clonamos y/o descargamos el repositorio para trabajar con él_


## Mejoras a realizar :star:
* Paginación de resultados
* Creación de usuarios desde sección admin
* Sección de preferencias de usuario
* Añadir notas de los juegos por parte de los usuarios


