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
            <a href="principal.php" title="Ir a la página principal de administración"><h1>gamescore</h1></a>
            <nav>
                <ul>
                    <li>Bienvenido [<?=$datosUsuario["usuario"]?>]</li>
                    <li><a href="principal.php">Home</a></li>
                    <li><a href="logout.php" title="Cerrar sesión">[Cerrar sesión]</a></li>
                </ul>
            </nav>
        </header>
        <main>
            <h1><?=$tituloPagina?></h1>