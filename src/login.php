<?php
/*
  script que realiza
  el login de usuario
*/

//cargamos la clase de usuarios
include "usuarios.php";

//creamos un objeto de la clase Usuarios
$oUsuarios = new Usuarios();

//revisamos que haya datos
if (!empty($_POST))
{
    $usuario = $_POST['txtUsuario'];
    $password = $_POST['txtPassword'];
    
    //codificamos el password
    $lPassC = sha1(md5("gamescore" . $password));
    
    //intentamos hacer el login
    $infoLogin = $oUsuarios->login($usuario, $lPassC);

    //dependiendo del resultado del login redireccionamos a la secion que toque o mostramos error
    if ($infoLogin == LOGIN_ADMIN)
    {
        header("Location: ".BASE_URL."src/admin.php");
    } 
    elseif ($infoLogin == LOGIN_USUARIO)
    {
        header("Location: ".BASE_URL."src/principal.php");
    }
    else
    {
        header("Location: ".BASE_URL."index.php?i=$infoLogin"); 
    }
}
else
{
    header("Location: ".BASE_URL."index.php?i=".USUARIO_BLANCO);
}
?>