<?php
include ('Cliente.php');
$Cliente = new Cliente();

if (isset($_POST['accion'])) { $accion = $_POST['accion']; }

if (isset($_POST['id'])) { $id = $_POST['id']; }
if (isset($_POST['input_nombre'])) { $nombre = $_POST['input_nombre']; }
if (isset($_POST['input_activo'])) $activo = $_POST['input_activo'];

// var_dump($_POST);

//PETICIONES
if ($accion == 'mostrar_cliente') {
   $Cliente->mostrarCliente($id);
}

if ($accion == 'crear_cliente') {
   $Cliente->crearCliente($nombre,$activo);
}

if ($accion == 'editar_cliente') {
   $Cliente->editarCliente($id,$nombre,$activo);
}

if ($accion == "eliminar_cliente") {
   $Cliente->eliminarCliente($id);
}


//FUNCIONES
if ($accion == "mostrar_clientes_ajax") {
   $Cliente->mostrarClientesAjax();
}