<?php
include ('Texto.php');
$Texto = new Texto();

if (isset($_POST['accion'])) { $accion = $_POST['accion']; }

if (isset($_POST['id'])) { $id = $_POST['id']; }
if (isset($_POST['input_ubicacion'])) { $ubicacion = $_POST['input_ubicacion']; }
if (isset($_POST['input_fecha_inicial'])) { $fecha_inicial = $_POST['input_fecha_inicial']; }
if (isset($_POST['input_fecha_final'])) { $fecha_final = $_POST['input_fecha_final']; }
if (isset($_POST['input_texto'])) { $texto = $_POST['input_texto']; }
if (isset($_POST['input_status'])) { $status = $_POST['input_status']; } else { $status = '0'; }
if (isset($_POST['input_tipo'])) {
   $tipo = $_POST['input_tipo'];
   if ($tipo == 2) {
      //Si es tipo publicidad 2=Promoción
      if (isset($_POST['input_hora_inicial'])) { $hora_inicial = $_POST['input_hora_inicial']; }
      if (isset($_POST['input_hora_final'])) { $hora_final = $_POST['input_hora_final']; }
      if (isset($_POST['input_color_texto'])) { $color_texto = $_POST['input_color_texto']; }
      if (isset($_POST['input_color_fondo'])) { $color_fondo = $_POST['input_color_fondo']; }
   } else {
      $hora_inicial = null; $hora_final = null; $color_texto = null; $color_fondo = null;
   }
}
if (isset($_POST['query'])) { $query = $_POST['query']; }
if (isset($_POST['ids'])) { $ids = $_POST['ids']; }



// var_dump($_POST);
// var_dump($_FILES);
// die();

//PETICIONES
if ($accion == 'mostrar_texto') {
   $Texto->mostrarTexto($id);
}

if ($accion == 'crear_texto') {
   $Texto->crearTexto($ubicacion,$fecha_inicial,$fecha_final,$texto,$status,$tipo,$hora_inicial,$hora_final,$color_texto,$color_fondo);
}

if ($accion == 'editar_texto') {
   $Texto->editarTexto($id,$ubicacion,$fecha_inicial,$fecha_final,$texto,$status,$tipo,$hora_inicial,$hora_final,$color_texto,$color_fondo);
}

if ($accion == "eliminar_texto") {
   $Texto->eliminarTexto($id);
}


//FUNCIONES EXTRAS
if ($accion == "actualizar_status") {
   $Texto->actualizarStatus($query,$ids);
}
