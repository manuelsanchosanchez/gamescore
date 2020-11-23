# GameScore

## Base de datos

Crearemos la base de datos que usaremos en la web. Lo podemos hacer desde phpmyadmin o con comandos:

CREATE DATABASE gamescore CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

Creamos el usuario que accedera a la base de datos y le damos privilegios a la base de datos y sus tablas

CREATE USER 'gamescore'@'%' IDENTIFIED BY 'g@m€sc0r3';
GRANT ALL PRIVILEGES ON gamescore.* TO 'gamescore'@'%';

## Tablas
Creamos las tablas que utilizaremos. En el repositorio está el fichero con la descripcion de tablas para usarlo directamente gamescore.sql.

## Usuario admin
Creamos el usuario admin para la aplicacion

INSERT INTO gamescore.usuarios(usuario, password, email, nivel, estado) VALUES ('admin','94662ad7fef2a7fdedeae615485dcc4ea3d73d97','admin@admin.es','100','1');

## Github
Clonamos o descargamos el repositorio para trabajar con él

## Instalar dependencias
Necesitaremos instalar con composer la librería ghunti/highcharts-php https://packagist.org/packages/ghunti/highcharts-phpla

