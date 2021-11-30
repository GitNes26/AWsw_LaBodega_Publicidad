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

class Banvertical extends DB_connection
{   
   function mostrarBanvertical($id) {
      try {
         $respuesta = array(
            "Resultado" => 'incorrecto',
            "Icono_alerta" => 'error',
            "Titulo_alerta" => 'Opps...!',
            "Mensaje_alerta" => 'Datos incorrectos.',
         );
         $query = "SELECT c.cli_id,iv.img_id,c.cli_nom_empresa,iv.img_fecha_ini,iv.img_fecha_fin,iv.img_ruta,iv.img_status FROM imagen_vertical as iv INNER JOIN clientes as c ON c.cli_id=iv.cli_id WHERE iv.img_id=$id";
         $consulta = $this->SelectOnlyOne($query);
         if (sizeof($consulta) > 0) {
            $respuesta = array(
               "Resultado" => 'correcto',
               "Icono_alerta" => 'success',
               "Titulo_alerta" => 'EXITO!',
               "Mensaje_alerta" => 'Mostrando imagen vertical.',
               "Datos" => array(
                  "Id" => $consulta["img_id"],
                  "Id_cliente" => $consulta["cli_id"],
                  "Ubicacion" => $consulta["cli_nom_empresa"],
                  "Ruta" => $consulta["img_ruta"],
                  "Fecha_inicial" => $consulta["img_fecha_ini"],
                  "Fecha_final" => $consulta["img_fecha_fin"],
                  "Status" => $consulta["img_status"],
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

   function mostrarBanverticales() {
      try {
         $query = "SELECT c.cli_id,iv.img_id,c.cli_nom_empresa,iv.img_fecha_ini,iv.img_fecha_fin,iv.img_ruta,iv.img_status FROM imagen_vertical as iv INNER JOIN clientes as c ON c.cli_id=iv.cli_id";
         $resultado = $this->MostrarEnHTML($query);
         if (sizeof($resultado) > 0) { return $resultado; }

      } catch (Exception $e) {
         echo "Error: ".$e->getMessage();
      }
   }

   function crearBanvertical($ubicacion,$ruta,$tipo,$fecha_inicial,$fecha_final,$status) {
      try {
         $respuesta = array(
            "Resultado" => 'incorrecto',
            "Icono_alerta" => 'error',
            "Titulo_alerta" => 'Opps...!',
            "Mensaje_alerta" => 'Datos incorrectos.',
         );

         $query = "INSERT INTO imagen_vertical (cli_id,img_ruta,img_tipo,img_fecha_ini,img_fecha_fin,img_status) VALUES (?,?,?,?,?,?)";
         $this->ExecuteQuery($query, array($ubicacion,$ruta,$tipo,$fecha_inicial,$fecha_final,$status));
         $respuesta = array(
            "Resultado" => 'correcto',
            "Icono_alerta" => 'success',
            "Titulo_alerta" => 'EXITO!',
            "Mensaje_alerta" => 'Imagen vertical subida.',
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

   function editarBanvertical($id,$ubicacion,$ruta,$tipo,$fecha_inicial,$fecha_final,$status){
      try {
         $respuesta = array(
            "Resultado" => 'incorrecto',
            "Icono_alerta" => 'error',
            "Titulo_alerta" => 'Opps...!',
            "Mensaje_alerta" => 'Datos incorrectos.',
         );

         if ($ruta == "") { //si no se desea editar/cambiar la imagen
            $query = "UPDATE imagen_vertical SET cli_id=?, img_tipo=?, img_fecha_ini=?, img_fecha_fin=?, img_status=? WHERE img_id=?";
            $this->ExecuteQuery($query,array($ubicacion,$tipo,$fecha_inicial,$fecha_final,$status,$id));
         } else {
            $query = "UPDATE imagen_vertical SET cli_id=?, img_ruta=?, img_tipo=?, img_fecha_ini=?, img_fecha_fin=?, img_status=? WHERE img_id=?";
            $this->ExecuteQuery($query,array($ubicacion,$ruta,$tipo,$fecha_inicial,$fecha_final,$status,$id));
         }

         $respuesta = array(
            "Resultado" => 'correcto',
            "Icono_alerta" => 'success',
            "Titulo_alerta" => 'EXITO!',
            "Mensaje_alerta" => 'Imagen vertical actualizada.',
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

   function eliminarBanvertical($id,$path_a_eliminar) {
      try {
         $respuesta = array(
            "Resultado" => 'incorrecto',
            "Icono_alerta" => 'error',
            "Titulo_alerta" => 'Opps...!',
            "Mensaje_alerta" => 'Datos incorrectos.',
         );

         $query = "DELETE FROM imagen_vertical WHERE img_id=?";
         $this->ExecuteQuery($query,array($id));

         //eliminar el archivo
         @unlink($path_a_eliminar);

         $respuesta = array(
            "Resultado" => 'correcto',
            "Icono_alerta" => 'success',
            "Titulo_alerta" => 'Imagen vertical eliminada!'
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

         $query = "UPDATE imagen_vertical SET img_ruta='', img_tipo='' WHERE img_id=?";
         $this->ExecuteQuery($query,array($id));
         
         //eliminar el archivo
         @unlink($path_a_eliminar);

         $respuesta = array(
            "Resultado" => 'correcto',
            "Icono_alerta" => 'success',
            "Titulo_alerta" => 'Archivo eliminado!'
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
            "Mensaje_alerta" => 'Banners verticales actualizados.',
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
