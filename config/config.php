<?php
    //constantes datos bd
    define("DB_SERVIDOR", "localhost");
    define("DB_USUARIO", "gamescore");
    define("DB_PASSWORD", "g@m€sc0r3");
    define("DB_DATABASE", "gamescore");
    define("BASE_URL", "http://localhost/gamescore/");

    //constantes gestion bd
    define("REGISTRO_EXISTE",-401);
    define("ERROR_INSERTA_REGISTRO",-402);
    define("ERROR_LONGITUD",-403);
    define("ERROR_ACTUALIZA_REGISTRO",-404);
    define("ERROR_FALTAN_DATOS",-405);
    define("ERROR_ELIMINA_REGISTRO",-406);
    
    define("REGISTRO_INSERTADO",400);
    define("REGISTRO_ACTUALIZADO",401);
    define("REGISTRO_ELIMINADO",402);

    //constantes para el login y sesiones
    define("LOGIN_ADMIN", 100);
    define("LOGIN_USUARIO", 101);
    define("USUARIO_NO_ENCONTRADO", -100);
    define("USUARIO_BLANCO", -101);
    define("SESION_CERRADA",110);
    define("ERROR_CERRAR_SESION",-110);
    define("NO_TIENES_ACCESO",-111);

    //constantes mensajes creacion usuario
    define("USUARIO_CREADO", 200);
    define("USUARIO_ACTUALIZADO", 201);
    define("ERROR_ACTUALIZAR_USUARIO",-201);

    define("USUARIO_YA_EXISTE", -200);
    define("USUARIO_CORTO", -202);
    define("PASSWORD_CORTA", -203);
    define("PASSWORD_NO_COINCIDE", -204);
    define("EMAIL_NO_VALIDO", -205);
    define("EMAIL_YA_EXISTE", -206);
    
    
?>