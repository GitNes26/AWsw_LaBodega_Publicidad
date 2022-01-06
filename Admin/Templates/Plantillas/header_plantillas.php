<?php
// if (isset($_COOKIE["bodega_sesion"])) {
//    if ($_COOKIE["bodega_sesion"] != "activa") {
//        header("location:index.php");
//        die();
//    }
// } else {
//    header("location:index.php");
//    die();
// }

?>
<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Reproductor | La Bodega</title>
   <link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />

   <!-- Google Font: Source Sans Pro -->
   <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"> -->
   <!-- JQuery 6 -->
   <script src="Admin/Assets/Dist/jquery-3.6.0/jquery-3.6.0.min.js"></script>
   <!-- Bootstrap 5.1.3 -->
   <link rel="stylesheet" href="Admin/Assets/Dist/bootstrap-5.1.3/css/bootstrap.min.css">
   <!-- Iconos - font-awesome6 -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
   <!-- SweetAlert2 -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.1.9/sweetalert2.min.css" integrity="sha512-cyIcYOviYhF0bHIhzXWJQ/7xnaBuIIOecYoPZBgJHQKFPo+TOBA+BY1EnTpmM8yKDU4ZdI3UGccNGCEUdfbBqw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
   
   <!-- Moment JS -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

   <!-- Mis Estilos -->
   <link rel="stylesheet" type="text/css" href="Admin/Assets/Dist/Css/estilosPlantillas.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed text-sm">
   <!-- Site wrapper -->
   <div class="wrapper">