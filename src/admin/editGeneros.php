<?php
/*
  vista editar generos
*/
?>

<div class="container-flex">
    <form action="admin.php" method="post">
    <input type="hidden" name="accion" value="editGeneros">
    <?php
    
    include_once("juegos.php");

    //creamos el objeto de clase Juegos
    $oJuegos = new Juegos();

    //si se recibe un id a eliminar se ejecuta el metodo pertinente
    if(isset($_GET["eliminar"]))
    {
        if($oJuegos->eliminarGenero($_GET["eliminar"]))
        {
            echo "<div class='msg item ok'>Se eliminó el registro correctamente.</div>";
        }
        else
        {
            echo "<div class='msg item alert'>Ocurrió un error al eliminar el registro.</div>";
        }
    }
    
    //si se pulsa el boton de nuevo recogemos los datos y creamos el registro
    if(isset($_POST["btnNuevoGenero"]))
    {
        $iNuevo = $oJuegos->insertGenero($_POST["txtGenero"]);
        if($iNuevo == REGISTRO_INSERTADO)echo "<div class='msg item ok'>Se insertó el registro correctamente.</div>";
        elseif($iNuevo == ERROR_INSERTA_REGISTRO)echo "<div class='msg item alert'>Ocurrió un error al insertar el registro.</div>";
        elseif($iNuevo == REGISTRO_EXISTE)echo "<div class='msg item alert'>Ya existe un registro con esos datos.</div>";
        else echo "<div class='msg item alert'>Error inesperado $iNuevo.</div>";
    }

    //si se pulsa el boton de actualizar recogemos los datos y actualizamos el registro
    if(isset($_POST["btnActGenero"]))
    {
        $iAct = $oJuegos->actGenero($_POST["idAct"],$_POST["txtGeneroAct"]);
        if($iAct == REGISTRO_ACTUALIZADO)echo "<div class='msg item ok'>Se actualizó el registro correctamente.</div>";
        elseif($iAct == ERROR_ACTUALIZA_REGISTRO)echo "<div class='msg item alert'>Ocurrió un error al actualizar el registro.</div>";
        elseif($iAct == REGISTRO_EXISTE)echo "<div class='msg item alert'>Ya existe un registro con esos datos.</div>";
        else echo "<div class='msg item alert'>Error inesperado $iAct.</div>";
    }

    $pQuery = $oJuegos->leerGeneros();
    $num = $pQuery->rowCount();

    // mostramos los registros si existen
    if($num>0)
    {    
        echo "<table class='item'><thead><tr><th>id</th><th>Genero</th><th>Acciones</th></tr></thead>";    
        while ($row = $pQuery->fetch(PDO::FETCH_ASSOC))
        {
            extract($row);
            //si hemos recibido un id a editar, lo marcamos para modificarlo
            if(isset($_GET["editar"]) && $_GET["editar"] == $id)
            {
                echo "<input type='hidden' value='$id' name='idAct'>";
                echo "<tr class='fAzul'><td>$id</td><td><input value='$genero' type='text' pattern='[A-Za-z].{3,100}' name='txtGeneroAct' placeholder='Mínimo 3 letras'></td>
                <td><button class='info' onclick='return confirm(\"¿Seguro que deseas actualizar?\");' title='Actualizar genero' name='btnActGenero'>Actualizar</button>
                <a class='button alerta' href='admin.php?accion=editGeneros' title='Volver al listado'>Atrás</a></td>
                </tr>";
            }
            else echo "<tr><td>{$id}</td><td>{$genero}</td>
            <td>
            <a class='button info' href='admin.php?accion=editGeneros&editar=$id' title='Editar genero'>Editar</a>
            <a class='button alerta' onclick='return confirm(\"¿Seguro que deseas eliminar?\");' href='admin.php?accion=editGeneros&eliminar=$id' title='Editar genero'>Borrar</a>
            </td>
            </tr>";

            //si se va a editar recogemos los valores del registro en cuestion
            if(isset($_GET["editar"]) && $_GET["editar"] == $id)$valoresEditar=$row;
        }
    }
    else
    {
        echo "<div class='msg item alerta'>No se encontraron géneros.</div>";
        echo "<table class='item'><thead><tr><th>id</th><th>Genero</th><th>Acciones</th></tr></thead>";
    }

    echo "<tr class='fVerde'><td>NUEVA</td><td><input type='text' pattern='[A-Za-z].{3,100}' name='txtGenero' placeholder='Mínimo 3 letras'></td>
    <td><button class='ok w100' onclick='return confirm(\"¿Seguro que deseas crear?\");' title='Añadir genero' name='btnNuevoGenero'>Añadir</button></td>
    </tr>";
    echo "</table>";
    ?>
    </form>
</div>