<?php
/*
  vista estadisticas de juegos
*/

//cargamos las librerias instaladas en composer
require_once '../vendor/autoload.php';

//cargamos la libreria Highchart para la grafica
use Ghunti\HighchartsPHP\Highchart;
use Ghunti\HighchartsPHP\HighchartJsExpr;

include_once("juegos.php");

//creamos un objeto de la clase Juegos que usaremos para obtener los datos
$oJuegos = new Juegos();

$pQuery = $oJuegos->leerJuegos();
$numTotal = $pQuery->rowCount();

// listamos los registros si existen
if($numTotal>0)
{
    //declaramos las variables que vamos a usar
    $plataformas = array();
    $juegos = array();

    //declaramos las variables para la grafica
    $axis=array();
    $seriesnotas=array();
    $seriesjuegos=array();

    //recorremos el listado de juegos
    while ($row = $pQuery->fetch(PDO::FETCH_ASSOC))
    {
        extract($row);
        
        //introducimos los valores de las estadisticas en las variables ya inicializadas
        if(!isset($plataformas[$plataforma]))$plataformas[$plataforma] = 0;
        if(!isset($juegos[$plataforma]))$juegos[$plataforma] = 0;

        $plataformas[$plataforma] += $nota;
        $juegos[$plataforma]++;
    }

    //calculamos la media de notas de cada plataforma
    foreach($plataformas as $p=>$v)
    {
        $plataformas[$p]=round($plataformas[$p]/$juegos[$p],2);
    }

    //asignamos los nombres de las series de la grafica
    $seriesnotas["name"]="Nota Media";    
    $seriesjuegos["name"]="Juegos Totales";

    //rellenamos los valores de las series de la grafica
    foreach($plataformas as $p=>$v)
    {
        $axis[]=$p;
        $seriesnotas["data"][]=$v;
        $seriesjuegos["data"][]=$juegos[$p];
    }

    //creamos la grafica

    $chart = new Highchart();
    $chart->chart->renderTo = "grafica";
    $chart->chart->type = "column";
    $chart->title->text = "Gamescore";
    $chart->subtitle->text = "Estadísticas por Plataforma";

    //valores de los axis x
    $chart->xAxis->categories = $axis;

    $chart->yAxis->min = 0;
    $chart->yAxis->title->text = "Valores";
    $chart->legend->layout = "vertical";
    $chart->legend->backgroundColor = "#FFFFFF";
    $chart->legend->align = "left";
    $chart->legend->verticalAlign = "top";
    $chart->legend->x = 100;
    $chart->legend->y = 70;
    $chart->legend->floating = 1;
    $chart->legend->shadow = 1;

    $chart->tooltip->formatter = new HighchartJsExpr("function() {
        return '' + this.x +': '+ this.y;}");

    $chart->plotOptions->column->pointPadding = 0.2;
    $chart->plotOptions->column->borderWidth = 0;

    //añadimos las dos series, las medias y el total de juegos por plataforma
    $chart->series[] = $seriesnotas;
    $chart->series[] = $seriesjuegos;

    //cargamos los scripts necesarios para la libreria
    $chart->printScripts();

    //contenedor de la grafica
    ?>
    <div id="grafica"></div>
    <script type="text/javascript"><?php echo $chart->render("grafica"); ?></script>
    <?php
}
?>