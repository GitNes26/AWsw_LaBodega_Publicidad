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

class Reproductor extends DB_connection
{  
   function mostrarReproductores() {
      try {
         $query = "SELECT c.cli_id,c.cli_nom_empresa FROM clientes as c WHERE c.cli_activo = 1";
         $resultado = $this->SelectAll($query);
         if (sizeof($resultado) > 0) { return $resultado; }

      } catch (Exception $e) {
         echo "Error: ".$e->getMessage();
      }
   }

   function videosParaReproducir($id_cliente,$plantilla,$fecha_inicial,$fecha_final) {
      try {
         $query = "SELECT v.vid_ruta,v.vid_plantilla,v.vid_fecha_ini,v.vid_fecha_fin
         FROM video as v INNER JOIN clientes as c ON c.cli_id=v.cli_id
         WHERE c.cli_id=$id_cliente
         AND v.vid_status=1
         AND v.vid_plantilla=$plantilla
         AND v.vid_fecha_ini <= '$fecha_final'
         AND v.vid_fecha_fin >= '$fecha_inicial'";

         $resultado = $this->SelectAll($query);

         $respuesta = array(
            "Resultado" => 'correcto',
            "Icono_alerta" => 'success',
            "Titulo_alerta" => 'EXITO!',
            "Mensaje_alerta" => 'Lista de videos obtenida.',
            "Datos" => $resultado,
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

   function banverticalesParaReproducir($id_cliente,$fecha_inicial,$fecha_final) {
      try {
         $query = "SELECT iv.img_ruta,iv.img_status,iv.img_fecha_ini
         FROM imagen_vertical as iv INNER JOIN clientes as c ON c.cli_id=iv.cli_id
         WHERE c.cli_id=$id_cliente
         AND iv.img_status=1
         AND iv.img_fecha_ini <= '$fecha_final'
         AND iv.img_fecha_fin >= '$fecha_inicial'";

         $resultado = $this->SelectAll($query);

         $respuesta = array(
            "Resultado" => 'correcto',
            "Icono_alerta" => 'success',
            "Titulo_alerta" => 'EXITO!',
            "Mensaje_alerta" => 'Lista de banners veticales obtenida.',
            "Datos" => $resultado,
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

   function banhorizontalesParaReproducir($id_cliente,$fecha_inicial,$fecha_final) {
      try {
         $query = "SELECT ih.imgh_ruta,ih.imgh_status,ih.imgh_fecha_ini,ih.imgh_fecha_fin
         FROM imagen_horizontal as ih INNER JOIN clientes as c ON c.cli_id=ih.cli_id
         WHERE c.cli_id=$id_cliente
         AND ih.imgh_status=1
         AND ih.imgh_fecha_ini <= '$fecha_final'
         AND ih.imgh_fecha_fin >= '$fecha_inicial'";

         $resultado = $this->SelectAll($query);

         $respuesta = array(
            "Resultado" => 'correcto',
            "Icono_alerta" => 'success',
            "Titulo_alerta" => 'EXITO!',
            "Mensaje_alerta" => 'Lista de banners horizontales obtenida.',
            "Datos" => $resultado,
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

   function bancompletosParaReproducir($id_cliente,$fecha_inicial,$fecha_final) {
      try {
         $query = "SELECT ic.imgc_ruta,ic.imgc_status,ic.imgc_fecha_ini,ic.imgc_fecha_fin
         FROM imagen_completa as ic INNER JOIN clientes as c ON c.cli_id=ic.cli_id
         WHERE c.cli_id=$id_cliente
         AND ic.imgc_status=1
         AND ic.imgc_fecha_ini <= '$fecha_final'
         AND ic.imgc_fecha_fin >= '$fecha_inicial'";

         $resultado = $this->SelectAll($query);

         $respuesta = array(
            "Resultado" => 'correcto',
            "Icono_alerta" => 'success',
            "Titulo_alerta" => 'EXITO!',
            "Mensaje_alerta" => 'Lista de banners completos obtenida.',
            "Datos" => $resultado,
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

   function textosParaReproducir($id_cliente,$fecha_inicial,$fecha_final) {
      try {
         $query = "SELECT t.text_spot,t.text_fecha_ini,t.text_fecha_fin,t.text_tipo,t.text_hora_ini,t.text_hora_fin,t.text_color,t.text_fondo_color
         FROM texto as t INNER JOIN clientes as c ON c.cli_id=t.cli_id
         WHERE c.cli_id=$id_cliente
         AND t.text_status=1
         AND t.text_fecha_ini <= '$fecha_final'
         AND t.text_fecha_fin >= '$fecha_inicial'";

         $resultado = $this->SelectAll($query);

         $respuesta = array(
            "Resultado" => 'correcto',
            "Icono_alerta" => 'success',
            "Titulo_alerta" => 'EXITO!',
            "Mensaje_alerta" => 'Lista de textos cintilla obtenida.',
            "Datos" => $resultado,
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
}
