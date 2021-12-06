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
               <h1>USUARIOS</h1>
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
            <button id="btn_modal_usuario" class="float-end btn btn-success fw-bold" data-bs-toggle="modal" data-bs-target="#modal_usuario"><i class="fa-solid fa-circle-plus"></i>&nbsp; AGREGAR USUARIO</button>
         </div>
         <div class="card-body">
            <!-- tabla -->
            <table id="tabla_usuarios" class="table text-center" style="width:100%">
               <thead class="thead-dark">
                  <tr>
                     <th>Nombre</th>
                     <th>Contraseña</th>
                     <th>Editar / Eliminar</th>
                  </tr>
               </thead>
               <tbody>
                  <?php
                  include '../Models/Usuario/Usuario.php';
                  $Usuario = new Usuario();
                  error_reporting(0);
                  foreach ($Usuario->mostrarUsuarios() as $objUsuario) {
                     $usr_id = $objUsuario["usr_id"];
                     echo  "
                        <tr>
                           <td class='align-middle'>$objUsuario[usr_nombre]</td>
                           <td class='align-middle'>$objUsuario[usr_pass_nor]</td>
                           <td class='align-middle'>
                              <button class='btn btn-primary btn_editar_usuario mb-1' data-bs-toggle='modal' data-bs-target='#modal_usuario' data-id='$objUsuario[usr_id]'><i class='fa-solid fa-pen-to-square fa-lg'></i></button>
                              <span class='mx-md-2'></span>
                              <button class='btn btn-danger btn_eliminar_usuario' data-id='$objUsuario[usr_id]' data-nombre='$objUsuario[usr_nombre]'><i class='fa-solid fa-trash-can'></i></button>
                           </td>
                        </tr>
                     ";
                  }
                  ?>
               </tbody>
               <tfoot>
                  <tr class="thead-dark">
                     <th>Nombre</th>
                     <th>Contraseña</th>
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

   <!-- Modal Usuario -->
   <div class="modal fade" id="modal_usuario" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title fw-bold" id="modalLabel"><i class="fa-solid fa-user-plus"></i>&nbsp; REGISTRAR USUARIO</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <form id="formulario_usuario" enctype="multipart/form-data">
                  <input type="hidden" id="accion" name="accion" value="crear_usuario">
                  <input type="hidden" name="id" name="id" value=''>
                  <div class="mb-3">
                     <label for="input_usuario" class="form-label">Nombre de usuario:</label>
                     <input type="text" class="form-control" id="input_usuario" name="input_usuario">
                  </div>
                  <div class="mb-3">
                     <label for="input_contrasenia" class="form-label">Contraseña:</label>
                     <input type="password" class="form-control" id="input_contrasenia" name="input_contrasenia">
                  </div>
                  <div class="h4">Permisos:</div>
                  <div class="form-group" id="seccion_permisos">
                     <div class="form-check fw-bold">
                        <input class="form-check-input" type="checkbox" value="todos" name="input_permisos_todos" id="input_permisos_todos">
                        <label class="form-check-label" for="input_permisos_todos">
                           Marcar Todos
                        </label>
                     </div>
                     <div class="container">
                        <table class="table table-sm table-borderless">
                           <thead>
                              <tr>
                                 <th>Catálogo</th>
                                 <th>Panel</th>
                                 <th>Reproductor</th>
                              </tr>
                           </thead>
                           <tbody>
                              <tr>
                                 <td>
                                    <div class="form-check">
                                       <input class="form-check-input permisos-todos" type="checkbox" value="usuarios" name="input_permisos[]" id="input_permiso_usuarios">
                                       <label class="form-check-label" for="input_permiso_usuarios">
                                          Usuarios
                                       </label>
                                    </div>
                                    <div class="form-check">
                                       <input class="form-check-input permisos-todos" type="checkbox" value="clientes" name="input_permisos[]" id="input_permiso_clientes">
                                       <label class="form-check-label" for="input_permiso_clientes">
                                          Ubicaciones
                                       </label>
                                    </div>
                                 </td>
                                 <td>
                                    <div class="form-check">
                                       <input class="form-check-input permisos-todos" type="checkbox" value="videos" name="input_permisos[]" id="input_permiso_videos">
                                       <label class="form-check-label" for="input_permiso_videos">
                                          Videos
                                       </label>
                                    </div>
                                    <div class="form-check">
                                       <input class="form-check-input permisos-todos" type="checkbox" value="banvertical" name="input_permisos[]" id="input_permiso_banvertical">
                                       <label class="form-check-label" for="input_permiso_banvertical">
                                          Banner vertical
                                       </label>
                                    </div>
                                    <div class="form-check">
                                       <input class="form-check-input permisos-todos" type="checkbox" value="banhorizontal" name="input_permisos[]" id="input_permiso_banhorizontal">
                                       <label class="form-check-label" for="input_permiso_banhorizontal">
                                          Banner horizontal
                                       </label>
                                    </div>
                                    <div class="form-check">
                                       <input class="form-check-input permisos-todos" type="checkbox" value="bancompleto" name="input_permisos[]" id="input_permiso_bancompleto">
                                       <label class="form-check-label" for="input_permiso_bancompleto">
                                          Banner completo
                                       </label>
                                    </div>
                                    <div class="form-check">
                                       <input class="form-check-input permisos-todos" type="checkbox" value="texto" name="input_permisos[]" id="input_permiso_texto">
                                       <label class="form-check-label" for="input_permiso_texto">
                                          Texto
                                       </label>
                                    </div>
                                 </td>
                                 <td>
                                    <div class="form-check">
                                       <input class="form-check-input permisos-todos" type="checkbox" value="reproductor" name="input_permisos[]" id="input_permiso_reproductor">
                                       <label class="form-check-label" for="input_permiso_reproductor">
                                          Reproductor
                                       </label>
                                    </div>
                                    <div class="form-check">
                                       <input class="form-check-input permisos-todos" type="checkbox" value="plantilla1" name="input_permisos[]" id="input_permiso_plantilla1">
                                       <label class="form-check-label" for="input_permiso_plantilla1">
                                          Plantilla 1
                                       </label>
                                    </div>
                                    <div class="form-check">
                                       <input class="form-check-input permisos-todos" type="checkbox" value="plantilla2" name="input_permisos[]" id="input_permiso_plantilla2">
                                       <label class="form-check-label" for="input_permiso_plantilla2">
                                          Plantilla 2
                                       </label>
                                    </div>
                                    <div class="form-check">
                                       <input class="form-check-input permisos-todos" type="checkbox" value="plantilla3" name="input_permisos[]" id="input_permiso_plantilla3">
                                       <label class="form-check-label" for="input_permiso_plantilla3">
                                          Plantilla 3
                                       </label>
                                    </div>
                                 </td>
                              </tr>
                           </tbody>
                        </table>
                     </div>
                  </div>
            </div>
            <div class="modal-footer">
               <button type="submit" id="btn_registrar_usuario" class="btn btn-success fw-bold">AGREGAR</button>
               <button type="reset" class="btn btn-secondary">Limpiar todo</button>
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
<script src="../Scripts/usuarios.js"></script>