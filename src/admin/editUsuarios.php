<?php
/*
  vista editar usuarios
*/
?>

<div class="container-flex">
    <form action="admin.php" method="post">
    <input type="hidden" name="accion" value="editUsuarios">
    <?php

    //creamos el objeto de clase Usuarios
    $oUsuarios = new Usuarios();

    //si se recibe un id a eliminar se ejecuta el metodo pertinente
    if(isset($_GET["eliminar"]))
    {
        if($oUsuarios->eliminarUsuario($_GET["eliminar"]))
        {
            echo "<div class='msg item ok'>Se eliminó el registro correctamente.</div>";
        }
        else
        {
            echo "<div class='msg item alert'>Ocurrió un error al eliminar el registro.</div>";
        }
    }

    //si se pulsa el boton de actualizar recogemos los datos y actualizamos el usuario
    if(isset($_POST["btnActUsuario"]))
    {
        extract($_POST);
        $iAct = $oUsuarios->actUsuario($idAct, $txtUsuario, $txtPassword, $txtPasswordC, $txtEmail, $slcNivel, $slcEstado);
        if($iAct == USUARIO_ACTUALIZADO)echo "<div class='msg item ok'>Se actualizó el usuario correctamente.</div>";
        elseif($iAct == ERROR_ACTUALIZAR_USUARIO)echo "<div class='msg item alert'>Ocurrió un error al actualizar el usuario.</div>";
        elseif($iAct == USUARIO_YA_EXISTE)echo "<div class='msg item alert'>Ya existe un usuario con ese nombre.</div>";
        elseif($iAct == EMAIL_NO_VALIDO)echo "<div class='msg item alert'>Por favor, introduce un email correcto.</div>";
        elseif($iAct == ERROR_FALTAN_DATOS)echo "<div class='msg item alert'>Por favor, rellena todos los datos.</div>";
        elseif($iAct == PASSWORD_CORTA)echo "<div class='msg item alert'>Por favor, introduce un password con al menos 4 carácteres.</div>";
        elseif($iAct == PASSWORD_NO_COINCIDE)echo "<div class='msg item alert'>LAs contraseñas no coinciden.</div>";
        elseif($iAct == USUARIO_CORTO)echo "<div class='msg item alert'>Por favor, introduce un usuario con al menos 3 carácteres.</div>";
        else echo "<div class='msg item alert'>Error inesperado $iAct.</div>";
    }


    //si recibimos el id a editar, leemos los datos y cargamos el formulario de editar usuario
    if(isset($_GET["editar"]) || isset($_POST["editar"]))
    {
        if(isset($_GET["editar"]))$id = $_GET["editar"];
        else $id = $_POST["editar"];

        $pQuery = $oUsuarios->leerUsuarios($id);
        $row = $pQuery->fetch(PDO::FETCH_ASSOC);

        extract($row);
        ?>
        <input type="hidden" value="<?=$id?>" name="idAct">
        <input type="hidden" value="<?=$id?>" name="editar">

        <h3>Editar usuario [<?=$usuario?>]</h3>
        <div class="item">
            <span class="form-label">
                <label for="txtUsuario">Nombre de usuario</label>
                <input type="text" name="txtUsuario" id="txtUsuario" placeholder="Usuario" pattern='[A-Za-z].{3,}' value="<?=$usuario?>" autocomplete="username">
            </span>
        </div>
        <div class="item">
            <span class="form-label">
                <label for="txtEmail">Email</label>
                <input type="email" name="txtEmail" id="txtEmail" placeholder="Email" value="<?=$email?>" autocomplete="email">
            </span>
        </div>
        <div class="item">
        <span class="form-label">
            <label for="txtPassword">Password</label>
            <input type="password" name="txtPassword" id="txtPassword" placeholder="Password" value="" autocomplete="new-password">
        </div>
        <div class="item">
            <span class="form-label">
                <label for="txtPasswordC">Repite el password</label>
                <input type="password" name="txtPasswordC" id="txtPasswordC" autocomplete="new-password" placeholder="Repite el password" value="">
            </span>
        </div>
        <div class="item">
            <span class="form-label">
                <label for="txtPasswordC">Nivel usuario</label>
                <select name="slcNivel"><option <?=($nivel == 101 ? "selected" : "")?> value="101">usuario</option><option <?=($nivel == 100 ? "selected" : "")?> value="100">admin</option></select>
            </span>
        </div>
        <div class="item">
            <span class="form-label">
                <label for="txtPasswordC">Estado</label>
                <select name="slcEstado"><option <?=($estado == 1 ? "selected" : "")?> value="1">Activo</option><option <?=($estado == 0 ? "selected" : "")?> value="0">inactivo</option></select>
            </span>
        </div>
        <button onclick="return confirm('¿Seguro que deseas actualizar?');" type="submit" name="btnActUsuario" title="Actualizar usuario">Actualizar</button>
        <a class="button alerta" href="admin.php?accion=editUsuarios" title="Volver al listado de usuarios">Atrás</a>
        <?php
    }
    else
    {
        //cargamos el listado de usuarios
        
        $pQuery = $oUsuarios->leerUsuarios();
        $num = $pQuery->rowCount();

        // mostramos los registros si existen
        if($num>0)
        {    
            echo "<table class='item'><thead><tr><th>id</th><th>Usuario</th><th>Email</th><th>Fecha Alta</th><th>Nivel</th><th>Estado</th><th>Acciones</th></tr></thead>";    
            while ($row = $pQuery->fetch(PDO::FETCH_ASSOC))
            {
                extract($row);
                echo "<tr><td>{$id}</td><td>{$usuario}</td><td>{$email}</td><td>{$altaes}</td><td>".($nivel == 100 ? "<span class='ok'>admin</span>" : "usuario")."</td><td>".($estado == 1 ? "<span class='ok'>ACTIVO</span>" : "<span class='alerta'>INACTIVO</span>")."</td>
                <td>
                <a class='button info' href='admin.php?accion=editUsuarios&editar=$id' title='Editar usuario'>Editar</a>
                <a class='button alerta' onclick='return confirm(\"¿Seguro que deseas eliminar?\");' href='admin.php?accion=editUsuarios&eliminar=$id' title='Editar usuario'>Borrar</a>
                </td>
                </tr>";
            }
            echo "</table>";
        }
        else
        {
            echo "<div class='msg item alerta'>No se encontraron usuarios.</div>";
        }
        /* Creación de usuario por parte del administrador */
    }
    ?>
    </form>
</div>