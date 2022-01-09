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

class Bancompleto extends DB_connection
{   
   function mostrarBancompleto($id) {
      try {
         $respuesta = array(
            "Resultado" => 'incorrecto',
            "Icono_alerta" => 'error',
            "Titulo_alerta" => 'Opps...!',
            "Mensaje_alerta" => 'Datos incorrectos.',
         );
         $query = "SELECT c.cli_id,ic.imgc_id,c.cli_nom_empresa,ic.imgc_fecha_ini,ic.imgc_fecha_fin,ic.imgc_ruta,ic.imgc_status FROM imagen_completa as ic INNER JOIN clientes as c ON c.cli_id=ic.cli_id WHERE ic.imgc_id=$id";
         $consulta = $this->SelectOnlyOne($query);
         if (sizeof($consulta) > 0) {
            $respuesta = array(
               "Resultado" => 'correcto',
               "Icono_alerta" => 'success',
               "Titulo_alerta" => 'EXITO!',
               "Mensaje_alerta" => 'Mostrando imagen completa.',
               "Datos" => array(
                  "Id" => $consulta["imgc_id"],
                  "Id_cliente" => $consulta["cli_id"],
                  "Ubicacion" => $consulta["cli_nom_empresa"],
                  "Ruta" => $consulta["imgc_ruta"],
                  "Fecha_inicial" => $consulta["imgc_fecha_ini"],
                  "Fecha_final" => $consulta["imgc_fecha_fin"],
                  "Status" => $consulta["imgc_status"],
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

   function mostrarBancompletos() {
      try {
         $query = "SELECT c.cli_id,ic.imgc_id,c.cli_nom_empresa,ic.imgc_fecha_ini,ic.imgc_fecha_fin,ic.imgc_ruta,ic.imgc_status,ic.imgc_order FROM imagen_completa as ic INNER JOIN clientes as c ON c.cli_id=ic.cli_id ORDER BY ic.imgc_order ASC";
         $resultado = $this->MostrarEnHTML($query);
         if (sizeof($resultado) > 0) { return $resultado; }

      } catch (Exception $e) {
         echo "Error: ".$e->getMessage();
      }
   }
   function mostrarBancompletosPorCliente($id_cliente) {
      try {
         $query = "SELECT c.cli_id,ic.imgc_id,c.cli_nom_empresa,ic.imgc_fecha_ini,ic.imgc_fecha_fin,ic.imgc_ruta,ic.imgc_status,ic.imgc_order FROM imagen_completa as ic INNER JOIN clientes as c ON c.cli_id=ic.cli_id WHERE ic.cli_id=$id_cliente ORDER BY ic.imgc_order ASC";
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

   function crearBancompleto($ubicacion,$ruta,$tipo,$fecha_inicial,$fecha_final,$status) {
      try {
         $respuesta = array(
            "Resultado" => 'incorrecto',
            "Icono_alerta" => 'error',
            "Titulo_alerta" => 'Opps...!',
            "Mensaje_alerta" => 'Datos incorrectos.',
         );
         // $cantidad = $this->contarBancompletosActivos()+1;
         $orden = $this->contarRegistrosActivosPorCliente($ubicacion)+1;

         $query = "INSERT INTO imagen_completa (cli_id,imgc_ruta,imgc_tipo,imgc_order,imgc_fecha_ini,imgc_fecha_fin,imgc_status) VALUES (?,?,?,?,?,?,?)";
         $this->ExecuteQuery($query, array($ubicacion,$ruta,$tipo,$orden,$fecha_inicial,$fecha_final,$status));
         $respuesta = array(
            "Resultado" => 'correcto',
            "Icono_alerta" => 'success',
            "Titulo_alerta" => 'EXITO!',
            "Mensaje_alerta" => 'Imagen completa subida.',
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

   function editarBancompleto($id,$ubicacion,$ruta,$tipo,$fecha_inicial,$fecha_final,$status,$asignar_orden){
      try {
         $respuesta = array(
            "Resultado" => 'incorrecto',
            "Icono_alerta" => 'error',
            "Titulo_alerta" => 'Opps...!',
            "Mensaje_alerta" => 'Datos incorrectos.',
         );
         // var_dump($asignar_orden);
         if ($asignar_orden > 0) {
            if ($asignar_orden == 1) {
               // $orden = $this->contarBancompletosActivos()+1; 
               $orden = $this->contarRegistrosActivosPorCliente($ubicacion)+1; 

               if ($ruta == "") { //si no se desea editar/cambiar la imagen
                  $query = "UPDATE imagen_completa SET cli_id=?, imgc_order=?, imgc_fecha_ini=?, imgc_fecha_fin=?, imgc_status=? WHERE imgc_id=?";
                  $this->ExecuteQuery($query,array($ubicacion,$orden,$fecha_inicial,$fecha_final,$status,$id));
               } else {
                  $query = "UPDATE imagen_completa SET cli_id=?, imgc_ruta=?, imgc_tipo=?, imgc_order=?, imgc_fecha_ini=?, imgc_fecha_fin=?, imgc_status=? WHERE imgc_id=?";
                  $this->ExecuteQuery($query,array($ubicacion,$ruta,$tipo,$orden,$fecha_inicial,$fecha_final,$status,$id));
               }
            } else {
               $orden = $asignar_orden;

               if ($ruta == "") { //si no se desea editar/cambiar la imagen
                  $query = "UPDATE imagen_completa SET cli_id=?, imgc_order=?, imgc_fecha_ini=?, imgc_fecha_fin=?, imgc_status=? WHERE imgc_id=?";
                  $this->ExecuteQuery($query,array($ubicacion,$orden,$fecha_inicial,$fecha_final,$status,$id));
               } else {
                  $query = "UPDATE imagen_completa SET cli_id=?, imgc_ruta=?, imgc_tipo=?, imgc_order=?, imgc_fecha_ini=?, imgc_fecha_fin=?, imgc_status=? WHERE imgc_id=?";
                  $this->ExecuteQuery($query,array($ubicacion,$ruta,$tipo,$orden,$fecha_inicial,$fecha_final,$status,$id));
               }
            }
         } else {            
            if ($ruta == "") { //si no se desea editar/cambiar la imagen
               $query = "UPDATE imagen_completa SET cli_id=?, imgc_fecha_ini=?, imgc_fecha_fin=?, imgc_status=? WHERE imgc_id=?";
               $this->ExecuteQuery($query,array($ubicacion,$fecha_inicial,$fecha_final,$status,$id));
            } else {
               $query = "UPDATE imagen_completa SET cli_id=?, imgc_ruta=?, imgc_tipo=?, imgc_fecha_ini=?, imgc_fecha_fin=?, imgc_status=? WHERE imgc_id=?";
               $this->ExecuteQuery($query,array($ubicacion,$ruta,$tipo,$fecha_inicial,$fecha_final,$status,$id));
            }
         }

         $respuesta = array(
            "Resultado" => 'correcto',
            "Icono_alerta" => 'success',
            "Titulo_alerta" => 'EXITO!',
            "Mensaje_alerta" => 'Imagen completa actualizada.',
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

   function eliminarBancompleto($id,$path_a_eliminar) {
      try {
         $respuesta = array(
            "Resultado" => 'incorrecto',
            "Icono_alerta" => 'error',
            "Titulo_alerta" => 'Opps...!',
            "Mensaje_alerta" => 'Datos incorrectos.',
         );

         $query = "DELETE FROM imagen_completa WHERE imgc_id=?";
         $this->ExecuteQueryContinuous($query,array($id));

         //eliminar el archivo | eliminara el archivo si no hay otro registro con la misma ruta
         $this->eliminarArchivo($path_a_eliminar);

         $respuesta = array(
            "Resultado" => 'correcto',
            "Icono_alerta" => 'success',
            "Titulo_alerta" => 'Imagen completa eliminada!'
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

         $query = "UPDATE imagen_completa SET imgc_ruta='', imgc_tipo='' WHERE imgc_id=?";
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
            "Mensaje_alerta" => 'Banners completos actualizados.',
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

         $query = "UPDATE imagen_completa SET imgc_order=? WHERE imgc_id=?";
         $this->ExecuteQuery($query,array($orden,$id));

         $respuesta = array(
            "Resultado" => 'correcto',
            "Icono_alerta" => 'success',
            "Titulo_alerta" => 'EXITO!',
            "Mensaje_alerta" => 'Banner completos orden actualizado.',
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

   function contarBancompletosActivos() {
      try {
         $query = "SELECT COUNT(*) as cantidad FROM imagen_completa WHERE imgc_status=1";
         $resultado = $this->SelectOnlyOneContinuous($query);
         return $resultado["cantidad"];
      } catch (Exception $e) {
         echo "Error: ".$e->getMessage();
      }
   }
   function contarRegistrosActivosPorCliente($ubicacion) {
      try {
         $query = "SELECT COUNT(*) as cantidad FROM imagen_completa WHERE imgc_status=1 AND cli_id=$ubicacion";
         $resultado = $this->SelectOnlyOneContinuous($query);
         return $resultado["cantidad"];
      } catch (Exception $e) {
         echo "Error: ".$e->getMessage();
      }
   }
   function contarRegistrosConLaMismaRuta($path_a_eliminar) {
      try {
         $query = "SELECT COUNT(*) as cantidad FROM imagen_completa WHERE imgc_ruta='$path_a_eliminar'";
         $resultado = $this->SelectOnlyOne($query);
         return $resultado["cantidad"];
      } catch (Exception $e) {
         echo "Error: ".$e->getMessage();
      }
   }
}
