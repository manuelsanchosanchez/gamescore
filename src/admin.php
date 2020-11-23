<?php
/*
  pagina principal de usuario
  donde se irán cargando las 
  diferentes vistas
*/

//cargamos la clase de sesiones
include "usuarios.php";

//recogemos la sesion y los datos de usuario que haya en un nuevo objeto de la clase Sesion
$oSesion = new Sesion();
$oSesion->sesion = $oSesion->getInstancia();
$sesionUsuario = $oSesion->sesion->__get("sesionUsuario");
$datosUsuario = json_decode($sesionUsuario,true);
$nivelUsuario = $datosUsuario["nivel"];

//si no hay datos de usuario o el nivel de usuario no es 100 lo devolvemos a la página principal
if(!$sesionUsuario || $nivelUsuario!=100)
{
    header("Location: ".BASE_URL."index.php?i=".NO_TIENES_ACCESO);    
}

//recogemos la variable pasada por GET para cargar un contenido u otro
if(isset($_GET["accion"]))$accion=$_GET["accion"];
elseif(isset($_POST["accion"]))$accion=$_POST["accion"];
else $accion="";

//con str_replace montamos el subtitulo de la pagina
if($accion)$subtituloPagina=str_replace("edit","",$accion);
else $subtituloPagina = "";

$tituloPagina = "Página Administración $subtituloPagina";

//cargamos el header
include_once("./layout/layoutHeaderAdmin.php");

//cargamos el contenido principal
switch($accion)
{
    case "editJuegos" :include("./admin/editJuegos.php");break;
    case "editPlataformas" :include("./admin/editPlataformas.php");break;
    case "editGeneros" :include("./admin/editGeneros.php");break;
    case "editUsuarios" :include("./admin/editUsuarios.php");break;
    case "Estadisticas" :include("./admin/estadisticasJuegos.php");break;
    default: include("./admin/adminPrincipal.php");
}

//cargamos el footer
include_once("./layout/layoutFooter.php");
?>