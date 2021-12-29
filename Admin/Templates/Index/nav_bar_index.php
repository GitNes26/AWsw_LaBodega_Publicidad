<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
   <!-- Left navbar links -->
   <ul class="navbar-nav">
      <li class="nav-item">
         <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
   </ul>

   <!-- Right navbar links -->
   <ul class="navbar-nav ml-auto">
      <!-- Perfil -->
      <li class='nav-item'>
         <div class='user-panel d-flex'>
            <div class='info'>
               <span class='d-block text-decoration-none text-dark text-bold'><i class='text-dark'><?php echo $_COOKIE["bodega_usuario"] ?></i></span>
            </div>
         </div>
      </li>
      <!-- Btn Logout -->
      <li class='nav-item ml-3'>
         <a href='#' id="btn_cerrar_sesion" class='btn btn-outline-danger btn_cerrar_sesion' title='Cerrar sesión'><i class="fas fa-door-closed"></i></a>
      </li>
   </ul>
</nav>
<!-- /.navbar -->