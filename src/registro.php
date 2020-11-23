<?php
/*
  página de registro de usuarios
*/

include "usuarios.php";

if (!empty($_POST))
{
    /* en este caso, recogemos los valores con POST en la misma página y mostramos
    los mensajes dependiendo del valor que nos devuelve el método de nuevo usuario */

    $usuario = $_POST['txtUsuario'];
    $email = $_POST['txtEmail'];
    $password = $_POST['txtPassword'];
    $passwordC = $_POST['txtPasswordC'];

    $oUsuario = new Usuarios();
    $infoCreaUsuario = $oUsuario->nuevoUsuario($usuario,  $password,  $passwordC, $email, 101);
        
    //si se ha creado correctamente, redireccionamos a la pagina de login
    if($infoCreaUsuario == USUARIO_CREADO)header("Location: ".BASE_URL."index.php?i=".USUARIO_CREADO);

}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>GAMESCORE - Registro nuevo usuario</title>
    <meta name="description" content="Gamescore">
    <meta name="author" content="Manuel Andrés Sancho Sánchez">
    <link rel="stylesheet" href="../resources/css/styles.css?v=1.0">
</head>
<body>
    <div class="container">
        <div id="form-login" class="container-flex">
            <a href="../index.php" title="Ir a la página principal"><h1>gamescore</h1></a>
            <p>Registro nuevo usuario</p>
            <form class="form-datos" method="POST" action="registro.php" autocomplete="off">
                <div class="item">
                    <span class="form-label">
                        <label for="txtUsuario">Nombre de usuario</label>
                        <input type="text" name="txtUsuario" id="txtUsuario" placeholder="Usuario" pattern='[A-Za-z].{3,}' value="<?=(isset($usuario) ? $usuario : "")?>">
                    </span>
                </div>
                <div class="item">
                    <span class="form-label">
                        <label for="txtEmail">Email</label>
                        <input type="email" name="txtEmail" id="txtEmail" placeholder="Email" value="<?=(isset($email) ? $email : "")?>">
                    </span>
                </div>
                <div class="item">
                    <span class="form-label">
                        <label for="txtPassword">Password</label>
                        <input type="password" name="txtPassword" id="txtPassword" placeholder="Password" value="">
                    </span>
                </div>
                <div class="item">
                    <span class="form-label">
                        <label for="txtPasswordC">Repite el password</label>
                        <input type="password" name="txtPasswordC" id="txtPasswordC" placeholder="Repite el password" value="">
                    </span>
                </div>
                <button type="submit">Registrarse</button>
            </form>
            <?php

            // si se ha intentado crear un usuario mostramos el mensaje correspondiente            
            if(isset($infoCreaUsuario))
            {
                ?>
                <div class="msg info item <?=($infoCreaUsuario < 0 ? "alerta" : "")?>">
                    <?php
                    if($infoCreaUsuario==USUARIO_CORTO)echo "Nombre de usuario debe tener al menos 3 caracteres";
                    elseif($infoCreaUsuario==EMAIL_NO_VALIDO)echo "Introduce un email válido";
                    elseif($infoCreaUsuario==PASSWORD_CORTA)echo "Contraseña debe tener al menos 4 caracteres";
                    elseif($infoCreaUsuario==USUARIO_YA_EXISTE)echo "El nombre de usuario ya existe, elige otro";
                    elseif($infoCreaUsuario==PASSWORD_NO_COINCIDE)echo "Las contraseñas no coinciden";
                    ?>
                </div>
                <?php
            }
            ?>
        </div>
        
    </div>
</body>
</html>