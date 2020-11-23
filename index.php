<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>GAMESCORE</title>
    <meta name="description" content="Gamescore">
    <meta name="author" content="Manuel Andrés Sancho Sánchez">
    <link rel="stylesheet" href="./resources/css/styles.css?v=1.0">
</head>
<body>
    <div class="container">
        <div id="form-login" class="container-flex">
            <a href="index.php" title="Ir a la página principal"><h1>gamescore</h1></a>
            <p>Login</p>
            <form method="POST" class="form-datos" action="./src/login.php">
                <div class="item">
                    <span class="form-label">
                        <label for="txtUsuario">Usuario</label>
                        <input type="text" name="txtUsuario" id="txtUsuario" placeholder="Usuario">
                    </span>
                </div>
                <div class="item">
                    <span class="form-label">
                        <label for="txtPassword">Password</label>
                        <input type="password" name="txtPassword" id="txtPassword" placeholder="Password">
                    </span>
                </div>
                <button type="submit">Entrar</button>
            </form>
            <div id="msgInfo" class="msg item">
            </div>
            <a href="./src/registro.php" title="Registrar nuevo usuario">¿No estás registrado? ¡Regístrate!</a>
        </div>
        
    </div>
    <script>
    /* mediante javascript podemos recoger los valores que pasamos en la url
    para mostrar el mensaje correspondiente en pantalla */

    const queryString = window.location.search;
    if (queryString != "") 
    {
        document.getElementById("msgInfo").style.display = "block";
        // mediante queryString recogemos la query de la url, le quitamos los caracteres sobrantes con replace y la procesamos con parseInt para que nos devuelva un numero entero
        const info = parseInt(queryString.replace("?i=", ""));

        // si el valor es negativo añadimos la clase de alerta para mostrar que es un error
        if(info < 0)document.getElementById("msgInfo").classList.add("alerta");
        
        //seleccionamos el mensaje concreto
        
        if (info == 200)document.getElementById("msgInfo").innerHTML="Usuario creado. Ya puedes hacer login.";
        else if (info == -101) document.getElementById("msgInfo").innerHTML="Introduce usuario y contraseña";
        else if (info == -100) document.getElementById("msgInfo").innerHTML="Usuario o contraseña incorrecto...";
        else if (info == 110) document.getElementById("msgInfo").innerHTML="Sesión cerrada correctamente";
        else if (info == -110) document.getElementById("msgInfo").innerHTML="Ocurrió un error al cerrar la sesión";
        else if (info == -111) document.getElementById("msgInfo").innerHTML="No tienes permisos para acceder...";
        else document.getElementById("msgInfo").innerHTML="Ha ocurrido un error: ".info;
    }
    </script>
</body>
</html>