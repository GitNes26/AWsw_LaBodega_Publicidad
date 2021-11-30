<?php
include ('Reproductor.php');
$Reproductor = new Reproductor();

if (isset($_POST['accion'])) { $accion = $_POST['accion']; }
if (isset($_POST['plantilla'])) { $plantilla = $_POST['plantilla']; }
if (isset($_POST['id_ubicacion'])) { $ubicacion = $_POST['id_ubicacion']; }
if (isset($_POST['fecha_inicial'])) { $fecha_inicial = $_POST['fecha_inicial']; }
if (isset($_POST['fecha_final'])) { $fecha_final = $_POST['fecha_final']; }

// var_dump($_POST);
// var_dump($_FILES);
// die();

//PETICIONES
if ($accion == 'lista_videos') {
   $Reproductor->videosParaReproducir($ubicacion,$plantilla,$fecha_inicial,$fecha_final);
}

if ($accion == 'lista_banverticales') {
   $Reproductor->banverticalesParaReproducir($ubicacion,$fecha_inicial,$fecha_final);
}

if ($accion == 'lista_banhorizontales') {
   $Reproductor->banhorizontalesParaReproducir($ubicacion,$fecha_inicial,$fecha_final);
}

if ($accion == 'lista_bancompletos') {
   $Reproductor->bancompletosParaReproducir($ubicacion,$fecha_inicial,$fecha_final);
}

if ($accion == "lista_textos") {
   $Reproductor->textosParaReproducir($ubicacion,$fecha_inicial,$fecha_final);
}


//FUNCIONES
