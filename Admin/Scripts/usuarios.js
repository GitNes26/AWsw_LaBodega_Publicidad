$(document).ready(() => {
   var tabla_usuarios = $('#tabla_usuarios').DataTable({
      "responsive": {
         details: {
            type: 'column'
         }
      },
      // scrollX: true,
      language: {
         "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/es-mx.json"
      },
      columnDefs: [
         {
            "className": "dt-center",
            "targets": "_all"
         }
      ],

      // dom: 'lfrtip',
      dom: '<"row"<"col-md-6 "l> <"col-md-6"f> > rt <"row"<"col-md-6 "i> <"col-md-6"p> >',
      // "lengthChange": true,
      "lengthMenu": [
         [
            5,
            10,
            50,
            100,
            -1
         ],
         [
            5,
            10,
            50,
            100,
            "Todos"
         ]
      ],
      "pageLength": 5,
      "order": [
         [0, 'asc']
      ],
      // buttons: [
      //     {
      //         extend: 'excel', title: 'Ventas Registradas (Relacionadas al arduino)',
      //         //exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11] }
      //     },
      //     {
      //         extend: 'pdf', title: 'Ventas Registradas (Relacionadas al arduino)',
      //         //exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11] }
      //     },
      //     {
      //         extend: 'print', title: 'Ventas Registradas (Relacionadas al arduino)',
      //         //exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11] }
      //     },
      // ],
      "deferRender": true

   });

});

const url_modelo_app = "../Models/Usuario/App.php";
const
   btn_modal_usuario = $("#btn_modal_usuario"),
   btn_registrar_usuario = $("#btn_registrar_usuario"),
   btns_editar_usuario = document.querySelectorAll(".btn_editar_usuario"),
   btns_eliminar_usuario = document.querySelectorAll(".btn_eliminar_usuario"),
   formulario_usuario = $("#formulario_usuario"),
   modal_title = $(".modal-title"),
   accion_modal = $("#accion"),
   input_usuario = $("#input_usuario"),
   input_contrasenia = $("#input_contrasenia"),
   seccion_permisos = $("#seccion_permisos"),
   input_permisos_todos = $("#input_permisos_todos"),
   input_permiso_usuarios = $("#input_permiso_usuarios"),
   input_permiso_clientes = $("#input_permiso_clientes"),
   input_permiso_videos = $("#input_permiso_videos"),
   input_permiso_banvertical = $("#input_permiso_banvertical"),
   input_permiso_banhorizontal = $("#input_permiso_banhorizontal"),
   input_permiso_texto = $("#input_permiso_texto"),
   input_permiso_reproductor = $("#input_permiso_reproductor"),
   input_permiso_plantilla1 = $("#input_permiso_plantilla1"),
   input_permiso_plantilla2 = $("#input_permiso_plantilla2"),
   input_permiso_plantilla3 = $("#input_permiso_plantilla3"),
   todos_los_permisos = document.querySelectorAll(".permisos-todos")
   ;

/* --- FUNCIONES DE CAJON--- */
function peticionAjax(url, datos, funcion_en_success, funcion_en_complete) {
   if (datos == null) {
      datos = {};
   }
   $.ajax({
      url,
      type: "POST",
      data: datos,
      dataType: "json",
      beforeSend: () => {
         mostrarBlockOutCargando();
      },
      success: (ajaxResponse) => {
         if (ajaxResponse.Resultado == "correcto") {
            if (funcion_en_success == null) 
               return;
            
            funcion_en_success(ajaxResponse);
         } else if (ajaxResponse.Resultado == "incorrecto") {
            Swal.fire({
               icon: "error",
               title: "Oops...!",
               text: `${
                  ajaxResponse.Mensaje_alerta
               }`,
               showConfirmButton: true,
               confirmButtonColor: '#494E53'
            })
         }
      },
      error: (error) => { // console.error(error.ajaxResponseText)
         Swal.fire({
            icon: "error",
            title: "Oops...!",
            text: `Hubo un error, verifica tus datos e intenta de nuevo.`,
            showConfirmButton: true,
            confirmButtonColor: '#494E53'
         })
      },
      complete: () => {
         if (funcion_en_complete == null) 
            mostrarBlockOutListo();
          else if (funcion_en_complete == "sin funcion complete") 
            return;
          else 
            funcion_en_complete();
         

      }
   })
}

function mostrarAlertaEditableConRefreshAlFinal(ajaxResponse, icono, titulo, texto, btn_aceptar, refresh) {
   if (ajaxResponse != null) {
      icono = ajaxResponse.Icono_alerta;
      titulo = ajaxResponse.Titulo_alerta;
      texto = ajaxResponse.Mensaje_alerta;
      btn_aceptar = ajaxResponse.DataBool;
   }
   if (refresh) {
      if (btn_aceptar) {
         Swal.fire({
            icon: icono,
            title: titulo,
            html: texto,
            showConfirmButton: true,
            confirmButtonColor: '#494E53'
         }).then(() => {
            window.location.reload();

         })
      } else {
         Swal.fire({
            icon: icono,
            title: titulo,
            html: texto,
            timer: 1500,
            showConfirmButton: false
         }).then(() => {
            window.location.reload();

         })
      }
   } else {
      if (btn_aceptar) {
         Swal.fire({
            icon: icono,
            title: titulo,
            html: texto,
            showConfirmButton: true,
            confirmButtonColor: '#494E53'
         }).then(() => {
            window.location.reload();
   
         })
      } else {
         Swal.fire({
            icon: icono,
            title: titulo,
            html: texto,
            timer: 1500,
            showConfirmButton: false
         })
      }
   }


}
function mostrarAlertaConOpciones(ajaxResponse, titulo, texto, datos) {
   if (ajaxResponse != null) {}
   Swal.fire({
      title: titulo,
      text: texto,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      confirmButtonText: 'Eliminar',
      cancelButtonColor: '#d33',
      cancelButtonText: 'Cancelar',
   }).then((result) => {
      if (result.isConfirmed) {
         $.ajax({
            url: url_modelo_app,
            type: "POST",
            data: datos,
            dataType: "json",
            success: () => {
               Swal.fire({
                  icon: 'success',
                  title:'Eliminado!',
                  showConfirmButton: false,
                  timer: 1500
               }).then(() => {
                  window.location.reload();
               })
            }
         });
      }
   })
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

   Toast.fire({icon: icono, title: mensaje})
}
function mostrarBlockOutCargando() {
   Swal.fire({
      title: 'Cargando...',
      // html: 'I will close in <b></b> milliseconds.',
      allowEscapeKey: false,
      allowOutsideClick: false,
      didOpen: () => {
         Swal.showLoading()
      }
   })
}
function mostrarBlockOutListo() {
   Swal.fire({
      title: "LISTO!",
      timer: 500,
      allowEscapeKey: false,
      allowOutsideClick: false,
      didOpen: () => {
         Swal.showLoading()
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

//ABRIR MODAL PARA REGISTRAR UN USUARIO
btn_modal_usuario.click((e) => {
   e.preventDefault();
   formulario_usuario[0].reset();
   modal_title.html("<i class='fa-solid fa-user-plus'></i>&nbsp; REGISTRAR USUARIO");
   btn_registrar_usuario.text("AGREGAR");
   setTimeout(() => {
      input_usuario.focus();
   }, 500);
})

// ACTIVAR Y DESACTIVAR TODOS LOS PERMISOS
input_permisos_todos.click(() => {
   estado = input_permisos_todos.prop("checked")
   $(".permisos-todos").prop("checked", estado);
});

//TODOS LOS PERMISOS ACTIVOS O NO?
seccion_permisos.change(() => {
   todos_activos = true;
   todos_los_permisos.forEach(permiso => {
      if (!permiso.checked) { todos_activos = false; }
   })
   console.log(todos_activos);
   if (todos_activos) { input_permisos_todos.prop("checked", true); }
   else { input_permisos_todos.prop("checked", false); }
})

// REGISTRAR USUARIO
btn_registrar_usuario.click((e) => {
   e.preventDefault();
   validar_campo = validarInput(input_usuario,"NOMBRE DE USUARIO");
   if (!validar_campo) return;
   validar_campo = validarInput(input_contrasenia,"CONTRASEÑA");
   if (!validar_campo) return;

   if (btn_registrar_usuario.text() == "AGREGAR")
      registrarUsuario();
   else if (btn_registrar_usuario.text() == "GUARDAR")
      editarUsuario();
});
function registrarUsuario() {
   let datos = formulario_usuario.serializeArray();
   datos.forEach(dato => {
      if (dato.name == "accion") {
         dato.value = "crear_usuario";
      }
   })
   peticionAjax(url_modelo_app,datos,usuarioRegistradoEditado,"sin funcion complete");
}
function usuarioRegistradoEditado(ajaxResponse) {
   mostrarAlertaEditableConRefreshAlFinal(ajaxResponse,null,null,null,null,true);
   formulario_usuario[0].reset();
   $(".btn-close").click();
}

//ABRIR MODAL DE USUARIO PARA EDITAR
btns_editar_usuario.forEach((btn_editar_usuario) => {
   btn_editar_usuario.addEventListener('click',() => {
      modal_title.html("<i class='fa-solid fa-pen-to-square'></i>&nbsp; EDITAR USUARIO");
      btn_registrar_usuario.text("GUARDAR");
      let accion = "mostrar_usuario";
      let id = Number(btn_editar_usuario.getAttribute('data-id'));
      datos = {
         accion,
         id
      }
      btn_registrar_usuario.attr("data-id",btn_editar_usuario.getAttribute("data-id"));
      peticionAjax(url_modelo_app,datos,mostrarUsuario,null);
   })
});
function permisosAsignados(permiso) {
   if (permiso == "usuarios") {
      input_permiso_usuarios.prop("checked", true);
   }
   if (permiso == "clientes") {
      input_permiso_clientes.prop("checked", true);
   }
   if (permiso == "videos") {
      input_permiso_videos.prop("checked", true);
   }
   if (permiso == "banvertical") {
      input_permiso_banvertical.prop("checked", true);
   }
   if (permiso == "banhorizontal") {
      input_permiso_banhorizontal.prop("checked", true);
   }
   if (permiso == "bancompleto") {
      input_permiso_bancompleto.prop("checked", true);
   }
   if (permiso == "texto") {
      input_permiso_texto.prop("checked", true);
   }
   if (permiso == "reproductor") {
      input_permiso_reproductor.prop("checked", true);
   }
   if (permiso == "plantilla1") {
      input_permiso_plantilla1.prop("checked", true);
   }
   if (permiso == "plantilla2") {
      input_permiso_plantilla2.prop("checked", true);
   }
   if (permiso == "plantilla3") {
      input_permiso_plantilla3.prop("checked", true);
   }
}
function mostrarUsuario(ajaxResponse) {
   objResponse = ajaxResponse.Datos;
   input_usuario.val(objResponse.Nombre);
   input_contrasenia.val(objResponse.Contrasenia);
   if (objResponse.Permisos == "todos") {
      input_permisos_todos.click();
   } else {
      let permisos = objResponse.Permisos.split("@");
      permisos.forEach(permiso => {
         permisosAsignados(permiso)
      });
   }
}
function editarUsuario() {
   let datos = formulario_usuario.serializeArray();
   datos.forEach(dato => {
      if (dato.name == "accion")
         dato.value = "editar_usuario";
      if (dato.name == "id")
         dato.value = Number(btn_registrar_usuario.attr("data-id"));
   })
   peticionAjax(url_modelo_app,datos,usuarioRegistradoEditado,"sin funcion complete");
}

//ELIMINAR USUARIO
btns_eliminar_usuario.forEach((btn_eliminar_usuario) => {
   btn_eliminar_usuario.addEventListener('click', () => {
      let titulo = "¿Estás seguro de eliminar a";
      let texto = `${btn_eliminar_usuario.getAttribute("data-nombre")} ?`;
      let datos = {
         accion: "eliminar_usuario",
         id: Number(btn_eliminar_usuario.getAttribute("data-id"))
      }
      mostrarAlertaConOpciones(null,titulo,texto,datos);
   })
});
