<?php
/*
  vista principal del administrador
*/

include_once("juegos.php");

//creamos el objeto de la clase Juegos
$oJuegos = new Juegos();

?>
<div class="container-flex">
    <p>Elige una de las opciones del menú superior o elige los filtros que desees y pulsa en buscar</p>
    <form class="form-datos" method="POST" action="admin.php">
        <div class="item">
            <span class="form-label">
            <select name="slcAnyoLanzamiento" id="slcAnyoLanzamiento">
                <option value="">Cualquier año</option>
                <?php
                //con un for rellenamos el select con los años
                for($i=date("Y");$i>=1980;$i--)
                {
                    echo "<option value='{$i}'>{$i}</option>";
                }
                ?>
            </select>
            <label for="anyoJuego">Año del juego</label>
            </span>
        </div>
        <div class="item">
            <span class="form-label">
            <select name="slcPlataforma" id="slcPlataforma">
                <option value="">Cualquier plataforma</option>
                <?php
                //rellenamos el select de plataformas
                $pQuery = $oJuegos->leerPlataformas();
                $num = $pQuery->rowCount();
            
                // mostramos los registros si existen
                if($num>0)
                {
                    while ($row = $pQuery->fetch(PDO::FETCH_ASSOC))
                    {
                        extract($row);
                        echo "<option value='$id'>$plataforma</option>";
                    }
                }
                ?>
            </select>
            <label for="plataformaJuego">Plataforma</label>
            </span>
        </div>
        <div class="item">
            <span class="form-label">
            <select name="slcGenero" id="slcGenero">
                <option value="">Cualquier género</option>
                <?php
                //rellenamos el select de generos
                $pQuery = $oJuegos->leerGeneros();
                $num = $pQuery->rowCount();

                // mostramos los registros si existen
                if($num>0)
                {
                    while ($row = $pQuery->fetch(PDO::FETCH_ASSOC))
                    {
                        extract($row);
                        echo "<option value='$id'>$genero</option>";
                    }
                }
                ?>
            </select>
            <label for="generoJuego">Género</label>
            </span>
        </div>
        <div class="item">
            <span class="form-label">
                <input type="text" name="txtJuego" id="txtJuego">
                <label for="nombreJuego">Nombre del juego</label>    
            </span>
        </div>
        <div class="item">
            <a class="button info" title="buscar" onclick="listaJuegos();">Buscar</a>
        </div>
    </form>
    <div id="resultados" class="item">
    </div>
</div>
<script>
//funcion que recoge los datos de juegos de forma asíncrona mediante AJAX
function listaJuegos()
{
    //recogemos los valores seleccionados del formulario
    anyo = document.getElementById("slcAnyoLanzamiento").value;
    plataforma = document.getElementById("slcPlataforma").value;
    genero = document.getElementById("slcGenero").value;
    juego = document.getElementById("txtJuego").value;

    //montamos la query que pasaremos por GET con la variable str
    str="";

    if(anyo)str="&anyo="+anyo;
    if(plataforma)str+="&plataforma="+plataforma;
    if(genero)str+="&genero="+genero;
    if(juego)str+="&juego="+juego;

    //mediante una variable tipo XMLHttpRequest solicitamos al script que nos de respuesta
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("resultados").innerHTML = this.responseText;
        }
        else document.getElementById("resultados").innerHTML = "CARGANDO";
    };
    xmlhttp.open("GET","listarJuegos.php?accion=listar"+str,true);
    xmlhttp.send();
}
</script>