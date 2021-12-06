<?php
require_once "../Templates/header.php";
require_once "../Templates/nav_bar.php";
require_once "../Templates/side_bar.php";

include '../Models/Bancompleto/Bancompleto.php';
$Bancompleto = new Bancompleto();
include '../Models/Cliente/Cliente.php';
$Cliente = new Cliente();
?>
<!-- Content Wrapper. Contenido de la pagina -->
<div class="content-wrapper text-sm">
   <!-- Content Header (Encabezado en el contenido de la pagina) -->
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>BANNER COMPLETO <span class="text-sm text-muted">(Este Banner solo se muestra en la Plantilla 3)</span></h1>
            </div>
         </div>
      </div><!-- /.container-fluid -->
   </section>

   <!-- Main content -->
   <section class="content">

      <!-- card -->
      <div class="card card-outline card-dark shadow">
         <div class="container-fluid mt-2">
            <i class="text-muted h5">Medidas: <b>1920px</b> ancho &nbsp; <i class="fas fa-times"></i> &nbsp; <b>1080px</b> alto</i>
            <button id="btn_abrir_modal" class="float-end btn btn-success fw-bold" data-bs-toggle="modal" data-bs-target="#modal"><i class="fa-solid fa-circle-plus"></i>&nbsp; AGREGAR IMAGEN</button>
         </div>
         <div class="card-body">
            <!-- tabla -->
            <table id="tabla_bancompletos" class="table text-center" style="width:100%">
               <thead class="thead-dark">
                  <tr>
                     <th>Ubicación</th>
                     <th>Fecha inicial</th>
                     <th>Fecha final</th>
                     <th>Imagen</th>
                     <th>Activo</th>
                     <th>Editar / Eliminar</th>
                  </tr>
               </thead>
               <tbody>
                  <?php
                  error_reporting(0);
                  foreach ($Bancompleto->mostrarBancompletos() as $objBancompleto) {
                     $imgc_id = $objBancompleto['imgc_id'];
                     $cli_id = $objBancompleto['cli_id'];
                     $activo = $objBancompleto['imgc_status'] == true ? "<i class='fa-regular fa-circle-check fa-2xl td_status' data-id='$imgc_id' data-status='$objBancompleto[imgc_status]' data-fecha-final='$objBancompleto[imgc_fecha_fin]'></i>" : "<i class='fa-regular fa-circle-xmark fa-2xl td_status' data-id='$imgc_id' data-status='$objBancompleto[imgc_status]' data-fecha-final='$objBancompleto[imgc_fecha_fin]'></i>";
                     echo  "
                        <tr>
                           <td class='align-middle'>$objBancompleto[cli_nom_empresa]</td>
                           <td class='align-middle td_fecha_inicial'>$objBancompleto[imgc_fecha_ini]</td>
                           <td class='align-middle td_fecha_final'>$objBancompleto[imgc_fecha_fin]</td>
                           <td class='align-middle'>
                              <img src='../$objBancompleto[imgc_ruta]' width='50' preload='true'></img>
                              </td>
                           <td class='align-middle'>$activo</td>
                           <td class='align-middle'>
                              <button class='btn btn-primary btn_editar mb-1' data-bs-toggle='modal' data-bs-target='#modal' data-id='$objBancompleto[imgc_id]'><i class='fa-solid fa-pen-to-square fa-lg'></i></button>
                              <span class='mx-md-2'></span>
                              <button class='btn btn-danger btn_eliminar' data-id='$objBancompleto[imgc_id]' data-nombre='$objBancompleto[cli_nom_empresa]'><i class='fa-solid fa-trash-can'></i></button>
                           </td>
                        </tr>
                     ";
                  }
                  ?>
               </tbody>
               <tfoot>
                  <tr class="thead-dark">
                     <th>Ubicación</th>
                     <th>Fecha inicial</th>
                     <th>Fecha final</th>
                     <th>Imagen</th>
                     <th>Activo</th>
                     <th>Editar / Eliminar</th>
                  </tr>
               </tfoot>
            </table>
         </div>
         <!-- /.card-body -->
      </div>
      <!-- /.card -->

      <!-- Modal -->
      <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true" aria-modal="true">
         <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title fw-bold" id="modalLabel"><i class="fa-regular fa-file-image"></i>&nbsp; AGREGAR BANNER COMPLETO</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-body">
                  <form id="formulario_modal" enctype="multipart/form-data">
                     <input type="hidden" id="accion" name="accion">
                     <input type="hidden" id="id" name="id" value=''>
                     <div class="mb-3">
                        <label for="input_ubicacion" class="form-label">Ubicación:</label>
                        <select class="select2 form-control" style="width:100%" aria-label="Default select example" id="input_ubicacion" name="input_ubicacion">
                           <option value="-1">Selecciona una opción</option>
                           <?php
                           foreach ($Cliente->obtenerUbicaciones() as $ubicacion) {
                              echo "<option value='$ubicacion[cli_id]'>$ubicacion[cli_nom_empresa]</option>";
                           }
                           ?>
                        </select>
                     </div>
                     <div class="mb-3">
                        <label for="input_fecha_inicial" class="form-label">Fecha inicial:</label>
                        <input class="form-control" type="date" id="input_fecha_inicial" name="input_fecha_inicial">
                     </div>
                     <div class="mb-3">
                        <label for="input_fecha_final" class="form-label">Fecha final:</label>
                        <input class="form-control" type="date" id="input_fecha_final" name="input_fecha_final">
                     </div>
                     <!-- DIV CARGAR IMAGEN -->
                     <div class="mb-3" id="div_cargar_archivo">
                        <label for="input_archivo" class="form-label">Cargar banner completo:</label>
                        <input class="form-control" type="file" id="input_archivo" name="input_archivo" accept="image/*">
                        <div class="form-text">Subir archivo con un peso máximo de <b id="peso_archivo"></b><b>MB</b>.</div>
                     </div>
                     <!-- DIV CARGAR IMAGEN -->
                     <!-- DIV IMAGEN CARGADO -->
                     <div class="mb-3" id="div_archivo_cargado">
                        <label for="ver_archivo" class="form-label">Banner completo cargado:</label>
                        <img src="<?php echo "../$objBancompleto[imgc_ruta]" ?>" controls preview="true" class="" id="ver_archivo" width="100%"></img>
                        <button type="button" id="btn_quitar_archivo" class="btn btn-default btn-block">QUITAR IMAGEN</button>
                     </div>
                     <!-- DIV IMAGEN CARGADO -->
                     <div class="row g-3 align-items-center">
                        <div class="col-auto">
                           <label for="input_status" class="col-form-label h4">STATUS:</label>
                        </div>
                        <div class="col-auto mx-2">
                           <div class="form-check form-switch">
                              <input class="form-check-input" type="checkbox" role="switch" id="input_status" name="input_status" value="1" data-activo="1" checked>
                              <label class="form-check-label fst-italic" id="label_input_status" for="input_status">Activo</label>
                           </div>
                        </div>
                     </div>
               </div>
               <div class="modal-footer">
                  <button type="submit" id="btn_enviar_formulario" class="btn btn-success fw-bold">AGREGAR</button>
                  <button type="reset" id="btn_reset_formulario" class="btn btn-secondary">Limpiar todo</button>
                  </form> <!-- ./form -->
               </div>
            </div>
         </div>
      </div>

   </section>
   <!-- /.content -->

</div>
<!-- /.content-wrapper -->


</div>
<!-- ./wrapper (este se abre en el Template-header) -->

<?php
require_once '../Templates/footer.php';
?>
<script src="../Scripts/index.js"></script>
<script src="../Scripts/bancompleto.js"></script>