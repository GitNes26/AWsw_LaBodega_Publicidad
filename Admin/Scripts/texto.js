var tabla_textos;
tbody = $("#tabla_textos").find('tbody');
tbody.attr("hidden", false);
tbody.fadeOut(1);

$(document).ready(() => {
   tabla_textos = $('#tabla_textos').DataTable({
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
      // dom: '<"row"<"col-md-6 "l> <"col-md-6"f> > rt <"row"<"col-md-6 "i> <"col-md-6"p> >',
      dom: '<"row"<"col-md-6 div_filtrar_cliente"> <"col-md-6"f> > rt <"row"<"col-md-6 "i> >',
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
      "pageLength": -1,
      // "order": [
      //    [0, 'asc']
      // ],
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
      "deferRender": true,
      aaSorting: [] //deshabilitar ordenado automatico
   });
   // Arrastrar renglón
   tbody.sortable({
      items: 'tr',
      axis: 'y',
      handle: '.handle',
      placeholder: 'ui-sortable-placeholder',
      start: function (event, ui) {
         ui.item.addClass('ui-sortable-item');

         ui.placeholder.height($(ui.item).height());
         ui.placeholder.width($(ui.item).width());

         // show all rows
         // tabla_textos.page.len(-1).draw(false);

         // refresh so that newly shown rows are counted as sortable items
         $(this).sortable('refresh');

         // sort table by sequence
         // tabla_textos.order([4, 'asc']).draw(false);
      },
      sort: function (event, ui) {
      },
      stop: function (event, ui) {
         enlistarOrden();
      }
   });
   // Arrastrar renglón

   $("#seccion_promocional").slideUp("fast");

   function formatearFechaHora(la_fecha) {
      // fecha = new Date(parseInt(la_fecha.substr(6)));
      fecha = new Date(la_fecha);

      if (la_fecha.length <= 10) {
         fecha = new Date(fecha.setDate(fecha.getDate()+1));
         return fecha_hora = new Intl.DateTimeFormat("es-MX", { day: '2-digit', month: '2-digit', year: 'numeric'}).format(fecha);
      }

      fecha = new Date(la_fecha);
      return fecha_hora = new Intl.DateTimeFormat("es-MX", { day: '2-digit', month: '2-digit', year: 'numeric', hour: "2-digit", minute: "2-digit", second: "2-digit", hour12: true }).format(fecha);
      
   }

   tds_fecha_inicial = document.querySelectorAll('.td_fecha_inicial');
   tds_fecha_inicial.forEach(td_fecha_inicial => {
      let fecha_formateada = formatearFechaHora(td_fecha_inicial.innerHTML);
      td_fecha_inicial.innerHTML = fecha_formateada;
   });
   tds_fecha_final = document.querySelectorAll('.td_fecha_final');
   tds_fecha_final.forEach(td_fecha_final => {
      let fecha_formateada = formatearFechaHora(td_fecha_final.innerHTML);
      td_fecha_final.innerHTML = fecha_formateada;
   });

   //RECARGAR Y ACTUALIZAR STATUS DE LOS REGISTROS SI VENCIO SU FECHA FINAL
   function peticionAjax(url, datos, funcion_en_success, funcion_en_complete, funcion_en_before) {
      if (datos == null) {
         datos = {};
      }
      $.ajax({
         url,
         type: "POST",
         data: datos,
         dataType: "json",
         beforeSend: () => {
            if (funcion_en_before == "EXPRESS") {return}
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
   const url_modelo_app = "../Models/Texto/App.php";
   tds_status = document.querySelectorAll(".td_status");
   function actualizarStatusRegistros() {
      let ids = "";
      por_actualizar = false;
      query = "UPDATE texto SET text_status='0', text_order=1000000 WHERE text_id IN (";
      tds_status.forEach(td_status => {
         let hoy = moment().format("YYYY-MM-DD HH:mm:ss");
         let momento_actual = moment(hoy)
         id_registro = Number(td_status.getAttribute("data-id"));
         status_td = Number(td_status.getAttribute("data-status"));
         fecha_final = moment(td_status.getAttribute("data-fecha-final"));
         if (td_status.getAttribute("data-fecha-final").length <= 11) {
            fecha_final = moment(fecha_final).format("YYYY-MM-DD 23:59:59");
            fecha_final = moment(fecha_final)
            let hoy = moment().format("YYYY-MM-DD 23:59:59");
            momento_actual = moment(hoy)
         }
         fecha_vencida = momento_actual.isAfter(fecha_final);
         if (fecha_vencida && status_td==1) {
            por_actualizar = true;
            query += `${id_registro},`;
            ids += id_registro+'.,'
         }
      });
      query = query.slice(0,-1);
      query += ");";
      ids = ids.slice(0,-1);
      let datos = {
         accion: "actualizar_status",
         ids,
         query
      }
      if (por_actualizar) { peticionAjax(url_modelo_app,datos,cambioDeEstado,null); }
      
   }
   function cambioDeEstado(ajaxResponse) {
      objResponse =  ajaxResponse.Datos;
      tds_status.forEach(td_status => {
         id_registro = td_status.getAttribute("data-id");
         comparacion = `${id_registro}.`;

         if (objResponse.includes(id_registro)) {
            td_status.setAttribute("data-status", 0);
            td_status.classList.remove("fa-circle-check");
            td_status.classList.add("fa-circle-xmark");

            // obtener su td_orden, quitarle su numero y poner su letra color muted
            var td_orden = $(`td[data-id='${id_registro}'].td_orden`);
            td_orden.attr("data-orden",1000000)
            td_orden.removeClass("handle");
            if (!td_orden.hasClass("text-muted")) {td_orden.addClass("text-muted");}
            td_orden.html("&nbsp;<i class='fa-solid fa-grip-vertical'></i>");
         }
      });
   }
   actualizarStatusRegistros();   
   //RECARGAR Y ACTUALIZAR STATUS DE LOS REGISTROS SI VENCIO SU FECHA FINAL
   
});

/*Select2*/
$.fn.select2.defaults.set('language', 'es');
$('.select2').select2()

const url_modelo_app = "../Models/Texto/App.php";
const
   card_body = $(".card-body"),
   btn_abrir_modal = $("#btn_abrir_modal"),
   btns_editar = document.querySelectorAll(".btn_editar"),
   btns_eliminar = document.querySelectorAll(".btn_eliminar"),
   modal_title = $(".modal-title"),
   formulario_modal = $("#formulario_modal"),
   accion_modal = $("#accion"),
   id = $("#id"),
   status_actual = $("#status_actual"),
   input_ubicacion = $("#input_ubicacion"),
   input_fecha_inicial = $("#input_fecha_inicial"),
   input_fecha_final = $("#input_fecha_final"),
   input_texto = $("#input_texto"),
   input_status = $("#input_status"),
   input_tipo = $("#input_tipo"),
   seccion_promocional = $("#seccion_promocional"),
   input_hora_inicial = $("#input_hora_inicial"),
   input_hora_final = $("#input_hora_final"),
   input_color_texto = $("#input_color_texto"),
   input_color_fondo = $("#input_color_fondo"),
   label_input_status = $("#label_input_status"),
   btn_enviar_formulario = $("#btn_enviar_formulario"),
   btn_reset_formulario = $("#btn_reset_formulario")
;


//RECARGAR Y ACTUALIZAR STATUS DE LOS REGISTROS SI VENCIO SU FECHA FINAL
tds_status = document.querySelectorAll(".td_status");
function actualizarStatusRegistros() {
   let ids = "";
   por_actualizar = false;
   query = "UPDATE texto SET text_status='0', text_order=1000000 WHERE text_id IN (";
   tds_status.forEach(td_status => {
      let hoy = moment().format("YYYY-MM-DD HH:mm:ss");
      let momento_actual = moment(hoy)
      id_registro = Number(td_status.getAttribute("data-id"));
      status_td = Number(td_status.getAttribute("data-status"));
      fecha_final = moment(td_status.getAttribute("data-fecha-final"));
      if (td_status.getAttribute("data-fecha-final").length <= 11) {
         fecha_final = moment(fecha_final).format("YYYY-MM-DD 23:59:59");
         fecha_final = moment(fecha_final)
         let hoy = moment().format("YYYY-MM-DD 23:59:59");
         momento_actual = moment(hoy)
      }
      fecha_vencida = momento_actual.isAfter(fecha_final);
      if (fecha_vencida && status_td==1) {
         por_actualizar = true;
         query += `${id_registro},`;
         ids += id_registro+'.,'
      }
   });
   query = query.slice(0,-1);
   query += ");";
   ids = ids.slice(0,-1);
   let datos = {
      accion: "actualizar_status",
      ids,
      query
   }
   if (por_actualizar) { peticionAjax(url_modelo_app,datos,cambioDeEstado,null); }
   
}
function cambioDeEstado(ajaxResponse) {
   objResponse =  ajaxResponse.Datos;
   tds_status.forEach(td_status => {
      id_registro = td_status.getAttribute("data-id");
      comparacion = `${id_registro}.`;

      if (objResponse.includes(id_registro)) {
         td_status.setAttribute("data-status", 0);
         td_status.classList.remove("fa-circle-check");
         td_status.classList.add("fa-circle-xmark");

         // obtener su td_orden, quitarle su numero y poner su letra color muted
         var td_orden = $(`td[data-id='${id_registro}'].td_orden`);
         td_orden.attr("data-orden",1000000)
         td_orden.removeClass("handle");
         if (!td_orden.hasClass("text-muted")) {td_orden.addClass("text-muted");}
         td_orden.html("&nbsp;<i class='fa-solid fa-grip-vertical'></i>");
      }
   });
}
const INTERVALO_MEDIA_HORA = 60000*30;
setInterval(() => {
   actualizarStatusRegistros();   
}, INTERVALO_MEDIA_HORA);
//RECARGAR Y ACTUALIZAR STATUS DE LOS REGISTROS SI VENCIO SU FECHA FINAL


/* --- FUNCIONES DE CAJON--- */
function peticionAjax(url, datos, funcion_en_success, funcion_en_complete,funcion_en_before) {
   if (datos == null) {
      datos = {};
   }
   $.ajax({
      url,
      type: "POST",
      data: datos,
      dataType: "json",
      beforeSend: () => {
         if (funcion_en_before == "EXPRESS") {return}
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
   if (btn_aceptar) {
      Swal.fire({
         icon: icono,
         title: titulo,
         html: texto,
         showConfirmButton: true,
         confirmButtonColor: '#494E53'
      }).then(() => {
         if (refresh) {
            window.location.reload();
         } else {
            return
         }
      })
   } else {
      Swal.fire({
         icon: icono,
         title: titulo,
         html: texto,
         timer: 1500,
         showConfirmButton: false
      }).then(() => {
         if (refresh) {
            window.location.reload();
         } else {
            return
         }
      })
   }
}
function mostrarAlertaConOpciones(titulo, texto, datos,refresh,funcion_then) {
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
            success: (ajaxResponse) => {
               Swal.fire({
                  icon: ajaxResponse.Icono_alerta,
                  title: ajaxResponse.Titulo_alerta,
                  text: ajaxResponse.Texto_alerta,
                  showConfirmButton: false,
                  timer: 1500
               }).then(() => {
                  if (refresh) {window.location.reload();}
                     switch (funcion_then) {
                        default:
                           break;
                     }
               });
            }
         });
      }
   });
}
function mostrarToast(icono, mensaje, posicion) {
   if (posicion == null) {posicion = 'top-end'}
   const Toast = Swal.mixin({
      toast: true,
      position: posicion,
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
      showConfirmButton: false,
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
   if (input.val() == "" || input.val() == -1 || input.val() == "-1") {
       mostrarToast('error', `Campo ${nombre_campo} vacio.`);
       input.focus();
       return false;
   }
   return true;
}

function formatearCantidadMX(cantidad) {
   total = new Intl.NumberFormat("es-MX").format(cantidad);

   return total;
}
function formatearFechaHora(la_fecha) {
   fecha = new Date(parseInt(la_fecha.substr(6)));
   fecha_hora = new Intl.DateTimeFormat("es-MX", { day: '2-digit', month: '2-digit', year: 'numeric', hour: "2-digit", minute: "2-digit", second: "2-digit", hour12: true }).format(fecha);

   return fecha_hora;
}
/* --- FUNCIONES DE CAJON--- */

function resetearSelect2Ubicacion() {
   input_ubicacion.prop('selectedIndex', 0);
   $("#select2-input_ubicacion-container").text('Selecciona una opción');
   $("#select2-input_ubicacion-container").attr('title', 'Selecciona una opción');
}

function validarRangoFechas(accion) {
   let
      fecha_actual = new Date();
      ayer = new Date(fecha_actual.setDate(fecha_actual.getDate()-1));
      ayer = new Date(ayer.setHours(23,59,59))
      ayer = ayer.getTime();

      fecha1 = new Date(input_fecha_inicial.val());
      fecha1 = new Date(fecha1.setDate(fecha1.getDate()+1));
      fecha1 = new Date(fecha1.setHours(0,0,0));
      data_fecha1 = new Date(fecha1).getTime();

      fecha2 = new Date(input_fecha_final.val());
      fecha2 = new Date(fecha2.setDate(fecha2.getDate()+1));
      fecha2 = new Date(fecha2.setHours(11,59,59));
      data_fecha2 = new Date(fecha2).getTime();

   if(accion == "crear"){   
      if (data_fecha1 <= ayer) {
         mostrarToast("warning", "No puedes publicar con fecha anterior a hoy.");
         input_fecha_inicial.focus();
         return false;
      }
   }
   if (data_fecha1 > data_fecha2) {
      mostrarToast("warning", "Rango de fechas inválido.");
      input_fecha_final.focus();
      return false;
   }
   return true;
}
function mostrarOcultarConfiguracionPromocional(tipo_promocion) {
   //Desplegar opciones de Promoción si el tipo de publicidad es 2=Promoción
   if (tipo_promocion == 1) {
      $("#seccion_promocional").slideUp();
   } else if (tipo_promocion == 2) {
      $("#seccion_promocional").slideDown();
   }
}

//ABRIR MODAL PARA REGISTRAR UN NEGOCIO
btn_abrir_modal.click((e) => {
   e.preventDefault();
   mostrarOcultarConfiguracionPromocional(1); //Ocultar opciones
   resetearSelect2Ubicacion();
   formulario_modal[0].reset();
   modal_title.html("<i class='fa-solid fa-terminal'></i>&nbsp; AGREGAR TEXTO</h5>");
   accion_modal.val("crear_texto");
   status_actual.val("");
   btn_enviar_formulario.text("AGREGAR");
   setTimeout(() => {
      input_ubicacion.focus();
   }, 500);
})

//BOTON RESETEAR FORMIÑARIO
btn_reset_formulario.click(() => {
   estado = input_status.prop("checked",true);
   input_status.click();
   input_status.click();
   resetearSelect2Ubicacion();
   mostrarOcultarConfiguracionPromocional(1); //Ocultar opciones
})

//ESTADO ACTIVO E INACTIVO
input_status.click(() => {
   estado = input_status.prop("checked")
   if (estado) { label_input_status.text("Activo"); input_status.attr("data-activo",1); }
   else { label_input_status.text("Inactivo"); input_status.attr("data-activo",0); }
});

//CAMBIO EN EL INPUT TIPO DE PUBLICIDAD
input_tipo.change((e) => {
   e.preventDefault();
   tipo_promocion = input_tipo.val();
   mostrarOcultarConfiguracionPromocional(tipo_promocion)
})

// REGISTRAR O EDITAR IMAGEN
formulario_modal.on("submit",(e) => {
   e.preventDefault();
   validar_campo = validarInput(input_ubicacion,"UBICACIÓN");
   if (!validar_campo) return;
   validar_campo = validarInput(input_fecha_inicial,"FECHA INICIAL");
   if (!validar_campo) return;
   validar_campo = validarInput(input_fecha_final,"FECHA FINAL");
   if (!validar_campo) return;
   if (btn_enviar_formulario.text() == "AGREGAR"){
      validar_rango = validarRangoFechas("crear");
      if (!validar_rango) return;
   } else {
      validar_rango = validarRangoFechas("editar");
      if (!validar_rango) return;
   }
   validar_campo = validarInput(input_texto,"TEXTO CINTILLA");
   if (!validar_campo) return;
   validar_campo = validarInput(input_tipo,"TIPO PUBLICIDAD");
   if (!validar_campo) return;
   if (input_tipo.val() == 2) {
      validar_campo = validarInput(input_hora_inicial,"HORA INICIAL");
      if (!validar_campo) return;
      validar_campo = validarInput(input_hora_final,"HORA FINAL");
      if (!validar_campo) return;
      if (input_color_texto.val() == input_color_fondo.val()) {
         mostrarToast("warning","NO pueden ser del mismo color el texto y el fondo, cambia uno.", "center")
         return
      }
   }

   datos = formulario_modal.serializeArray();
   agregarDatoAlArray("input_texto",input_texto.val(),datos);
   let status_activo = false;
   datos.forEach(dato => {
      if (dato.name == "input_status") {
         status_activo = true;
         if (status_actual.val() == 1)
            agregarDatoAlArray("asignar_orden",-1,datos); // no hubo cambio, conservar su posicion
         else
            agregarDatoAlArray("asignar_orden",1,datos); // hubo cambio, asignar posicion
      }
   });
   if (!status_activo) {
      if (status_actual.val() == 1)
         agregarDatoAlArray("asignar_orden",1000000,datos); // hubo cambio, asignar posicion 1000000=NULL
      else
         agregarDatoAlArray("asignar_orden",-1,datos); // no hubo cambio, conserva posicion NULL
   }
   // console.log(status_actual.val());
   // return console.log(datos);
   peticionAjax(url_modelo_app,datos,textoRegistradoEditado,"sin funcion complete");
})
function agregarDatoAlArray(nombre,valor,array){
   //array obtenido de formulario_modal.serializeArray()
   dato_nuevo = {
      name: nombre,
      value: valor
   }
   array.push(dato_nuevo)
}

function textoRegistradoEditado(ajaxResponse) {
   mostrarAlertaEditableConRefreshAlFinal(ajaxResponse,null,null,null,null,true);
   $(".btn-close").click();
}

//ABRIR MODAL PARA EDITAR
btns_editar.forEach((btn_editar) => {
   btn_editar.addEventListener('click',() => {
      accion_modal.val("editar_texto");
      status_actual.val(btn_editar.getAttribute("data-status-actual"));
      modal_title.html("<i class='fa-solid fa-terminal'></i>&nbsp; EDITAR TEXTO</h5>");
      btn_enviar_formulario.text("GUARDAR");

      datos = {
         accion: "mostrar_texto",
         id: Number(btn_editar.getAttribute('data-id'))
      }
      id.val(btn_editar.getAttribute("data-id"));
      peticionAjax(url_modelo_app,datos,mostrarTexto,null);
   })
});
function mostrarTexto(ajaxResponse) {
   objResponse = ajaxResponse.Datos;
   
   establecerValorSelect2(objResponse);
   input_fecha_inicial.val(objResponse.Fecha_inicial);
   input_fecha_final.val(objResponse.Fecha_final);
   input_texto.text(objResponse.Texto);
   if (objResponse.Status == "1") {
      input_status.prop("checked", true);
      input_status.click()
      input_status.click()
   } else {
      input_status.prop("checked", false);
      input_status.click()
      input_status.click()
   }
   input_tipo.val(objResponse.Tipo);
   input_tipo.change();
   if (objResponse.Tipo == 2) {
      input_hora_inicial.val(objResponse.Hora_inicial);
      input_hora_final.val(objResponse.Hora_final);
      input_color_texto.val(objResponse.Color_texto);
      input_color_fondo.val(objResponse.Color_fondo);
   }
   
}
function establecerValorSelect2(objResponse) {
   input_ubicacion.html("");
   input_ubicacion.append(`<option select value=${objResponse.Id_cliente}>${objResponse.Ubicacion}</option>`);
   datos = {
      accion: "mostrar_clientes_ajax"
   }
   peticionAjax(url_modelo_app_cliente,datos,rellenarSelect2Ubicacion,"sin funcion complete");   
}
function rellenarSelect2Ubicacion(ajaxResponse) {
   objResponse = ajaxResponse.Datos;
   objResponse.forEach(Ubicacion => {
      input_ubicacion.append(`<option value=${Ubicacion.cli_id}>${Ubicacion.cli_nom_empresa}</option>`);
   });
}

//ELIMINAR OBJETO
btns_eliminar.forEach((btn_eliminar) => {
   btn_eliminar.addEventListener('click', () => {
      let titulo = "¿Estás seguro de eliminar esta cintilla?";
      let texto = ``;

      let datos = {
         accion: "eliminar_texto",
         id: Number(btn_eliminar.getAttribute("data-id")),
      }
      mostrarAlertaConOpciones(titulo,texto,datos,true,null);
   })
});


//SELECTOR DE CLIENTES
let div_filtrar_cliente;
const url_modelo_app_cliente = "../Models/Cliente/App.php"
setTimeout(() => {
   div_filtrar_cliente = $(".div_filtrar_cliente");
   crearSelectorFiltroCliente();
}, 1000);

function crearSelectorFiltroCliente() {
   let datos = {accion: "mostrar_clientes_ajax"}
   peticionAjax(url_modelo_app_cliente,datos,rellenarFiltroClientes,"sin funcion complete","EXPRESS");
}
function rellenarFiltroClientes(ajaxResponse) {
   objResponse = ajaxResponse.Datos;
   input_select = `
   <div class='row align-items-center'>
      <span class='col-auto'>
         Cliente:
      </span>
      <div class='col'>
         <select class='form-select select_filtro_cliente' style='width: 100' id='select_filtro_cliente'>`;
            objResponse.forEach(objeto => {
            input_select += `<option>${objeto.cli_nom_empresa}</option>`;
            // input_select += `<option value='${objeto.cli_id}'>${objeto.cli_nom_empresa}</option>`;
            });
         input_select += `   
         </select>
      </div>
   </div>
   `;
   div_filtrar_cliente.html(input_select);
   
   //Mostrar registros por el primer cliente
   let select_cliente = $("#select_filtro_cliente");
   let cliente = select_cliente.val();
   tabla_textos.columns(0).search(cliente).draw();
   enlistarOrden();
   tbody.fadeIn();
}
card_body.click((e) => {
   if (e.target.id == "select_filtro_cliente") {
      select_cliente = $(e.target);
      select_cliente.change(() => {
         let cliente = select_cliente.val();
         // console.log(cliente);
         tabla_textos.columns(0).search(cliente).draw();
      });
   }
});
function enlistarOrden() {
   orden = 1;
   tbody.find("td.handle").each(function () {
      let td_handle = $(this);
      td_handle.attr("data-orden",orden);
      td_handle.html(`${orden} &nbsp;<i class='fa-solid fa-grip-vertical'></i>`);
      let id_td = td_handle.attr("data-id");
      let orden_td = td_handle.attr("data-orden")
         
      datos = {
         accion: "actualizar_orden",
         id: id_td,
         orden: orden_td,
         actualizar: moment().format("YYYY-MM-DD HH:mm:ss")
      }
      peticionAjax(url_modelo_app,datos,null,"sin funcion complete","EXPRESS")
      orden++;
      // console.log("orden "+orden);
   });
}