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

class Usuario extends DB_connection
{
   //SECCION DE LOGIN
   function iniciarSesion($usuario,$contrasenia) {
      try {
         $query = "SELECT u.usr_id, u.usr_nombre, u.usr_pass, u.usr_permisos FROM usuarios as u WHERE u.usr_nombre='$usuario'";

         $respuesta = array(
            "Resultado" => 'incorrecto',
            "Icono_alerta" => 'error',
            "Titulo_alerta" => 'Opps...!',
            "Mensaje_alerta" => 'Usuario incorrecto.',
         );

         $consulta = $this->SelectOnlyOne($query);
         if (sizeof($consulta) > 0) {
            if (password_verify($contrasenia, $consulta["usr_pass"])) {
               setcookie("id_usuario",$consulta["usr_id"], time() + (86400*30), "/");
               setcookie("usuario",$consulta["usr_nombre"], time() + (86400*30), "/");
               setcookie("permisos",$consulta["usr_permisos"], time() + (86400*30), "/");
               setcookie("sesion","activa", time() + (86400*30), "/");
               
               $respuesta = array(
                  "Resultado" => 'correcto',
                  "Icono_alerta" => 'success',
                  "Titulo_alerta" => 'Bienvenido!',
                  "Mensaje_alerta" => $consulta['usr_nombre'],
               );
            } else {
               $respuesta = array(
                  "Resultado" => 'incorrecto',
                  "Icono_alerta" => 'error',
                  "Titulo_alerta" => 'Opps...!',
                  "Mensaje_alerta" => 'Contraseña incorrecta',
               );
            }
         }
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

   function cerrarSesion() {
      unset($_COOKIE["id_usuario"]);
      unset($_COOKIE["usuario"]);
      unset($_COOKIE["permisos"]);
      unset($_COOKIE["sesion"]);

      setcookie("id_usuario", null, -1, "/");
      setcookie("usuario", null, -1, "/");
      setcookie("permisos", null, -1,);
      setcookie("sesion", null, -1, "/");

      $respuesta = array(
         "Resultado" => 'correcto',
         "Icono_alerta" => 'success',
         "Titulo_alerta" => 'Cerrando Sesion',
         "Mensaje_alerta" => '',
      );
      die(json_encode($respuesta));
   }
   //SECCION DE LOGIN
   
   function mostrarUsuario($id_usuario) {
      try {
         $respuesta = array(
            "Resultado" => 'incorrecto',
            "Icono_alerta" => 'error',
            "Titulo_alerta" => 'Opps...!',
            "Mensaje_alerta" => 'Datos incorrectos.',
         );

         $query = "SELECT * FROM usuarios WHERE usr_id=$id_usuario";
         $consulta = $this->SelectOnlyOne($query);
         if (sizeof($consulta) > 0) {
            $respuesta = array(
               "Resultado" => 'correcto',
               "Icono_alerta" => 'success',
               "Titulo_alerta" => 'EXITO!',
               "Mensaje_alerta" => 'Usuario registrado.',
               "Datos" => array(
                  "Id" => $consulta["usr_id"],
                  "Nombre" => $consulta["usr_nombre"],
                  "Contrasenia" => $consulta["usr_pass_nor"],
                  "Permisos" => $consulta["usr_permisos"]
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

   function mostrarUsuarios() {
      try {
         $query = "SELECT * FROM usuarios";
         $resultado = $this->MostrarEnHTML($query);
         if (sizeof($resultado) > 0) { return $resultado; }

      } catch (Exception $e) {
         echo "Error: ".$e->getMessage();
      }
   }

   function crearUsuario($usuario,$contrasenia,$permisos) {
      try {
         $respuesta = array(
            "Resultado" => 'incorrecto',
            "Icono_alerta" => 'error',
            "Titulo_alerta" => 'Opps...!',
            "Mensaje_alerta" => 'Datos incorrectos.',
         );

         $contrasenia_hash = password_hash($contrasenia,PASSWORD_DEFAULT);
         $query = "INSERT INTO usuarios (usr_pass,usr_pass_nor,usr_nombre,usr_nivel,usr_permisos) VALUES (?,?,?,?,?)";
         $this->ExecuteQuery($query, array($contrasenia_hash,$contrasenia,$usuario,'1',$permisos));
         $respuesta = array(
            "Resultado" => 'correcto',
            "Icono_alerta" => 'success',
            "Titulo_alerta" => 'EXITO!',
            "Mensaje_alerta" => 'Usuario registrado.',
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

   function editarUsuario($id,$usuario,$contrasenia,$permisos){
      try {
         $respuesta = array(
            "Resultado" => 'incorrecto',
            "Icono_alerta" => 'error',
            "Titulo_alerta" => 'Opps...!',
            "Mensaje_alerta" => 'Datos incorrectos.',
         );

         $query = "UPDATE usuarios SET usr_nombre=?, usr_pass_nor=?, usr_permisos=? WHERE usr_id=?";
         $this->ExecuteQuery($query,array($usuario,$contrasenia,$permisos,$id));

         $respuesta = array(
            "Resultado" => 'correcto',
            "Icono_alerta" => 'success',
            "Titulo_alerta" => 'EXITO!',
            "Mensaje_alerta" => 'Usuario actualizado.',
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

   function eliminarUsuario($id) {
      try {
         $respuesta = array(
            "Resultado" => 'incorrecto',
            "Icono_alerta" => 'error',
            "Titulo_alerta" => 'Opps...!',
            "Mensaje_alerta" => 'Datos incorrectos.',
         );

         $query = "DELETE FROM usuarios WHERE usr_id=?";
         $this->ExecuteQuery($query,array($id));

         $respuesta = array(
            "Resultado" => 'correcto',
            "Icono_alerta" => 'success',
            "Titulo_alerta" => 'EXITO!',
            "Mensaje_alerta" => 'Usuario eliminado.',
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
