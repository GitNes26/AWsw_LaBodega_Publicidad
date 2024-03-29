<?php
include ('Video.php');
$Video = new Video();

if (isset($_POST['accion'])) { $accion = $_POST['accion']; }

if (isset($_POST['id'])) { $id = $_POST['id']; }
if (isset($_POST['input_ubicacion'])) { $ubicacion = $_POST['input_ubicacion']; }
if (isset($_POST['input_plantilla'])) { $plantilla = $_POST['input_plantilla']; }
if (isset($_POST['input_fecha_inicial'])) { $fecha_inicial = $_POST['input_fecha_inicial']; }
if (isset($_POST['input_fecha_final'])) { $fecha_final = $_POST['input_fecha_final']; }
if (isset($_FILES['input_archivo'])) {
   $path_archivos_panel = "Assets/Archivos_panel";
   $archivo = $_FILES['input_archivo'];

   $directorio = "../../$path_archivos_panel/$ubicacion/Videos";
   $nombre_archivo = $archivo["name"];
   $destino = "$directorio/$ubicacion-$nombre_archivo";
   $tipo = explode(".",$nombre_archivo);
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
      $ruta = "$path_archivos_panel/$ubicacion/Videos/$ubicacion-$nombre_archivo";
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

if (isset($_POST['orden'])) { $orden = $_POST['orden']; }
if (isset($_POST['id_cliente'])) { $id_cliente = $_POST['id_cliente']; }

// var_dump($_POST);
// var_dump($_FILES);

//PETICIONES
if ($accion == 'mostrar_videos_cliente') {
   $Video->mostrarVideosPorCliente($id_cliente);
}
if ($accion == 'mostrar_video') {
   $Video->mostrarVideo($id);
}

if ($accion == 'crear_video') {
   $Video->crearVideo($ubicacion,$ruta,$tipo,$fecha_inicial,$fecha_final,$status,$plantilla);
}

if ($accion == 'editar_video') {
   if (isset($_POST['asignar_orden'])) { $asignar_orden = $_POST['asignar_orden']; }
   if($id_ubicacion_actual != "") {
      if ($ubicacion != $id_ubicacion_actual) {
         $ruta = moverArchivo($src_archivo,$ubicacion,$ruta);
         $path = explode(".",$ruta);
         $tipo = strtoupper(trim(end($path)));
      }
   }
   $Video->editarVideo($id,$ubicacion,$ruta,$tipo,$fecha_inicial,$fecha_final,$status,$plantilla,$asignar_orden);
}

if ($accion == "eliminar_video") {
   $path_a_eliminar = "../../$src_archivo";

   $Video->eliminarVideo($id,$path_a_eliminar);
}


//FUNCIONES
if ($accion == "eliminar_archivo") {
   $path_a_eliminar = "../../$src_archivo";

   $Video->eliminarArchivoYruta($id,$path_a_eliminar);
}

if ($accion == "actualizar_status") {
   $Video->actualizarStatus($query,$ids);
}

if ($accion == "actualizar_orden") {
   $Video->actualizarOrden($id,$orden);
}
//Esta función actuará si se edita la ubicación del objeto
function moverArchivo($src_archivo,$ubicacion,$ruta) {
   $path_actual = "../../$src_archivo";

   $path_archivos_panel = "Assets/Archivos_panel";
   $nombre_archivo = explode("/", $src_archivo);
   $nombre_archivo = trim(end($nombre_archivo));

   $directorio = "../../$path_archivos_panel/$ubicacion/Videos";
   $destino = "$directorio/$nombre_archivo";
   if (!is_dir($directorio)) {
      @mkdir($directorio,0755,true);
   }

   // var_dump("$path_actual | $destino");
   if ($src_archivo == "" || $src_archivo == null) {
      $nombre_archivo = $_FILES["input_archivo"]["name"];
      $nombre_archivo = "$ubicacion-$nombre_archivo";
      $destino = "$directorio/$nombre_archivo";

      if (file_exists($destino)) {
         $ruta = explode("../",$destino);
         $ruta = trim(end($ruta));
         return $ruta;
      } else {
         if (move_uploaded_file($_FILES["input_archivo"]["tmp_name"],$destino)) {
            $ruta = "$path_archivos_panel/$ubicacion/Videos/$ubicacion-$nombre_archivo";
            var_dump($ruta);
         } else {
            $ruta = "";
            $tipo = "";
            print(error_get_last());
         }
      }
   }
   else {
      // consultar si hay más registros con la misma ruta...
      $Video = new Video();
      $path = explode("../",$path_actual);
      $ruta = trim(end($path));
      // var_dump($ruta);
      $cantidad = (int)$Video->contarRegistrosConLaMismaRuta($ruta);
      // var_dump($cantidad);
      if ($cantidad > 0) {
         @copy($path_actual,$destino);
      } else {
         if (rename($path_actual,$destino)) {
            $ruta = "$path_archivos_panel/$ubicacion/Videos/$nombre_archivo";
         } else {
            print(error_get_last());
         }
      }
   }
   return $ruta;      
}
