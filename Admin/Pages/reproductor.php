<?php
require_once "../Templates/header.php";
require_once "../Templates/nav_bar.php";
require_once "../Templates/side_bar.php";

include '../Models/Reproductor/Reproductor.php';
$Reproductor = new Reproductor();
?>
<!-- Content Wrapper. Contenido de la pagina -->
<div class="content-wrapper text-sm">
   <!-- Content Header (Encabezado en el contenido de la pagina) -->
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>REPRODUCTOR</h1>
            </div>
         </div>
      </div><!-- /.container-fluid -->
   </section>

   <!-- Main content -->
   <section class="content">

      <!-- card -->
      <div class="card card-outline card-dark shadow">
         <div class="card-body">
            <!-- tabla -->
            <table id="tabla_reproductores" class="table text-center" style="width:100%">
               <thead class="thead-dark">
                  <tr>
                     <th>Negocio</th>
                     <th>Plantilla 1</th>
                     <th>Plantilla 2</th>
                     <th>Plantilla 3</th>
                  </tr>
               </thead>
               <tbody>
                  <?php
                  error_reporting(0);
                  foreach ($Reproductor->mostrarReproductores() as $objReproductor) {
                     $cli_id = $objReproductor['cli_id'];
                     $empresa = $objReproductor['cli_nom_empresa'];
                     
                     echo  "
                        <tr>
                           <td class='align-middle'>$objReproductor[cli_nom_empresa]</td>

                           <td class='align-middle'><a href='../../p1.php?n=$cli_id' target='_blank' class='btn_play_plantilla1' title='Reproducir plantilla 1 de $empresa'><i class='fa-solid fa-circle-play fa-3x'></i></a></td>

                           <td class='align-middle'><a href='../../p2.php?n=$cli_id' target='_blank' class='btn_play_plantilla2' title='Reproducir plantilla 2 de $empresa'><i class='fa-solid fa-circle-play fa-3x'></i></a></td>

                           <td class='align-middle'><a href='../../p3.php?n=$cli_id' target='_blank' class='btn_play_plantilla4' title='Reproducir plantilla 4 de $empresa'><i class='fa-solid fa-circle-play fa-3x'></i></a></td>
                        </tr>
                     ";
                  }
                  ?>
               </tbody>
               <tfoot>
                  <tr class="thead-dark">
                     <th>Negocio</th>
                     <th>Plantilla 1</th>
                     <th>Plantilla 2</th>
                     <th>Plantilla 3</th>
                  </tr>
               </tfoot>
            </table>
         </div>
         <!-- /.card-body -->
      </div>
      <!-- /.card -->

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
<script src="../Scripts/reproductor.js"></script>