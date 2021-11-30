<?php
include ('Usuario.php');
$Usuario = new Usuario();

if (isset($_POST['accion'])) { $accion = $_POST['accion']; }

//SECCION DE LOGIN
if (isset($_POST['usuario'])) { $usuario = $_POST['usuario']; }
if (isset($_POST['contrasenia'])) { $contrasenia = $_POST['contrasenia']; }
//FUNCIONES 
if ($accion == 'iniciar_sesion') { $Usuario->iniciarSesion($usuario,$contrasenia); }
if ($accion == 'cerrar_sesion') { $Usuario->CerrarSesion(); }
//SECCION DE LOGIN


if (isset($_POST['id'])) { $id = $_POST['id']; }
if (isset($_POST['input_usuario'])) { $usuario = $_POST['input_usuario']; }
if (isset($_POST['input_contrasenia'])) $contrasenia = $_POST['input_contrasenia'];
if (isset($_POST['input_permisos_todos'])) { $input_permisos_todos = $_POST['input_permisos_todos']; }
if (isset($_POST['input_permisos'])) { $input_permisos = $_POST['input_permisos']; }

//PETICIONES
if ($accion == 'mostrar_usuario') {
   $Usuario->mostrarUsuario($id);
}

if ($accion == 'crear_usuario') {
   if (!empty($input_permisos_todos) && !empty($input_permisos_todos)) {
      $permisos = estructurarPermisos($input_permisos_todos,$input_permisos);
   } else if (!empty($input_permisos_todos)) {
      $permisos = estructurarPermisos($input_permisos_todos);
   } else if (!empty($input_permisos)) {
      $permisos = estructurarPermisos(null,$input_permisos);
   }

   $Usuario->crearUsuario($usuario, $contrasenia, $permisos);
}

if ($accion == 'editar_usuario') {
   if (!empty($input_permisos_todos) && !empty($input_permisos_todos)) {
      $permisos = estructurarPermisos($input_permisos_todos,$input_permisos);
   } else if (!empty($input_permisos_todos)) {
      $permisos = estructurarPermisos($input_permisos_todos);
   } else if (!empty($input_permisos)) {
      $permisos = estructurarPermisos(null,$input_permisos);
   }

   $Usuario->editarUsuario($id,$usuario,$contrasenia,$permisos);
}

if ($accion == "eliminar_usuario") {
   $Usuario->eliminarUsuario($id);
}


//FUNCIONES
function estructurarPermisos($input_permisos_todos=null,$input_permisos=null) {
   $permisos = "";
   if (!empty($input_permisos_todos)) {
      $permisos = $input_permisos_todos;
   } else {
      if (!empty($input_permisos)) {
         $cantidad = sizeof($input_permisos);
         $i = 1;
         foreach ($input_permisos as $permiso) {
            if ($i < $cantidad) {
               $permisos .= "$permiso@";
            } else {
               $permisos .= "$permiso";
            }
            $i++;
         }
      }
   }
   return $permisos;
}