<?php
/*
  script que realiza
  el logout de usuario
*/

//cargamos la clase de sesiones
include "sesion.php";

//recogemos la sesion en un nuevo objeto de la clase Sesion
$oSesion = new Sesion();
$oSesion->sesion = $oSesion->getInstancia();

// redireccionamos a la pagina de login con el mensaje correspondiente si se ha podido cerrar la sesión
if($oSesion->sesion->destruirSesion())header("Location: ".BASE_URL."index.php?i=".SESION_CERRADA);
else header("Location: ".BASE_URL."index.php?i=".ERROR_CERRAR_SESION);
?>