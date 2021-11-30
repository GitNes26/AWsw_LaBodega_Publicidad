<?php
require_once "../Templates/header.php";
require_once "../Templates/nav_bar.php";
require_once "../Templates/side_bar.php";

include '../Models/Texto/Texto.php';
$Texto = new Texto();
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
               <h1>TEXTO CINTILLO</h1>
            </div>
         </div>
      </div><!-- /.container-fluid -->
   </section>

   <!-- Main content -->
   <section class="content">

      <!-- card -->
      <div class="card card-outline card-dark shadow">
         <div class="container-fluid mt-2">
            <button id="btn_abrir_modal" class="float-end btn btn-success fw-bold" data-bs-toggle="modal" data-bs-target="#modal"><i class="fa-solid fa-circle-plus"></i>&nbsp; AGREGAR TEXTO</button>
         </div>
         <div class="card-body">
            <!-- tabla -->
            <table id="tabla_textos" class="table text-center" style="width:100%">
               <thead class="thead-dark">
                  <tr>
                     <th>Ubicación</th>
                     <th>Fecha inicial</th>
                     <th>Fecha final</th>
                     <th>Texo</th>
                     <th>Tipo publicidad</th>
                     <th>Activo</th>
                     <th>Editar / Eliminar</th>
                  </tr>
               </thead>
               <tbody>
                  <?php
                  error_reporting(0);
                  foreach ($Texto->mostrarTextoes() as $objTexto) {
                     $text_id = $objTexto['text_id'];
                     $cli_id = $objTexto['cli_id'];
                     $color_texto = $objTexto['text_color'];
                     $color_fondo = $objTexto['text_fondo_color'];
                     $tipo_publicidad = $objTexto['text_tipo'];
                     if ($tipo_publicidad == 1) {
                        $tipo_publicidad = "Del día";
                        $td_texto = "<td class='align-middle' style='max-width:13rem'>$objTexto[text_spot]</td>";
                     }
                     else if ($tipo_publicidad == 2) {
                        $tipo_publicidad = "Promoción";
                        $td_texto = "<td class='align-middle' style='max-width:13rem; color:$color_texto; background-color:$color_fondo;'>$objTexto[text_spot]</td>";
                     } 

                     $fecha_inicial = $objTexto['text_fecha_ini'];
                     if ($objTexto['text_tipo'] == 2) { $fecha_inicial .= " $objTexto[text_hora_ini]"; }
                     
                     $fecha_final = $objTexto['text_fecha_fin'];
                     if ($objTexto['text_tipo'] == 2) { $fecha_final .= " $objTexto[text_hora_fin]"; }
                     $activo = $objTexto['text_status'] == true ? "<i class='fa-regular fa-circle-check fa-2xl td_status' data-id='$text_id' data-status='$objTexto[text_status]' data-fecha-final='$fecha_final'></i>" : "<i class='fa-regular fa-circle-xmark fa-2xl td_status' data-id='$text_id' data-status='$objTexto[text_status]' data-fecha-final='$fecha_final'></i>";
                     
                     echo  "
                        <tr>
                           <td class='align-middle'>$objTexto[cli_nom_empresa]</td>
                           <td class='align-middle td_fecha_inicial'>$fecha_inicial</td>
                           <td class='align-middle td_fecha_final'>$fecha_final</td>
                           $td_texto
                           <td class='align-middle'>$tipo_publicidad</td>
                           <td class='align-middle'>$activo</td>
                           <td class='align-middle'>
                              <button class='btn btn-primary btn_editar' data-bs-toggle='modal' data-bs-target='#modal' data-id='$objTexto[text_id]'><i class='fa-solid fa-pen-to-square fa-lg'></i></button>
                              <span class='mx-2'></span>
                              <button class='btn btn-danger btn_eliminar' data-id='$objTexto[text_id]' data-nombre='$objTexto[cli_nom_empresa]'><i class='fa-solid fa-trash-can'></i></button>
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
                     <th>Texo</th>
                     <th>Tipo publicidad</th>
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
                  <h5 class="modal-title fw-bold" id="modalLabel"><i class="fa-solid fa-terminal"></i>&nbsp; AGREGAR TEXTO</h5>
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
                     <div class="mb-3">
                        <label for="input_texto" class="form-label">Texto cintilla:</label>
                        <textarea class="form-control" id="input_texto" rows="2"></textarea>
                     </div>
                     <div class="row mb-3 g-3 align-items-center">
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
                     <div class="mb-3">
                        <label for="input_tipo" class="form-label">Tipo publicidad:</label>
                        <select class="form-control" style="width:100%" aria-label="Default select example" id="input_tipo" name="input_tipo">
                           <option value="-1">Selecciona una opción</option>
                           <option value="1">Del día</option>
                           <option value="2">Promoción</option>
                        </select>
                     </div>
                     <div id="seccion_promocional">
                        <div class="h6">Configuraciones de texto promocional</div>
                        <div class="form-group">
                           <div class="row">
                              <div class="col-md-6 mb-3">
                                 <label for="input_hora_inicial" class="form-label">Hora inicial:</label>
                                 <input class="form-control" type="time" id="input_hora_inicial" name="input_hora_inicial">
                              </div>
                              <div class="col-md-6 mb-3">
                                 <label for="input_hora_final" class="form-label">Hora final:</label>
                                 <input class="form-control" type="time" id="input_hora_final" name="input_hora_final">
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-6 mb-3">
                              <label for="input_color_texto" class="form-label">Color del texto en cintilla:</label>
                              <input class="form-control" type="color" id="input_color_texto" name="input_color_texto">
                           </div>
                           <div class="col-md-6 mb-3">
                              <label for="input_color_fondo" class="form-label">Color de fondo en cintilla :</label>
                              <input class="form-control" type="color" id="input_color_fondo" name="input_color_fondo">
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
<script src="../Scripts/texto.js"></script>