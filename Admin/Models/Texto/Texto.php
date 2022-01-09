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

class Texto extends DB_connection
{   
   function mostrarTexto($id) {
      try {
         $respuesta = array(
            "Resultado" => 'incorrecto',
            "Icono_alerta" => 'error',
            "Titulo_alerta" => 'Opps...!',
            "Mensaje_alerta" => 'Datos incorrectos.',
         );
         $query = "SELECT c.cli_id,t.text_id,c.cli_nom_empresa,t.text_spot,t.text_fecha_ini,t.text_fecha_fin,t.text_status,t.text_tipo,t.text_hora_ini,t.text_hora_fin,t.text_color,t.text_fondo_color FROM texto as t INNER JOIN clientes as c ON c.cli_id=t.cli_id WHERE t.text_id=$id";
         $consulta = $this->SelectOnlyOne($query);
         if (sizeof($consulta) > 0) {
            $respuesta = array(
               "Resultado" => 'correcto',
               "Icono_alerta" => 'success',
               "Titulo_alerta" => 'EXITO!',
               "Mensaje_alerta" => 'Mostrando texto.',
               "Datos" => array(
                  "Id" => $consulta["text_id"],
                  "Id_cliente" => $consulta["cli_id"],
                  "Ubicacion" => $consulta["cli_nom_empresa"],
                  "Texto" => $consulta["text_spot"],
                  "Fecha_inicial" => $consulta["text_fecha_ini"],
                  "Fecha_final" => $consulta["text_fecha_fin"],
                  "Status" => $consulta["text_status"],
                  "Tipo" => $consulta["text_tipo"],
                  "Hora_inicial" => $consulta["text_hora_ini"],
                  "Hora_final" => $consulta["text_hora_fin"],
                  "Color_texto" => $consulta["text_color"],
                  "Color_fondo" => $consulta["text_fondo_color"],
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

   function mostrarTextoes() {
      try {
         $query = "SELECT c.cli_id,t.text_id,c.cli_nom_empresa,t.text_spot,t.text_fecha_ini,t.text_fecha_fin,t.text_status,t.text_tipo,t.text_order,t.text_hora_ini,t.text_hora_fin,t.text_color,t.text_fondo_color FROM texto as t INNER JOIN clientes as c ON c.cli_id=t.cli_id ORDER BY t.text_order ASC";
         $resultado = $this->MostrarEnHTML($query);
         if (sizeof($resultado) > 0) { return $resultado; }

      } catch (Exception $e) {
         echo "Error: ".$e->getMessage();
      }
   }
   function mostrarTextosPorCliente($id_cliente) {
      try {
         $query = "SELECT c.cli_id,t.text_id,c.cli_nom_empresa,t.text_spot,t.text_fecha_ini,t.text_fecha_fin,t.text_status,t.text_tipo,t.text_order,t.text_hora_ini,t.text_hora_fin,t.text_color,t.text_fondo_color FROM texto as t INNER JOIN clientes as c ON c.cli_id=t.cli_id WHERE t.cli_id=$id_cliente ORDER BY ih.text_order ASC";
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

   function crearTexto($ubicacion,$fecha_inicial,$fecha_final,$texto,$status,$tipo,$hora_inicial,$hora_final,$color_texto,$color_fondo) {
      try {
         $respuesta = array(
            "Resultado" => 'incorrecto',
            "Icono_alerta" => 'error',
            "Titulo_alerta" => 'Opps...!',
            "Mensaje_alerta" => 'Datos incorrectos.',
         );
         $orden = $this->contarRegistrosActivosPorCliente($ubicacion)+1;

         $query = "INSERT INTO texto (cli_id,text_fecha_ini,text_fecha_fin,text_spot,text_status,text_tipo,text_order,text_hora_ini,text_hora_fin,text_color,text_fondo_color) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
         $this->ExecuteQuery($query, array($ubicacion,$fecha_inicial,$fecha_final,$texto,$status,$tipo,$orden,$hora_inicial,$hora_final,$color_texto,$color_fondo));
         $respuesta = array(
            "Resultado" => 'correcto',
            "Icono_alerta" => 'success',
            "Titulo_alerta" => 'EXITO!',
            "Mensaje_alerta" => 'Texto registrado.',
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

   function editarTexto($id,$ubicacion,$fecha_inicial,$fecha_final,$texto,$status,$tipo,$hora_inicial,$hora_final,$color_texto,$color_fondo,$asignar_orden){
      try {
         $respuesta = array(
            "Resultado" => 'incorrecto',
            "Icono_alerta" => 'error',
            "Titulo_alerta" => 'Opps...!',
            "Mensaje_alerta" => 'Datos incorrectos.',
         );

         if ($asignar_orden > 0) {
            if ($asignar_orden == 1)
               $orden = $this->contarRegistrosActivosPorCliente($ubicacion)+1; 
            else
               $orden = $asignar_orden;

            $query = "UPDATE texto SET cli_id=?, text_fecha_ini=?, text_fecha_fin=?, text_spot=?, text_status=?, text_tipo=?, text_order=?, text_hora_ini=?, text_hora_fin=?, text_color=?, text_fondo_color=?  WHERE text_id=?";
            $this->ExecuteQuery($query,array($ubicacion,$fecha_inicial,$fecha_final,$texto,$status,$tipo,$orden,$hora_inicial,$hora_final,$color_texto,$color_fondo,$id));
         } else {
            $query = "UPDATE texto SET cli_id=?, text_fecha_ini=?, text_fecha_fin=?, text_spot=?, text_status=?, text_tipo=?, text_hora_ini=?, text_hora_fin=?, text_color=?, text_fondo_color=?  WHERE text_id=?";
            $this->ExecuteQuery($query,array($ubicacion,$fecha_inicial,$fecha_final,$texto,$status,$tipo,$hora_inicial,$hora_final,$color_texto,$color_fondo,$id));
         }

         $respuesta = array(
            "Resultado" => 'correcto',
            "Icono_alerta" => 'success',
            "Titulo_alerta" => 'EXITO!',
            "Mensaje_alerta" => 'Texto actualizado.',
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

   function eliminarTexto($id) {
      try {
         $respuesta = array(
            "Resultado" => 'incorrecto',
            "Icono_alerta" => 'error',
            "Titulo_alerta" => 'Opps...!',
            "Mensaje_alerta" => 'Datos incorrectos.',
         );

         $query = "DELETE FROM texto WHERE text_id=?";
         $this->ExecuteQuery($query,array($id));

         $respuesta = array(
            "Resultado" => 'correcto',
            "Icono_alerta" => 'success',
            "Titulo_alerta" => 'Texto eliminado!'
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

         // $query = "UPDATE texto SET text_status='0' WHERE text_id IN (?)";
         $this->ExecuteQuery($query,null);

         $respuesta = array(
            "Resultado" => 'correcto',
            "Icono_alerta" => 'success',
            "Titulo_alerta" => 'EXITO!',
            "Mensaje_alerta" => 'Textos actualizados.',
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

         $query = "UPDATE texto SET text_order=? WHERE text_id=?";
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
         $query = "SELECT COUNT(*) as cantidad FROM texto WHERE text_status=1 AND cli_id=$ubicacion";
         $resultado = $this->SelectOnlyOneContinuous($query);
         return $resultado["cantidad"];
      } catch (Exception $e) {
         echo "Error: ".$e->getMessage();
      }
   }
}
