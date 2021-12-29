<?php
//  require_once $_SERVER['DOCUMENT_ROOT'].'/AWS/LaBodega_publicidad/Admin/Configurations/globals.php';
require_once 'Admin/Configurations/globals.php';

if (isset($_COOKIE["bodega_sesion"])) {
    if ($_COOKIE["bodega_sesion"] == "activa") {
        header("location:Admin/");
        die();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | La Bodega</title>
    <link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />

    <!-- JQuery 6 -->
    <script src="<?php echo($DIST_PATH) ?>/jquery-3.6.0/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 5.1.3 -->
    <link rel="stylesheet" href="<?php echo($DIST_PATH) ?>/Bootstrap-5.1.3/css/bootstrap.min.css">
    <!-- AdminLTE-3 -->
    <link rel="stylesheet" href="<?php echo($DIST_PATH) ?>/AdminLTE-3/css/adminlte.min.css">
    <!-- Iconos - font-awesome6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.1.9/sweetalert2.min.css" integrity="sha512-cyIcYOviYhF0bHIhzXWJQ/7xnaBuIIOecYoPZBgJHQKFPo+TOBA+BY1EnTpmM8yKDU4ZdI3UGccNGCEUdfbBqw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <span class="fw-bold h1">PUBLICIDAD</span>
            <img src="favicon.png" alt='La Bodega Logo' class='img-fluid' style="width: auto" />
        </div>
        <!-- /.login-logo -->
        <div class="card rounded-3 card-outline card-success shadow">
            <div class="card-body login-card-body">
                <p class="login-box-msg text-sm fst-italic">Ingresa tus datos para iniciar sesión</p>

                <form id="formulario_login">
                    <input type="hidden" id="accion" value="iniciar_sesion">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control rounded-l" id='usuario' name='usuario' placeholder="Usuario" aria-label="Username" aria-describedby="span-usuario" autofocus="autofocus" />
                        <span class="input-group-text" id="span-usuario"><i class="fa-solid fa-user"></i></span>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" id='contrasenia' name='contrasenia' placeholder="Contraseña" aria-label="Contrasenia" aria-describedby="span-contrasenia"autocomplete="off" />
                        <span class="input-group-text" id="span-contrasenia"><i class="fa-solid fa-key"></i></span>
                    </div>
                    <div class="row">
                        <div class="col">
                            <button type="submit" class="btn btn-outline-success btn-block fw-bold text-center" id="btn_iniciar_sesion">
                                <i class="fa-solid fa-circle-arrow-right"></i>&nbsp;INGRESAR
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->

    <footer class="footer-login mt-5">
        <p><b>AW Software</b> | 2021</p>
    </footer>

    <!-- SCRIPTS -->
    <!-- JQuery 6 -->
    <script src="<?php echo($DIST_PATH) ?>/jquery-3.6.0/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 5.1.3 -->
    <script src="<?php echo($DIST_PATH) ?>/Bootstrap-5.1.3/js/bootstrap.min.js"></script>
    <!-- AdminLTE-3 -->
    <script src="<?php echo($DIST_PATH) ?>/AdminLTE-3/js/adminlte.min.js"></script>

    <!-- Iconos - FontAwesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/js/all.min.js" integrity="sha512-cyAbuGborsD25bhT/uz++wPqrh5cqPh1ULJz4NSpN9ktWcA6Hnh9g+CWKeNx2R0fgQt+ybRXdabSBgYXkQTTmA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.9/dist/sweetalert2.all.min.js"></script>

    <script src="Admin/Scripts/login.js"></script>
</body>
</html>