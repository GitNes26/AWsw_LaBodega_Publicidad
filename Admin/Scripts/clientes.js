$(document).ready(() => {
   var tabla_clientes = $('#tabla_clientes').DataTable({
      "responsive": true,
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
const url_modelo_app = "../Models/Cliente/App.php";
const
   btn_abrir_modal = $("#btn_abrir_modal"),
   btns_editar = document.querySelectorAll(".btn_editar"),
   btns_eliminar = document.querySelectorAll(".btn_eliminar"),
   modal_title = $(".modal-title"),
   formulario_modal = $("#formulario_modal"),
   accion_modal = $("#accion"),
   input_nombre = $("#input_nombre"),
   input_activo = $("#input_activo"),
   label_input_activo = $("#label_input_activo"),
   btn_enviar_formulario = $("#btn_enviar_formulario"),
   btn_reset_formulario = $("#btn_reset_formulario")
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

//ABRIR MODAL PARA REGISTRAR UN NEGOCIO
btn_abrir_modal.click((e) => {
   e.preventDefault();
   formulario_modal[0].reset();
   modal_title.html("<i class='fa-solid fa-kaaba'></i>&nbsp; REGISTRAR NEGOCIO");
   btn_enviar_formulario.text("AGREGAR");
   setTimeout(() => {
      input_nombre.focus();
   }, 500);
})

//BOTON RESETEAR FORMIÑARIO
btn_reset_formulario.click(() => {
   estado = input_activo.prop("checked",true);
   input_activo.click();
   input_activo.click();
})

// ESTADO ACTIVO E INACTIVO
input_activo.click(() => {
   estado = input_activo.prop("checked")
   if (estado) { label_input_activo.text("Activo"); input_activo.attr("data-activo",1); }
   else { label_input_activo.text("Inactivo"); input_activo.attr("data-activo",0); }
});

// REGISTRAR CLIENTE
btn_enviar_formulario.click((e) => {
   e.preventDefault();
   validar_campo = validarInput(input_nombre,"NOMBRE DE LA EMPRESA");
   if (!validar_campo) return;

   if (btn_enviar_formulario.text() == "AGREGAR")
      registrarCliente();
   else if (btn_enviar_formulario.text() == "GUARDAR")
      editarCliente();
});
function registrarCliente() {
   let activo = input_activo.attr("data-activo");
   let nombre = input_nombre.val();
   if (activo == 1) { activo = 1; }
   else { activo = 0; }

   datos = {
      accion: "crear_cliente",
      input_nombre: nombre,
      input_activo: activo,
   }
   peticionAjax(url_modelo_app, datos, clienteRegistradoEditado, "sin funcion complete");
}

function clienteRegistradoEditado(ajaxResponse) {
   mostrarAlertaEditableConRefreshAlFinal(ajaxResponse,null,null,null,null,true);
   // formulario_modal[0].reset();
   $(".btn-close").click();
}

//ABRIR MODAL DE CLIENTE PARA EDITAR
btns_editar.forEach((btn_editar) => {
   btn_editar.addEventListener('click',() => {
      modal_title.html("<i class='fa-solid fa-kaaba'></i>&nbsp; EDITAR NEGOCIO");
      btn_enviar_formulario.text("GUARDAR");
      let accion = "mostrar_cliente";
      let id = Number(btn_editar.getAttribute('data-id'));
      datos = {
         accion,
         id
      }
      btn_enviar_formulario.attr("data-id",btn_editar.getAttribute("data-id"));
      peticionAjax(url_modelo_app,datos,mostrarCliente,null);
   })
});
function mostrarCliente(ajaxResponse) {
   objResponse = ajaxResponse.Datos;
   input_nombre.val(objResponse.Nombre);
   input_activo.val(objResponse.Activo);
   if (objResponse.Activo == "1") {
      input_activo.prop("checked", true);
      input_activo.click()
      input_activo.click()
   } else {
      input_activo.prop("checked", false);
      input_activo.click()
      input_activo.click()
   }
}
function editarCliente() {
   let datos = {
      accion: "editar_cliente",
      id: Number(btn_enviar_formulario.attr("data-id")),
      input_nombre: input_nombre.val(),
      input_activo: input_activo.attr("data-activo")
   }
   peticionAjax(url_modelo_app,datos,clienteRegistradoEditado,"sin funcion complete");
}

//ELIMINAR USUARIO
btns_eliminar.forEach((btn_eliminar) => {
   btn_eliminar.addEventListener('click', () => {
      let titulo = "¿Estás seguro de eliminar a";
      let texto = `${btn_eliminar.getAttribute("data-nombre")} ?`;
      let datos = {
         accion: "eliminar_cliente",
         id: Number(btn_eliminar.getAttribute("data-id"))
      }
      mostrarAlertaConOpciones(null,titulo,texto,datos);
   })
});
