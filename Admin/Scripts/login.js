$("#btn_iniciar_sesion").click((e) => {
   e.preventDefault();
   let usuario = $("#usuario");
   let contrasenia = $("#contrasenia");
   let accion = $("#accion");

   validar_campo = validarInput(usuario,"USUARIO");
   if (!validar_campo) return;
   valida_campo = validarInput(contrasenia,"CONTRASEÑA");
   if (!valida_campo) return;
   
   datos = {
      accion: accion.val(),
      usuario: usuario.val(),
      contrasenia: contrasenia.val()
   }
   peticionAjax("Admin/Models/Usuario/App.php",datos);
});

function peticionAjax(url,datos) {
   $.ajax({
      url,
      type: "POST",
      data: datos,
      dataType: "json",
      success: (respuesta) => {
         if (respuesta.Resultado == "correcto") {
            Swal.fire({
               icon: respuesta.Icono_alerta,
               title: respuesta.Titulo_alerta,
               text: `${respuesta.Mensaje_alerta}`,
               showConfirmButton: false,
               timer: 2000
            }).then(() => {
               $("#formulario_login")[0].reset();
               window.location.href = "Admin/index.php"
            });
         } else {
            Swal.fire({
               icon: respuesta.Icono_alerta,
               title: respuesta.Titulo_alerta,
               text: `${respuesta.Mensaje_alerta}`,
               showConfirmButton: true,
               confirmButtonColor: '#494E53'
            }).then(() => {
               if (respuesta.Mensaje_alerta == "El usuario no cuenta con los privilegios para acceder.") {
                  $("#usuario").focus();
               }
            });                        
         }
      }
   })
}

function validarInput(input, nombre_campo) {
   if (input.val() == "") {
       mostrarToast('error', `Campo ${nombre_campo} vacio.`);
       input.focus();
       return false;
   }
   return true;
}

function mostrarToast(icono, mensaje) {
   const Toast = Swal.mixin({
       toast: true,
       position: 'top-end',
       showConfirmButton: false,
       timer: 2000,
       timerProgressBar: true,
       didOpen: (toast) => {
           toast.addEventListener('mouseenter', Swal.stopTimer)
           toast.addEventListener('mouseleave', Swal.resumeTimer)
       }
   })

   Toast.fire({
       icon: icono,
       title: mensaje
   })
}
