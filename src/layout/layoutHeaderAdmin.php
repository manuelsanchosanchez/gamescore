<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>GAMESCORE - <?=$tituloPagina?></title>
    <meta name="description" content="Gamescore">
    <meta name="author" content="Manuel Andrés Sancho Sánchez">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../resources/css/styles.css?v=1.0">
</head>
<body>
    <div class="container">
        <header>
            <a href="admin.php" title="Ir a la página principal de administración"><h1>gamescore</h1></a>
            <nav>
                <ul>
                    <li>Bienvenido Admin [<?=$datosUsuario["usuario"]?>]</li>
                    <li><a href="admin.php">Home</a></li>
                    <li><a href="admin.php?accion=editJuegos">Juegos</a></li>
                    <li><a href="admin.php?accion=editPlataformas">Plataformas</a></li>
                    <li><a href="admin.php?accion=editGeneros">Géneros</a></li>
                    <li><a href="admin.php?accion=Estadisticas">Estadísticas</a></li>
                    <li><a href="admin.php?accion=editUsuarios">Usuarios</a></li>
                    <li><a href="logout.php" title="Cerrar sesión">[Cerrar sesión]</a></li>
                </ul>
            </nav>
        </header>
        <main>
            <h1><?=$tituloPagina?></h1>