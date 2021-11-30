<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
   <!-- Brand Logo -->
   <a href="../" class="brand-link">
      <img src="../../favicon.png" alt="Logo" class="brand-image img-fluid" style="opacity: .8">
   </a>

   <?php
   //ASIGNACION DE PERMISOS
      $permiso_todos = false;
      $permiso_usuarios = false;
      $permiso_clientes = false;
      $permiso_videos = false;
      $permiso_banvertical = false;
      $permiso_banhorizontal = false;
      $permiso_bancompleto = false;
      $permiso_texto = false;
      $permiso_reproductor = false;
      $permiso_plantilla1 = false;
      $permiso_plantilla2 = false;
      $permiso_plantilla3 = false;
      if (strpos($_COOKIE['permisos'], 'todos') !== false) {
         $permiso_todos = true;
      }
      if (strpos($_COOKIE['permisos'], 'usuarios') !== false) {
         $permiso_usuarios = true;
      }
      if (strpos($_COOKIE['permisos'], 'clientes') !== false) {
         $permiso_clientes = true;
      }
      if (strpos($_COOKIE['permisos'], 'videos') !== false) {
         $permiso_videos = true;
      }
      if (strpos($_COOKIE['permisos'], 'banvertical') !== false) {
         $permiso_banvertical = true;
      }
      if (strpos($_COOKIE['permisos'], 'banhorizontal') !== false) {
         $permiso_banhorizontal = true;
      }
      if (strpos($_COOKIE['permisos'], 'bancompleto') !== false) {
         $permiso_bancompleto = true;
      }
      if (strpos($_COOKIE['permisos'], 'texto') !== false) {
         $permiso_texto = true;
      }
      if (strpos($_COOKIE['permisos'], 'reproductor') !== false) {
         $permiso_reproductor = true;
      }
      if (strpos($_COOKIE['permisos'], 'plantilla1') !== false) {
         $permiso_plantilla1 = true;
      }
      if (strpos($_COOKIE['permisos'], 'plantilla2') !== false) {
         $permiso_plantilla2 = true;
      }
      if (strpos($_COOKIE['permisos'], 'plantilla3') !== false) {
         $permiso_plantilla3 = true;
      }
   ?>

   <!-- Sidebar -->
   <div class="sidebar">

      <!-- Sidebar Menu -->
      <nav class="mt-2">
         <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- CATÁLOGOS -->
            <?php if ($permiso_todos || $permiso_usuarios || $permiso_clientes) {?>
            <li class="nav-item">
               <a href="#" class="nav-link">
                  <i class="nav-icon fa-solid fa-folder-tree"></i>
                  <p>
                     Catálogos
                     <i class="right fas fa-angle-left"></i>
                  </p>
               </a>
               <ul class="nav nav-treeview text-sm">
                  <?php if ($permiso_todos || $permiso_usuarios) {?>
                  <li class="nav-item">
                     <a href="./usuarios.php" class="nav-link">
                        <i class="far fa-circle nav-icon text-sm"></i>
                        <p>Usuarios</p>
                     </a>
                  </li>
                  <?php } ?>
                  <?php if ($permiso_todos || $permiso_clientes) {?>
                  <li class="nav-item">
                     <a href="./clientes.php" class="nav-link">
                        <i class="far fa-circle nav-icon text-sm"></i>
                        <p>Ubicaciones</p>
                     </a>
                  </li>
                  <?php } ?>
               </ul>
            </li>
            <?php } ?>

            <!-- PANEL -->
            <?php if ($permiso_todos || $permiso_videos || $permiso_banvertical || $permiso_banhorizontal || $permiso_bancompleto || $permiso_texto) {?>
            <li class="nav-item">
               <a href="#" class="nav-link">
                  <i class=" nav-icon fa-solid fa-photo-film"></i>
                  <p>
                     Panel
                     <i class="right fas fa-angle-left"></i>
                  </p>
               </a>
               <ul class="nav nav-treeview text-sm">
                  <?php if ($permiso_todos || $permiso_videos) {?>
                  <li class="nav-item">
                     <a href="./videos.php" class="nav-link">
                        <i class="far fa-circle nav-icon text-sm"></i>
                        <p>Video Principal</p>
                     </a>
                  </li>
                  <?php } ?>
                  <?php if ($permiso_todos || $permiso_banvertical) {?>
                  <li class="nav-item">
                     <a href="./banvertical.php" class="nav-link">
                        <i class="far fa-circle nav-icon text-sm"></i>
                        <p>Banner Vertical</p>
                     </a>
                  </li>
                  <?php } ?>
                  <?php if ($permiso_todos || $permiso_banhorizontal) {?>
                  <li class="nav-item">
                     <a href="./banhorizontal.php" class="nav-link">
                        <i class="far fa-circle nav-icon text-sm"></i>
                        <p>Banner Horizontal</p>
                     </a>
                  </li>
                  <?php } ?>
                  <?php if ($permiso_todos || $permiso_bancompleto) {?>
                  <li class="nav-item">
                     <a href="./bancompleto.php" class="nav-link">
                        <i class="far fa-circle nav-icon text-sm"></i>
                        <p>Banner Completo</p>
                     </a>
                  </li>
                  <?php } ?>
                  <?php if ($permiso_todos || $permiso_texto) {?>
                  <li class="nav-item">
                     <a href="./texto.php" class="nav-link">
                        <i class="far fa-circle nav-icon text-sm"></i>
                        <p>Texto Cintilla</p>
                     </a>
                  </li>
                  <?php } ?>
               </ul>
            </li>
            <?php } ?>

            <!-- REPRODUCTOR -->
            <?php if ($permiso_todos || $permiso_reproductor) {?>
            <li class="nav-item">
               <a href="#" class="nav-link">
                  <i class="nav-icon fa-solid fa-video"></i>
                  <p>
                     Reproductor
                     <i class="right fas fa-angle-left"></i>
                  </p>
               </a>
               <ul class="nav nav-treeview text-sm">
                  <li class="nav-item">
                     <a href="./reproductor.php" class="nav-link">
                        <i class="far fa-circle nav-icon text-sm"></i>
                        <p>Reproductor</p>
                     </a>
                  </li>
               </ul>
            </li>
            <?php } ?>
         </ul>
      </nav>
      <!-- /.sidebar-menu -->
   </div>
   <!-- /.sidebar -->
</aside>