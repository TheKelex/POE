<?php

    session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="../../bootstrap-5.3.7-dist/css/bootstrap.css">
    <link rel="stylesheet" href="./style.css">
</head>

<body class="m-0 p-0">

    <div class="w-100 sticky-top">
        <img src="../../Imagenes/Banner.png" alt="" class="img-fluid" style="width: 100%; max-height: 160px; object-fit: cover;">
    </div>

    <div class="container-fluid min-vh-100 d-flex justify-content-center align-items-center" style="margin-top: -160px; padding-top: 180px;">

        <div class="col-md-4 p-4 rounded-4 shadow" id="fondo">

            <center>
                <h1 style="color: #04BF55; font-weight: bold;">Iniciar Sesion</h1>
                <p style="color: #02db60; font-weight: bold;">Llene los campos y luego de click en iniciar sesion para ingresar</p>
            </center>

            <h3>Login</h3>

            <?php if (isset($_SESSION["conexion"])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $_SESSION["conexion"]; ?>
                    <?php unset($_SESSION["conexion"]); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>


            <?php if (isset($_SESSION["error"])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $_SESSION["error"]; ?>
                    <?php unset($_SESSION["error"]); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>


            <?php if (isset($cerrada)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $cerrada; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!--Inicio de formulario-->
            <form action="login.php" name="formulario" method="post">

                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label d-flex"> <span class="material-symbols-outlined">account_circle</span><b> Usuario</b></label>
                    <input type="text" name="usuario" class="form-control shadow" id="exampleInputEmail1" aria-describedby="emailHelp">
                    <div id="emailHelp" class="form-text">Ingrese su nombre de usuario</div>
                </div>

                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label d-flex"> <span class="material-symbols-outlined"> lock</span><b> Contraseña</b></label>
                    <input type="password" name="contraseña" class="form-control shadow" id="exampleInputPassword1">
                </div>

                <br>

                <div class="row">

                    <div class="col">

                        <a href="../../index.html" class="btn w-100 d-flex justify-content-center gap-2 rounded-pill px-4 py-2" id="boton_1">Volver</a>

                    </div>

                    <div class="col">

                        <input class="btn w-100 d-flex justify-content-center gap-2 rounded-pill px-4 py-2" type="submit" name="login" id="boton" value="Iniciar Sesion"> <br>

                    </div>

                </div>

            </form>
            <!--Fin inicio de formulario-->

        </div>

    </div>

    <script src="../../bootstrap-5.3.7-dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>