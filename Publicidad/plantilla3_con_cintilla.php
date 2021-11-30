<?php
require_once "../Admin/Templates/Plantillas/header_plantillas.php";
$id_negocio = $_GET['negocio'];
?>
<input type="hidden" id="id_negocio" value="<?php echo $id_negocio; ?>">
<div class="contenedor_general_plantilla3" oncontextmenu="return false" onmousedown="return false" onmouseup="return false" onselect="return false" onselectstart="return false" onmouseover="return false" onmouseout="return false">
<div class="seccion_arriba">
      <div class="contenedor_video" id="contenedor_video">
         <video src="" alt="Video" muted hidden></video>
      </div>
      <div class="contenedor_banvertical" id="contenedor_banvertical">
         <img src="" alt="Banner vertical" id="img_banvertical">
      </div>
   </div>
    <div class="seccion_abajo">
      <div class="contenedor_banhorizontal" id="contenedor_banhorizontal">
         <img src="" alt="Banner horizontal" id="img_banhorizontal">
      </div>
      <div class="contenedor_texto" id="contenedor_texto">
         <div id="texto_marquee" class="marquee h2 fw-bold"></div>
      </div>
   </div>
</div>

<?php
require_once "../Admin/Templates/Plantillas/footer_plantillas.php";
?>

<!-- SCRIPTS -->
<!-- Marquee JQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.Marquee/1.6.0/jquery.marquee.min.js" integrity="sha512-JHJv/L48s1Hod24iSI0u9bcF/JlUi+YaxliKdbasnw/U1Lp9xxWkaZ3O5OuQPMkVwOVXeFkF4n4176ouA6Py3A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- <script src="../Admin/Scripts/plantillas.js"></script> -->