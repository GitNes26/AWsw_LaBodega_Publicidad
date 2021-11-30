<?php
if (isset($_COOKIE["sesion"])) {
   if ($_COOKIE["sesion"] != "activa") {
      header("location:../index.php");
      die();
   }
} else {
   header("location:../index.php");
   die();
}

require_once "./Templates/Index/header_index.php";
require_once "./Templates/Index/nav_bar_index.php";
require_once "./Templates/Index/side_bar_index.php";
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper text-sm">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>Bienvenido <?php echo$_COOKIE['usuario'] ?></h1>
            </div>
         </div>
      </div><!-- /.container-fluid -->
   </section>

   <!-- Main content -->
   <section class="content">

      <!-- card -->
      <div class="card card-outline card-dark shadow">
         <div class="card-body">
            <div class="row">
               <?php 
               include './Models/Cliente/Cliente.php';
               error_reporting(0);
               $Cliente = new Cliente();
               foreach ($Cliente->mostrarClientesYCantidadesContenido() as $objCliente) {
                  echo "
                  <div class='col-4'>
                  <div class='card card-outline card-success'>
                     <div class='card-header'>
                        <h3 class='card-title fw-bold'>$objCliente[empresa]</h3>
                        <div class='card-tools'>
                           <button type='button' class='btn btn-tool' data-card-widget='collapse' title='Collapse'>
                              <i class='fas fa-minus'></i>
                           </button>
                        </div>
                     </div>
                     <div class='card-body'>
                        <ul class='list-group list-group-flush'>
                           <li class='list-group-item d-flex justify-content-between align-items-center'>
                              Videos activos
                              <span class='badge_videos_activos badge'>$objCliente[videos_activos]</span>
                           </li>
                           <li class='list-group-item d-flex justify-content-between align-items-center'>
                              Imágenes verticales activas
                              <span class='badge_banverticales_activos badge'>$objCliente[banverticales_activos]</span>
                           </li>
                           <li class='list-group-item d-flex justify-content-between align-items-center'>
                              Imágenes horizontales activas
                              <span class='badge_banhorizontales_activos badge'>$objCliente[banhorizontales_activos]</span>
                           </li>
                           <li class='list-group-item d-flex justify-content-between align-items-center'>
                              Imágenes completos activas
                              <span class='badge_bancompletos_activos badge'>$objCliente[bancompletos_activos]</span>
                           </li>
                           <li class='list-group-item d-flex justify-content-between align-items-center'>
                              Texto cintilla (del día) activas
                              <span class='badge_textos_activos badge'>$objCliente[textos_activos]</span>
                           </li>
                           <li class='list-group-item d-flex justify-content-between align-items-center'>
                              Texto de promoción en cintillo activa
                              <span class='badge_textos_promo_activos badge'>$objCliente[textos_promo_activos]</span>
                           </li>
                        </ul>
                        
                     </div>
                  </div>
               </div>
                  ";
               }
               ?>
            </div>
         </div>
         <!-- /.card-body -->
      </div>
      <!-- /.card -->

   </section>
   <!-- /.content -->
</div>
<!-- /.content-wrapper -->
</div>
<!-- ./wrapper -->

<?php
require_once './Templates/Index/footer_index.php';
?>
<script src="./Scripts/index_admin.js"></script>