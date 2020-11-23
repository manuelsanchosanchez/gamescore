<?php
/*
  script que devuelve por texto
  un listado de usuarios
*/

include_once("bd.php");
include_once("juegos.php");

//creamos el objeto de la clase Juegos
$oJuegos = new Juegos();

//inicializamos variables
$plataforma = $genero = $anyo = $juego = $usuario = null;

if(isset($_GET))
{
    //con extract convertimos en variables por separado cada variable de GET
    extract($_GET);

    //si se listan favoritos se añade la columna
    if($accion == "favs")$thusuario = "<th>Fav</th>";
    else $thusuario = "";

    //mediante la clase Juegos y el metodo leerJuegos obtenemos el listado
    $pQuery = $oJuegos->leerJuegos($juego, $plataforma, $genero, $anyo, $usuario);
    $num = $pQuery->rowCount();

    // mostramos los registros si existen
    if($num>0)
    {
        echo "<table class='item'><thead><tr><th>id</th><th>Juego</th><th>Plataforma</th><th>Género</th><th>Año</th><th>Nota</th>$thusuario</tr></thead>";    
        while ($row = $pQuery->fetch(PDO::FETCH_ASSOC))
        {
            extract($row);
            echo "<tr><td>{$id}</td><td>{$nombre}</td><td>{$plataforma}</td><td>{$genero}</td><td>{$anyoLanzamiento}</td><td>{$nota}</td>";
            //si se listan favoritos se añaden los botones de añadir/quitar favorito
            if($accion == "favs")
            {
                if($usuario)echo "<td><a onclick='return confirm(\"¿Seguro que deseas quitar el juego como favorito?\");' class='fav' href='principal.php?fav=$id' title='Desmarcar como favorito'>&#9733;</a> $notaUsuario</td>";
                else echo "<td><a onclick='return confirm(\"¿Seguro que deseas agregar el juego como favorito?\");' class='unfav' href='principal.php?fav=$id' title='Marcar como favorito'>&#9733;</a></td>";
            }
            echo "</tr>";
        }

        echo "</table>";
    }
    else
    {
        echo "<div class='msg item alerta'>No se encontraron juegos.</div>";
    }
}
else
{
    echo "<div class='msg item alerta'>Ocurrió un error al listar los juegos.</div>";
}