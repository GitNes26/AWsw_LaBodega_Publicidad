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

class Banhorizontal extends DB_connection
{   
   function mostrarBanhorizontal($id) {
      try {
         $respuesta = array(
            "Resultado" => 'incorrecto',
            "Icono_alerta" => 'error',
            "Titulo_alerta" => 'Opps...!',
            "Mensaje_alerta" => 'Datos incorrectos.',
         );
         $query = "SELECT c.cli_id,ih.imgh_id,c.cli_nom_empresa,ih.imgh_fecha_ini,ih.imgh_fecha_fin,ih.imgh_ruta,ih.imgh_status FROM imagen_horizontal as ih INNER JOIN clientes as c ON c.cli_id=ih.cli_id WHERE ih.imgh_id=$id";
         $consulta = $this->SelectOnlyOne($query);
         if (sizeof($consulta) > 0) {
            $respuesta = array(
               "Resultado" => 'correcto',
               "Icono_alerta" => 'success',
               "Titulo_alerta" => 'EXITO!',
               "Mensaje_alerta" => 'Mostrando imagen horizontal.',
               "Datos" => array(
                  "Id" => $consulta["imgh_id"],
                  "Id_cliente" => $consulta["cli_id"],
                  "Ubicacion" => $consulta["cli_nom_empresa"],
                  "Ruta" => $consulta["imgh_ruta"],
                  "Fecha_inicial" => $consulta["imgh_fecha_ini"],
                  "Fecha_final" => $consulta["imgh_fecha_fin"],
                  "Status" => $consulta["imgh_status"],
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

   function mostrarBanhorizontales() {
      try {
         $query = "SELECT c.cli_id,ih.imgh_id,c.cli_nom_empresa,ih.imgh_fecha_ini,ih.imgh_fecha_fin,ih.imgh_ruta,ih.imgh_status,ih.imgh_order FROM imagen_horizontal as ih INNER JOIN clientes as c ON c.cli_id=ih.cli_id ORDER BY ih.imgh_order ASC";
         $resultado = $this->MostrarEnHTML($query);
         if (sizeof($resultado) > 0) { return $resultado; }

      } catch (Exception $e) {
         echo "Error: ".$e->getMessage();
      }
   }
   function mostrarBanhorizontalesPorCliente($id_cliente) {
      try {
         $query = "SELECT c.cli_id,ih.imgh_id,c.cli_nom_empresa,ih.imgh_fecha_ini,ih.imgh_fecha_fin,ih.imgh_ruta,ih.imgh_status,ih.imgh_order FROM imagen_horizontal as ih INNER JOIN clientes as c ON c.cli_id=ih.cli_id WHERE ih.cli_id=$id_cliente ORDER BY ih.imgh_order ASC";
         $resultado = $this->SelectAll($query);
         if (sizeof($resultado) > 0) { 
            $respuesta = array(
            "Resultado" => "correcto",
            "Mensaje_alerta" => "Datos encontrados.",
            "Datos" => $resultado
            );
         } else {
            $respuesta = array(
               "Resultado" => "correcto",
               "Mensaje_alerta" => "Sin resultados.",
               "Datos" => array(),
               );
         }
         die (json_encode($respuesta));

      } catch (Exception $e) {
         echo "Error: ".$e->getMessage();
      }
   }

   function crearBanhorizontal($ubicacion,$ruta,$tipo,$fecha_inicial,$fecha_final,$status) {
      try {
         $respuesta = array(
            "Resultado" => 'incorrecto',
            "Icono_alerta" => 'error',
            "Titulo_alerta" => 'Opps...!',
            "Mensaje_alerta" => 'Datos incorrectos.',
         );
         $orden = $this->contarRegistrosActivosPorCliente($ubicacion)+1;

         $query = "INSERT INTO imagen_horizontal (cli_id,imgh_ruta,imgh_tipo,imgh_order,imgh_fecha_ini,imgh_fecha_fin,imgh_status) VALUES (?,?,?,?,?,?,?)";
         $this->ExecuteQuery($query, array($ubicacion,$ruta,$tipo,$orden,$fecha_inicial,$fecha_final,$status));
         $respuesta = array(
            "Resultado" => 'correcto',
            "Icono_alerta" => 'success',
            "Titulo_alerta" => 'EXITO!',
            "Mensaje_alerta" => 'Imagen horizontal subida.',
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

   function editarBanhorizontal($id,$ubicacion,$ruta,$tipo,$fecha_inicial,$fecha_final,$status,$asignar_orden){
      try {
         $respuesta = array(
            "Resultado" => 'incorrecto',
            "Icono_alerta" => 'error',
            "Titulo_alerta" => 'Opps...!',
            "Mensaje_alerta" => 'Datos incorrectos.',
         );
         // var_dump($asignar_orden);
         if ($asignar_orden > 0) {
            if ($asignar_orden == 1)
               $orden = $this->contarRegistrosActivosPorCliente($ubicacion)+1; 
            else
               $orden = $asignar_orden;

            if ($ruta == "") { //si no se desea editar/cambiar la imagen
               $query = "UPDATE imagen_horizontal SET cli_id=?, imgh_order=?, imgh_fecha_ini=?, imgh_fecha_fin=?, imgh_status=? WHERE imgh_id=?";
               $this->ExecuteQuery($query,array($ubicacion,$orden,$fecha_inicial,$fecha_final,$status,$id));
            } else {
               $query = "UPDATE imagen_horizontal SET cli_id=?, imgh_ruta=?, imgh_tipo=?, imgh_order=?, imgh_fecha_ini=?, imgh_fecha_fin=?, imgh_status=? WHERE imgh_id=?";
               $this->ExecuteQuery($query,array($ubicacion,$ruta,$tipo,$orden,$fecha_inicial,$fecha_final,$status,$id));
               }
         } else {
            if ($ruta == "") { //si no se desea editar/cambiar la imagen
               $query = "UPDATE imagen_horizontal SET cli_id=?, imgh_fecha_ini=?, imgh_fecha_fin=?, imgh_status=? WHERE imgh_id=?";
               $this->ExecuteQuery($query,array($ubicacion,$fecha_inicial,$fecha_final,$status,$id));
            } else {
               $query = "UPDATE imagen_horizontal SET cli_id=?, imgh_ruta=?, imgh_tipo=?, imgh_fecha_ini=?, imgh_fecha_fin=?, imgh_status=? WHERE imgh_id=?";
               $this->ExecuteQuery($query,array($ubicacion,$ruta,$tipo,$fecha_inicial,$fecha_final,$status,$id));
            }
         }

         $respuesta = array(
            "Resultado" => 'correcto',
            "Icono_alerta" => 'success',
            "Titulo_alerta" => 'EXITO!',
            "Mensaje_alerta" => 'Imagen horizontal actualizada.',
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

   function eliminarBanhorizontal($id,$path_a_eliminar) {
      try {
         $respuesta = array(
            "Resultado" => 'incorrecto',
            "Icono_alerta" => 'error',
            "Titulo_alerta" => 'Opps...!',
            "Mensaje_alerta" => 'Datos incorrectos.',
         );

         $query = "DELETE FROM imagen_horizontal WHERE imgh_id=?";
         $this->ExecuteQueryContinuous($query,array($id));

         //eliminar el archivo | eliminara el archivo si no hay otro registro con la misma ruta
         $this->eliminarArchivo($path_a_eliminar);

         $respuesta = array(
            "Resultado" => 'correcto',
            "Icono_alerta" => 'success',
            "Titulo_alerta" => 'Imagen horizontal eliminada!'
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

         $query = "UPDATE imagen_horizontal SET imgh_ruta='', imgh_tipo='' WHERE imgh_id=?";
         $this->ExecuteQueryContinuous($query,array($id));
         
         //eliminar el archivo | eliminara el archivo si no hay otro registro con la misma ruta
         $this->eliminarArchivo($path_a_eliminar);

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
   function eliminarArchivo($path_a_eliminar) {
      $ruta = explode("../",$path_a_eliminar);
      $ruta = trim(end($ruta));
      $cantidad_mismo_path = (int)$this->contarRegistrosConLaMismaRuta($ruta);
      if ($cantidad_mismo_path < 1) { // Si no hay más imagenes con el mismo path (misma imagen) eliminar archivo.
         @unlink($path_a_eliminar);
      }
   }
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
            "Mensaje_alerta" => 'Banners horizontales actualizados.',
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

   function actualizarOrden($id,$orden) {
      try {
         $respuesta = array(
            "Resultado" => 'incorrecto',
            "Icono_alerta" => 'error',
            "Titulo_alerta" => 'Opps...!',
            "Mensaje_alerta" => 'Datos incorrectos.',
         );

         $query = "UPDATE imagen_horizontal SET imgh_order=? WHERE imgh_id=?";
         $this->ExecuteQuery($query,array($orden,$id));

         $respuesta = array(
            "Resultado" => 'correcto',
            "Icono_alerta" => 'success',
            "Titulo_alerta" => 'EXITO!',
            "Mensaje_alerta" => 'Banner horizontal orden actualizado.',
            "Datos" => "$id",
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

   function contarRegistrosActivosPorCliente($ubicacion) {
      try {
         $query = "SELECT COUNT(*) as cantidad FROM imagen_horizontal WHERE imgh_status=1 AND cli_id=$ubicacion";
         $resultado = $this->SelectOnlyOneContinuous($query);
         return $resultado["cantidad"];
      } catch (Exception $e) {
         echo "Error: ".$e->getMessage();
      }
   }
   function contarRegistrosConLaMismaRuta($path_a_eliminar) {
      try {
         $query = "SELECT COUNT(*) as cantidad FROM imagen_horizontal WHERE imgh_ruta='$path_a_eliminar'";
         $resultado = $this->SelectOnlyOne($query);
         return $resultado["cantidad"];
      } catch (Exception $e) {
         echo "Error: ".$e->getMessage();
      }
   }
}
