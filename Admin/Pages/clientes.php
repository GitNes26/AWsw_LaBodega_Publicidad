<?php
require_once "../Templates/header.php";
require_once "../Templates/nav_bar.php";
require_once "../Templates/side_bar.php";
?>
<!-- Content Wrapper. Contenido de la pagina -->
<div class="content-wrapper text-sm">
   <!-- Content Header (Encabezado en el contenido de la pagina) -->
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>UBICACIONES <i class="text-muted text-sm">(NEGOCIOS)</i></h1>
            </div>
            <!-- <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">Blank Page</li>
               </ol>
            </div> -->
         </div>
      </div><!-- /.container-fluid -->
   </section>

   <!-- Main content -->
   <section class="content">

      <!-- card -->
      <div class="card card-outline card-dark shadow">
         <div class="container-fluid mt-2">
            <button id="btn_abrir_modal" class="float-end btn btn-success fw-bold" data-bs-toggle="modal" data-bs-target="#modal"><i class="fa-solid fa-circle-plus"></i>&nbsp; AGREGAR NEGOCIO</button>
         </div>
         <div class="card-body">
            <!-- tabla -->
            <table id="tabla_clientes" class="table text-center" style="width:100%">
               <thead class="thead-dark">
                  <tr>
                     <th>Número ubicación</th>
                     <th>Negocio</th>
                     <th>Activo</th>
                     <th>Editar / Eliminar</th>
                  </tr>
               </thead>
               <tbody>
                  <?php
                  include '../Models/Cliente/Cliente.php';
                  error_reporting(0);
                  $Cliente = new Cliente();
                  foreach ($Cliente->mostrarClientes() as $objCliente) {
                     $cli_id = $objCliente['cli_id'];
                     $activo = $objCliente['cli_activo'] == true ? "<i class='fa-regular fa-circle-check fa-2xl'></i>" : "<i class='fa-regular fa-circle-xmark fa-2xl'></i>";
                     echo  "
                        <tr>
                           <td class='align-middle'>$objCliente[cli_id]</td>
                           <td class='align-middle'>$objCliente[cli_nom_empresa]</td>
                           <td class='align-middle'>$activo</td>
                           <td class='align-middle'>
                              <button class='btn btn-primary btn_editar mb-1' data-bs-toggle='modal' data-bs-target='#modal' data-id='$objCliente[cli_id]'><i class='fa-solid fa-pen-to-square fa-lg'></i></button>
                              <span class='mx-md-2'></span>
                              <button class='btn btn-danger btn_eliminar' data-id='$objCliente[cli_id]' data-nombre='$objCliente[cli_nom_empresa]'><i class='fa-solid fa-trash-can'></i></button>
                           </td>
                        </tr>
                     ";
                  }
                  ?>
               </tbody>
               <tfoot>
                  <tr class="thead-dark">
                     <th>Número ubicación</th>
                     <th>Negocio</th>
                     <th>Activo</th>
                     <th>Editar / Eliminar</th>
                  </tr>
               </tfoot>
            </table>
         </div>
         <!-- /.card-body -->
      </div>
      <!-- /.card -->

   </section>
   <!-- /.content -->

   <!-- Modal -->
   <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title fw-bold" id="modalLabel"><i class="fa-solid fa-kaaba"></i>&nbsp; REGISTRAR NEGOCIO</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <form id="formulario_modal" enctype="multipart/form-data">
                  <input type="hidden" id="accion" name="accion" value="crear_cliente">
                  <input type="hidden" name="id" name="id" value=''>
                  <div class="mb-3">
                     <label for="input_nombre" class="form-label">Nombre de la empresa:</label>
                     <input type="text" class="form-control" id="input_nombre" name="input_nombre">
                  </div>
                  <div class="row g-3 align-items-center">
                     <div class="col-auto">
                        <label for="input_activo" class="col-form-label h4">STATUS:</label>
                     </div>
                     <div class="col-auto mx-2">
                        <div class="form-check form-switch">
                           <input class="form-check-input" type="checkbox" role="switch" id="input_activo" name="input_activo" value="1" data-activo="1" checked>
                           <label class="form-check-label fst-italic" id="label_input_activo" for="input_activo">Activo</label>
                        </div>
                     </div>
                  </div>
            </div>
            <div class="modal-footer">
               <button type="submit" id="btn_enviar_formulario" class="btn btn-success fw-bold">AGREGAR</button>
               <button type="reset" id="btn_reset_formulario" class="btn btn-secondary">Limpiar todo</button>
               </form>
            </div>
         </div>
      </div>
   </div>

</div>
<!-- /.content-wrapper -->


</div>
<!-- ./wrapper (este se abre en el Template-header) -->

<?php
require_once '../Templates/footer.php';
?>
<script src="../Scripts/index.js"></script>
<script src="../Scripts/clientes.js"></script>