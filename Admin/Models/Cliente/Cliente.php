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

class Cliente extends DB_connection
{   
   function mostrarCliente($id) {
      try {
         $respuesta = array(
            "Resultado" => 'incorrecto',
            "Icono_alerta" => 'error',
            "Titulo_alerta" => 'Opps...!',
            "Mensaje_alerta" => 'Datos incorrectos.',
         );

         $query = "SELECT * FROM clientes WHERE cli_id=$id";
         $consulta = $this->SelectOnlyOne($query);
         if (sizeof($consulta) > 0) {
            $respuesta = array(
               "Resultado" => 'correcto',
               "Icono_alerta" => 'success',
               "Titulo_alerta" => 'EXITO!',
               "Mensaje_alerta" => 'Cliente registrado.',
               "Datos" => array(
                  "Id" => $consulta["cli_id"],
                  "Nombre" => $consulta["cli_nom_empresa"],
                  "Representante" => $consulta["cli_nom_representante"],
                  "Telefono" => $consulta["cli_tel_representante"],
                  "Correo" => $consulta["cli_correo_empresa"],
                  "Direccion" => $consulta["cli_direccion"],
                  "Activo" => $consulta["cli_activo"],
                  "Fecha_vigencia" => $consulta["cli_fecha_vigencia"],
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

   function mostrarClientes() {
      try {
         $query = "SELECT * FROM clientes";
         $resultado = $this->MostrarEnHTML($query);
         if (sizeof($resultado) > 0) { return $resultado; }

      } catch (Exception $e) {
         echo "Error: ".$e->getMessage();
      }
   }

   function crearCliente($nombre,$activo) {
      try {
         $respuesta = array(
            "Resultado" => 'incorrecto',
            "Icono_alerta" => 'error',
            "Titulo_alerta" => 'Opps...!',
            "Mensaje_alerta" => 'Datos incorrectos.',
         );

         $query = "INSERT INTO clientes (cli_nom_empresa,cli_activo) VALUES (?,?)";
         $this->ExecuteQuery($query, array($nombre,$activo));
         $respuesta = array(
            "Resultado" => 'correcto',
            "Icono_alerta" => 'success',
            "Titulo_alerta" => 'EXITO!',
            "Mensaje_alerta" => 'Cliente registrado.',
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

   function editarCliente($id,$nombre,$activo){
      try {
         $respuesta = array(
            "Resultado" => 'incorrecto',
            "Icono_alerta" => 'error',
            "Titulo_alerta" => 'Opps...!',
            "Mensaje_alerta" => 'Datos incorrectos.',
         );

         $query = "UPDATE clientes SET cli_nom_empresa=?, cli_activo=? WHERE cli_id=?";
         $this->ExecuteQuery($query,array($nombre,$activo,$id));

         $respuesta = array(
            "Resultado" => 'correcto',
            "Icono_alerta" => 'success',
            "Titulo_alerta" => 'EXITO!',
            "Mensaje_alerta" => 'Cliente actualizado.',
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

   function eliminarCliente($id) {
      try {
         $respuesta = array(
            "Resultado" => 'incorrecto',
            "Icono_alerta" => 'error',
            "Titulo_alerta" => 'Opps...!',
            "Mensaje_alerta" => 'Datos incorrectos.',
         );

         $query = "DELETE FROM clientes WHERE cli_id=?";
         $this->ExecuteQuery($query,array($id));

         $respuesta = array(
            "Resultado" => 'correcto',
            "Icono_alerta" => 'success',
            "Titulo_alerta" => 'EXITO!',
            "Mensaje_alerta" => 'Cliente eliminado.',
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

   
   function obtenerUbicaciones() {
      try {
         $query = "SELECT cli_id,cli_nom_empresa FROM clientes";
         $resultado = $this->SelectAll($query);
         if (sizeof($resultado) > 0) { return $resultado; }

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

   //FUNCIONES EXTRAS
   function mostrarClientesAjax(){
      try {
         $query = "SELECT * FROM clientes";
         $resultado = $this->SelectAll($query);
         if (sizeof($resultado) > 0) {
            $respuesta = array(
               "Resultado" => "correcto",
               "Datos" => $resultado
            );
         }
         else {
            $respuesta = array(
               "Mensaje" => 'Sin registros.',
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

   function mostrarClientesYCantidadesContenido() {
      try {
         $query = "SELECT cli_id as negocio_id, cli_nom_empresa as empresa,
         (SELECT COUNT(*) FROM video WHERE vid_status=1 AND cli_id=negocio_id) as videos_activos,
         (SELECT COUNT(*) FROM imagen_vertical WHERE img_status=1 AND cli_id=negocio_id) as banverticales_activos,
         (SELECT COUNT(*) FROM imagen_horizontal WHERE imgh_status=1 AND cli_id=negocio_id) as banhorizontales_activos,
         (SELECT COUNT(*) FROM imagen_completa WHERE imgc_status=1 AND cli_id=negocio_id) as bancompletos_activos,
         (SELECT COUNT(*) FROM texto WHERE text_status=1 AND text_tipo=1 AND cli_id=negocio_id) as textos_activos,
         (SELECT COUNT(*) FROM texto WHERE text_status=1 AND text_tipo=2 AND cli_id=negocio_id) as textos_promo_activos
         FROM clientes";
         $resultado = $this->MostrarEnHTML($query);
         if (sizeof($resultado) > 0) { return $resultado; }

      } catch (Exception $e) {
         echo "Error: ".$e->getMessage();
      }
   }

   function obtenerPrimerCliente() {
      try {
         $query = "SELECT cli_id,cli_nom_empresa FROM clientes ORDER BY cli_id ASC LIMIT 1";
         $resultado = $this->SelectOnlyOne($query);
         if (sizeof($resultado) > 0) { return $resultado; }

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
