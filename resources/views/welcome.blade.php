<!doctype html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Control escolar KD Beauty" />
        <meta name="author" content="Emanuel Cueva" />
        <title>Control Escolar</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            *{
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            
            body { 
                font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
                background-image: url('imagen/bg.png');
                background-attachment: fixed;
                background-size: cover;
                background-position: center;
                background-size: 115%;
            }

            .logo1 {
                width: 200px;
                margin: auto;
                display: block;
            }

            .form-login {
                width: 400px;
                height: 360px;
                border-radius: 20px;
                background: rgb(16, 158, 194);
                opacity: 0.9;
                /*Este sirve para hacer trasparente el form*/
                margin: auto;
                margin-top: 50px;
                box-shadow: 10px 13px 77px rgb(14, 12, 12);
                padding: 20px 30px;
                border-top: 4px solid #087ba8;
                color: rgb(255, 255, 255);
            }

            .form-login h5 {
                margin: 0;
                text-align: center;
                height: 40px;
                margin-bottom: 30px;
                border-bottom: 1px solid;
                font-size: 20px;
            }
            
            .controls {
                width: 100%;
                border: 1px solid #1ba9e0;
                margin-bottom: 15px;
                padding: 11px 10px;
                background: #c5c3c3;
                font-size: 14px;
                font-weight: bold;
            }

            .buttons {
                width: 100%;
                border-radius: 5px;
                /*Este sirve para redondear las esquinas*/
                height: 45px;
                background: #087ba8;
                border: none;
                font-size: 15px;
                font-weight: bold;
                color: white;
                margin-bottom: 16px;
            }

            .buttons:hover {
                /*hover es para declarar las caracteristicas
                del elemento al colocar el puntero sobre de el*/
                width: 100%;
                border-radius: 5px;
                height: 44px;
                background: #1b6886;
                border: none;
                font-size: 15px;
                font-weight: bold;
                color: rgba(255, 255, 255, 0.212);
                margin-bottom: 17px;
            }

            .form-login p {
                height: 40px;
                text-align: center;
                border-bottom: 1px;
            }
            
            .form-login a {
                color: white;
                text-decoration: none;
                font-size: 14px;
            }

            .form-login a:hover {
                text-decoration: underline;
            }
        </style>
    </head>

    <div>
        <!--Se agrega div para poder centrar el logo-->
        <br></br>
        <img class="logo1" src="imagen/logo_kd.png" alt="">
    </div>
    <body>
    <body>
        <section class="form-login">
            <h5>Iniciar Sesión</h5>
            <input class="controls" type="text" id="usuario" placeholder="Usuario">
            <input class="controls" type="password" id="contrasena" placeholder="Contraseña" onkeypress="pulsarenter(event)">
            <input class="buttons" type="submit" id="ingresarbt" value="Ingresar" onclick="buscarusua()">

            <p><a href="#"> ¿Olvidaste tu Contraseña? </a> </p>
        </section>
    </body>
</html>
