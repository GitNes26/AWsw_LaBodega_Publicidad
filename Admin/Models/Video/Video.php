<?php
if (file_exists("../Models/DB_connection.php")) {
   require_once("../Models/DB_connection.php");
} else {
   if (file_exists("./Models/DB_connection.php")) {
      require_once("./Models/DB_connection.php");
   } else if (file_exists("../Models/DB_connection.php")) {
      require_once("../Models/DB_connection.php");
   } else if (file_exists("../../Models/DB_connection.php")) {
      require_once("../../Models/DB_connection.php");
   }
}

class Video extends DB_connection
{   
   function mostrarVideo($id) {
      try {
         $respuesta = array(
            "Resultado" => 'incorrecto',
            "Icono_alerta" => 'error',
            "Titulo_alerta" => 'Opps...!',
            "Mensaje_alerta" => 'Datos incorrectos.',
         );
         $query = "SELECT c.cli_id,v.vid_id,c.cli_nom_empresa,v.vid_fecha_ini,v.vid_fecha_fin,v.vid_plantilla,v.vid_ruta,v.vid_status FROM video as v INNER JOIN clientes as c ON c.cli_id=v.cli_id WHERE v.vid_id=$id";
         $consulta = $this->SelectOnlyOne($query);
         if (sizeof($consulta) > 0) {
            $respuesta = array(
               "Resultado" => 'correcto',
               "Icono_alerta" => 'success',
               "Titulo_alerta" => 'EXITO!',
               "Mensaje_alerta" => 'Mostrando video.',
               "Datos" => array(
                  "Id" => $consulta["vid_id"],
                  "Id_cliente" => $consulta["cli_id"],
                  "Ubicacion" => $consulta["cli_nom_empresa"],
                  "Ruta" => $consulta["vid_ruta"],
                  "Fecha_inicial" => $consulta["vid_fecha_ini"],
                  "Fecha_final" => $consulta["vid_fecha_fin"],
                  "Status" => $consulta["vid_status"],
                  "Plantilla" => $consulta["vid_plantilla"],
               )
            );
         }
         
      } catch (Exception $e) {
         echo "Error: ".$e->getMessage();
         $respuesta = array(
            "Resultado" => 'error',
            "Icono_alerta" => 'error',
            "Titulo_alerta" => 'Opps...!',
            "Mensaje_alerta" => 'Ha ocurrido un erro, verifica tus datos.',
         );
      }
      die(json_encode($respuesta));
   }

   function mostrarVideos() {
      try {
         $query = "SELECT c.cli_id,v.vid_id,c.cli_nom_empresa,v.vid_fecha_ini,v.vid_fecha_fin,v.vid_plantilla,v.vid_ruta,v.vid_status FROM video as v INNER JOIN clientes as c ON c.cli_id=v.cli_id ORDER BY v.vid_id DESC";
         $resultado = $this->MostrarEnHTML($query);
         if (sizeof($resultado) > 0) { return $resultado; }

      } catch (Exception $e) {
         echo "Error: ".$e->getMessage();
      }
   }

   function crearVideo($ubicacion,$ruta,$tipo,$fecha_inicial,$fecha_final,$status,$plantilla) {
      try {
         $respuesta = array(
            "Resultado" => 'incorrecto',
            "Icono_alerta" => 'error',
            "Titulo_alerta" => 'Opps...!',
            "Mensaje_alerta" => 'Datos incorrectos.',
         );

         $query = "INSERT INTO video (cli_id,vid_ruta,vid_tipo,vid_fecha_ini,vid_fecha_fin,vid_status,vid_plantilla) VALUES (?,?,?,?,?,?,?)";
         $this->ExecuteQuery($query, array($ubicacion,$ruta,$tipo,$fecha_inicial,$fecha_final,$status,$plantilla));
         $respuesta = array(
            "Resultado" => 'correcto',
            "Icono_alerta" => 'success',
            "Titulo_alerta" => 'EXITO!',
            "Mensaje_alerta" => 'Video subido.',
         );

      } catch (Exception $e) {
         echo "Error: ".$e->getMessage();
         $respuesta = array(
            "Resultado" => 'error',
            "Icono_alerta" => 'error',
            "Titulo_alerta" => 'Opps...!',
            "Mensaje_alerta" => 'Ha ocurrido un erro, verifica tus datos.',
         );
      }
      die(json_encode($respuesta));
      
   }

   function editarVideo($id,$ubicacion,$ruta,$tipo,$fecha_inicial,$fecha_final,$status,$plantilla){
      try {
         $respuesta = array(
            "Resultado" => 'incorrecto',
            "Icono_alerta" => 'error',
            "Titulo_alerta" => 'Opps...!',
            "Mensaje_alerta" => 'Datos incorrectos.',
         );

         if ($ruta == "") { //si no se desea editar/cambiar el video
            $query = "UPDATE video SET cli_id=?, vid_tipo=?, vid_fecha_ini=?, vid_fecha_fin=?, vid_status=?, vid_plantilla=? WHERE vid_id=?";
            $this->ExecuteQuery($query,array($ubicacion,$tipo,$fecha_inicial,$fecha_final,$status,$plantilla,$id));
         } else {
            $query = "UPDATE video SET cli_id=?, vid_ruta=?, vid_tipo=?, vid_fecha_ini=?, vid_fecha_fin=?, vid_status=?, vid_plantilla=? WHERE vid_id=?";
            $this->ExecuteQuery($query,array($ubicacion,$ruta,$tipo,$fecha_inicial,$fecha_final,$status,$plantilla,$id));
         }

         $respuesta = array(
            "Resultado" => 'correcto',
            "Icono_alerta" => 'success',
            "Titulo_alerta" => 'EXITO!',
            "Mensaje_alerta" => 'Video actualizado.',
         );
      } catch (Exception $e) {
         echo "Error: ".$e->getMessage();
         $respuesta = array(
            "Resultado" => 'error',
            "Icono_alerta" => 'error',
            "Titulo_alerta" => 'Opps...!',
            "Mensaje_alerta" => 'Ha ocurrido un erro, verifica tus datos.',
         );
      }
      die(json_encode($respuesta));
   }

   function eliminarVideo($id,$path_a_eliminar) {
      try {
         $respuesta = array(
            "Resultado" => 'incorrecto',
            "Icono_alerta" => 'error',
            "Titulo_alerta" => 'Opps...!',
            "Mensaje_alerta" => 'Datos incorrectos.',
         );

         $query = "DELETE FROM video WHERE vid_id=?";
         $this->ExecuteQuery($query,array($id));

         //eliminar el archivo
         @unlink($path_a_eliminar);
         // if (@unlink($path_a_eliminar)) {
         //    print("\nObjeto Video eliminado exitosamente: ".$path_a_eliminar);
         // } else {
         //    print("\Error al eliminar el objeto video: ".error_get_last());
         // }

         $respuesta = array(
            "Resultado" => 'correcto',
            "Icono_alerta" => 'success',
            "Titulo_alerta" => 'Video eliminado!',
         );
      } catch (Exception $e) {
         echo "Error: ".$e->getMessage();
         $respuesta = array(
            "Resultado" => 'error',
            "Icono_alerta" => 'error',
            "Titulo_alerta" => 'Opps...!',
            "Mensaje_alerta" => 'Ha ocurrido un error, verifica tus datos.',
         );
      }
      die(json_encode($respuesta));
   }

   function eliminarArchivoYruta($id,$path_a_eliminar) {
      try {
         $respuesta = array(
            "Resultado" => 'incorrecto',
            "Icono_alerta" => 'error',
            "Titulo_alerta" => 'Opps...!',
            "Mensaje_alerta" => 'Datos incorrectos.',
         );

         $query = "UPDATE video SET vid_ruta='', vid_tipo='' WHERE vid_id=?";
         $this->ExecuteQuery($query,array($id));
         
         //eliminar el archivo
         @unlink($path_a_eliminar);

         $respuesta = array(
            "Resultado" => 'correcto',
            "Icono_alerta" => 'success',
            "Titulo_alerta" => 'Archivo eliminado!',
         );
      } catch (Exception $e) {
         echo "Error: ".$e->getMessage();
         $respuesta = array(
            "Resultado" => 'error',
            "Icono_alerta" => 'error',
            "Titulo_alerta" => 'Opps...!',
            "Mensaje_alerta" => 'Ha ocurrido un error, verifica tus datos.',
         );
      }
      die(json_encode($respuesta));
   }


   //FUNCIONES EXTRAS
   function actualizarStatus($query,$ids) {
      try {
         $respuesta = array(
            "Resultado" => 'incorrecto',
            "Icono_alerta" => 'error',
            "Titulo_alerta" => 'Opps...!',
            "Mensaje_alerta" => 'Datos incorrectos.',
         );

         $this->ExecuteQuery($query,null);

         $respuesta = array(
            "Resultado" => 'correcto',
            "Icono_alerta" => 'success',
            "Titulo_alerta" => 'EXITO!',
            "Mensaje_alerta" => 'Videos actualizados.',
            "Datos" => "$ids",
         );
      } catch (Exception $e) {
         echo "Error: ".$e->getMessage();
         $respuesta = array(
            "Resultado" => 'error',
            "Icono_alerta" => 'error',
            "Titulo_alerta" => 'Opps...!',
            "Mensaje_alerta" => 'Ha ocurrido un erro, verifica tus datos.',
         );
      }
      die(json_encode($respuesta));
   }
}
