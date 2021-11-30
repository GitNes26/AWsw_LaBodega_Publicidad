<?php
include ('Banvertical.php');
$Banvertical = new Banvertical();

if (isset($_POST['accion'])) { $accion = $_POST['accion']; }

if (isset($_POST['id'])) { $id = $_POST['id']; }
if (isset($_POST['input_ubicacion'])) { $ubicacion = $_POST['input_ubicacion']; }
if (isset($_POST['input_fecha_inicial'])) { $fecha_inicial = $_POST['input_fecha_inicial']; }
if (isset($_POST['input_fecha_final'])) { $fecha_final = $_POST['input_fecha_final']; }
if (isset($_FILES['input_archivo'])) {
   $path_archivos_panel = "Assets/Archivos_panel";
   $archivo = $_FILES['input_archivo'];

   $directorio = "../../$path_archivos_panel/$ubicacion/Banvertical";
   $nombre_archivo = $archivo["name"];
   $destino = "$directorio/$ubicacion-$nombre_archivo";
   $tipo = explode("/",$archivo["type"]);
   $tipo = strtoupper(trim(end($tipo)));

   if (!is_dir($directorio)) {
      @mkdir($directorio,0755,true);
      /**
      * 0755 => PERMISOS CRUD de los arvhicos
      * true => es para hacerlo recursivo,
      *         es decir todos los archivos de la ruta tienen
      *         los mismos permisos.
      */
   }
   if (move_uploaded_file($_FILES["input_archivo"]["tmp_name"],$destino)) {
      $ruta = "$path_archivos_panel/$ubicacion/Banvertical/$ubicacion-$nombre_archivo";
   } else {
      $ruta = "";
      $tipo = "";
      print(error_get_last());
   }
} else {
   $ruta = "";
   $tipo = "";
}
if (isset($_POST['input_status'])) { $status = $_POST['input_status']; } else { $status = "0"; }

if (isset($_POST['src_archivo'])) { $src_archivo = $_POST['src_archivo']; } else { $src_archivo = ""; }
if (isset($_POST['id_ubicacion_actual'])) { $id_ubicacion_actual = $_POST['id_ubicacion_actual']; } else { $id_ubicacion_actual = "";}

if (isset($_POST['query'])) { $query = $_POST['query']; }
if (isset($_POST['ids'])) { $ids = $_POST['ids']; }

// var_dump($_POST);
// var_dump($_FILES);
// die();

//PETICIONES
if ($accion == 'mostrar_banvertical') {
   $Banvertical->mostrarBanvertical($id);
}

if ($accion == 'crear_banvertical') {
   $Banvertical->crearBanvertical($ubicacion,$ruta,$tipo,$fecha_inicial,$fecha_final,$status);
}

if ($accion == 'editar_banvertical') {
   if($id_ubicacion_actual != "") {
      if ($ubicacion != $id_ubicacion_actual) {
         $ruta = moverArchivo($src_archivo,$ubicacion,$ruta);
      }
   }
   $Banvertical->editarBanvertical($id,$ubicacion,$ruta,$tipo,$fecha_inicial,$fecha_final,$status);
}

if ($accion == "eliminar_banvertical") {
   $path_a_eliminar = "../../$src_archivo";

   $Banvertical->eliminarBanvertical($id,$path_a_eliminar);
}


//FUNCIONES
if ($accion == "eliminar_archivo") {
   $path_a_eliminar = "../../$src_archivo";

   $Banvertical->eliminarArchivoYruta($id,$path_a_eliminar);
}

if ($accion == "actualizar_status") {
   $Banvertical->actualizarStatus($query,$ids);
}

//Esta función actuará si se edita la ubicación del objeto
function moverArchivo($src_archivo,$ubicacion,$ruta) {
   $path_actual = "../../$src_archivo";

   $path_archivos_panel = "Assets/Archivos_panel";
   $nombre_archivo = explode("/", $src_archivo);
   $nombre_archivo = trim(end($nombre_archivo));

   $directorio = "../../$path_archivos_panel/$ubicacion/Banvertical";
   $destino = "$directorio/$nombre_archivo";
   if (!is_dir($directorio)) {
      @mkdir($directorio,0755,true);
   }

   if (rename($path_actual,$destino)) {
      $ruta = "$path_archivos_panel/$ubicacion/Banvertical/$nombre_archivo";
   } else {
      print(error_get_last());
   }
   return $ruta;
      
}
