<?php
/*
  vista editar usuarios
*/
?>
<div class="container-flex">
    <form action="admin.php" method="post">
    <input type="hidden" name="accion" value="editJuegos">
    <?php

    include_once("juegos.php");

    //creamos el objeto de clase Juegos
    $oJuegos = new Juegos();

    //si se recibe un id a eliminar se ejecuta el metodo pertinente
    if(isset($_GET["eliminar"]))
    {
        if($oJuegos->eliminarJuego($_GET["eliminar"]))
        {
            echo "<div class='msg item ok'>Se eliminó el registro correctamente.</div>";
        }
        else
        {
            echo "<div class='msg item alert'>Ocurrió un error al eliminar el registro.</div>";
        }
    }    

    //si se pulsa el boton de nuevo recogemos los datos y creamos el registro
    if(isset($_POST["btnNuevoJuego"]))
    {
        $iNuevo = $oJuegos->insertJuego($_POST["txtJuego"],$_POST["slcPlataforma"], $_POST["slcGenero"], $_POST["slcAnyo"], $_POST["txtNota"]);
        if($iNuevo == REGISTRO_INSERTADO)echo "<div class='msg item ok'>Se insertó el registro correctamente.</div>";
        elseif($iNuevo == ERROR_INSERTA_REGISTRO)echo "<div class='msg item alert'>Ocurrió un error al insertar el registro.</div>";
        elseif($iNuevo == REGISTRO_EXISTE)echo "<div class='msg item alert'>Ya existe un registro con esos datos.</div>";
        elseif($iNuevo == ERROR_FALTAN_DATOS)echo "<div class='msg item alert'>Por favor, rellena todos los datos.</div>";
        else echo "<div class='msg item alert'>Error inesperado $iNuevo.</div>";
    }

    //si se pulsa el boton de actualizar recogemos los datos y actualizamos el registro
    if(isset($_POST["btnActJuego"]))
    {
        $iAct = $oJuegos->actJuego($_POST["idAct"],$_POST["txtJuegoAct"],$_POST["slcPlataformaAct"], $_POST["slcGeneroAct"], $_POST["slcAnyoAct"], $_POST["txtNotaAct"]);
        if($iAct == REGISTRO_ACTUALIZADO)echo "<div class='msg item ok'>Se actualizó el registro correctamente.</div>";
        elseif($iAct == ERROR_ACTUALIZA_REGISTRO)echo "<div class='msg item alert'>Ocurrió un error al actualizar el registro.</div>";
        elseif($iAct == REGISTRO_EXISTE)echo "<div class='msg item alert'>Ya existe un registro con esos datos.</div>";
        elseif($iAct == ERROR_FALTAN_DATOS)echo "<div class='msg item alert'>Por favor, rellena todos los datos.</div>";
        else echo "<div class='msg item alert'>Error inesperado $iAct.</div>";
    }

    $pQuery = $oJuegos->leerJuegos();
    $num = $pQuery->rowCount();

    // mostramos los registros si existen
    if($num>0)
    {    
        echo "<table class='item'><thead><tr><th>id</th><th>Juego</th><th>Plataforma</th><th>Género</th><th>Año</th><th>Nota</th><th style='width:182px'>Acciones</th></tr></thead>";    
        while ($row = $pQuery->fetch(PDO::FETCH_ASSOC))
        {
            extract($row);
            //si hemos recibido un id a editar, lo marcamos para modificarlo
            if(isset($_GET["editar"]) && $_GET["editar"] == $id)
            {
                echo "<input type='hidden' value='$id' name='idAct'>";
                echo "<tr class='fAzul'><td>$id</td><td><input value='$nombre' type='text' pattern='.{3,}' name='txtJuegoAct' placeholder='Nombre del juego'></td>
                <td>
                    <select name='slcPlataformaAct'>";
                    $oPlataformas = new Juegos();
                    $pQueryPlat = $oPlataformas->leerPlataformas();
                    $num = $pQueryPlat->rowCount();
            
                    if($num)
                    {
                        echo "<option value=''>Plataforma...</option>";
                        while ($rowplat = $pQueryPlat->fetch(PDO::FETCH_ASSOC))
                        {
                            extract($rowplat,EXTR_PREFIX_ALL,"plat");
                            echo "<option ";echo $idPlataforma == $plat_id ? "selected" : "";echo" value='{$plat_id}'>{$plat_plataforma}</option>";
                        }
                    }
                    else echo "<option value=''>Sin plataformas...</option>";
                    echo "</select>
                </td>
                <td>
                    <select name='slcGeneroAct'>";
                    $oGeneros = new Juegos();
                    $pQueryGeneros = $oGeneros->leerGeneros();
                    $num = $pQueryGeneros->rowCount();
            
                    if($num)
                    {
                        echo "<option value=''>Género...</option>";
                        while ($rowgenero = $pQueryGeneros->fetch(PDO::FETCH_ASSOC))
                        {
                            extract($rowgenero,EXTR_PREFIX_ALL,"genero");
                            echo "<option ";echo $idGenero == $genero_id ? "selected" : "";echo" value='{$genero_id}'>{$genero_genero}</option>";
                        }
                    }
                    else echo "<option value=''>Sin géneros...</option>";
                    echo "</select>
                </td>
                <td>
                    <select name='slcAnyoAct'><option value=''>Año...</option>";
                    for($i=date("Y");$i>=1980;$i--)
                    {
                        echo "<option ";echo $anyoLanzamiento == $i ? "selected" : "";echo" value='{$i}'>{$i}</option>";
                    }
                    echo "</select>
                </td>
                <td><input type='number' step='0.5' name='txtNotaAct' placeholder='nota' min='0' max='10' value='$nota'></td>
                <td><button class='ok' onclick='return confirm(\"¿Seguro que deseas actualizar?\");'  title='Actualizar juego' name='btnActJuego'>Actualizar</button>
                <a class='button alerta' href='admin.php?accion=editJuegos' title='Volver al listado'>Atrás</a></td></td>
                </tr>";
            }
            else echo "<tr><td>{$id}</td><td>{$nombre}</td><td>{$plataforma}</td><td>{$genero}</td><td>{$anyoLanzamiento}</td><td>{$nota}</td>
            <td>
            <a class='button info' href='admin.php?accion=editJuegos&editar=$id' title='Editar juego'>Editar</a>
            <a class='button alerta' onclick='return confirm(\"¿Seguro que deseas eliminar?\");' href='admin.php?accion=editJuegos&eliminar=$id' title='Editar juego'>Borrar</a>
            </td>
            </tr>";
        }
    }
    else
    {
        echo "<div class='msg item alerta'>No se encontraron juegos.</div>";
        echo "<table class='item'><thead><tr><th>id</th><th>Juego</th><th>Plataforma</th><th>Género</th><th>Año</th><th>Nota</th><th style='width:182px'>Acciones</th></tr></thead>";
    }
    echo "<tr class='fVerde'><td>NUEVA</td><td><input type='text' pattern='.{3,}' name='txtJuego' placeholder='Nombre del juego'></td>
    <td>
        <select name='slcPlataforma'>";
        $oPlataformas = new Juegos();
        $pQuery = $oJuegos->leerPlataformas();
        $num = $pQuery->rowCount();

        if($num)
        {
            echo "<option value=''>Plataforma...</option>";
            while ($row = $pQuery->fetch(PDO::FETCH_ASSOC))
            {
                extract($row);
                echo "<option value='{$id}'>{$plataforma}</option>";
            }
        }
        else echo "<option value=''>Sin plataformas...</option>";
        echo "</select>
    </td>
    <td>
        <select name='slcGenero'>";
        $oPlataformas = new Juegos();
        $pQuery = $oJuegos->leerGeneros();
        $num = $pQuery->rowCount();

        if($num)
        {
            echo "<option value=''>Género...</option>";
            while ($row = $pQuery->fetch(PDO::FETCH_ASSOC))
            {
                extract($row);
                echo "<option value='{$id}'>{$genero}</option>";
            }
        }
        else echo "<option value=''>Sin géneros...</option>";
        echo "</select>
    </td>
    <td>
        <select name='slcAnyo'><option value=''>Año...</option>";
        for($i=date("Y");$i>=1980;$i--)
        {
            echo "<option value='{$i}'>{$i}</option>";
        }
        echo "</select>
    </td>
    <td><input type='number' step='0.5' name='txtNota' placeholder='nota' min='0' max='10'></td>
    <td><button class='ok w100' onclick='return confirm(\"¿Seguro que deseas crear?\");'  title='Añadir juego' name='btnNuevoJuego'>Añadir</button></td>
    </tr>
    </table>";
    ?>
    </form>
</div>