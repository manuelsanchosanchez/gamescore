<?php
/*
  pagina principal de usuario
  donde se irán cargando las 
  diferentes vistas
*/

//cargamos la clase de sesiones
include "sesion.php";

//recogemos la sesion y los datos de usuario que haya en un nuevo objeto de la clase Sesion
$oSesion = new Sesion();
$oSesion->sesion = $oSesion->getInstancia();
$sesionUsuario = $oSesion->sesion->__get("sesionUsuario");
$datosUsuario = json_decode($sesionUsuario,true);

//si no hay datos de usuario es porque no se ha logado por lo que devolvemos a la página principal
if(!$sesionUsuario)
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

$tituloPagina = "Página Usuario $subtituloPagina";

//cargamos el header
include_once("./layout/layoutHeader.php");

//cargamos el contenido principal
switch($accion)
{
    case "editFavoritos" :include("./user/editFavoritos.php");break;
    default: include("./user/userPrincipal.php");
}

//cargamos el footer
include_once("./layout/layoutFooter.php");
?>