var tabla_banhorizontales;
tbody = $("#tabla_banhorizontales").find('tbody');
tbody.attr("hidden", false);
tbody.fadeOut(1);

$(document).ready(() => {
   tabla_banhorizontales = $('#tabla_banhorizontales').DataTable({
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
      aaSorting: [], //deshabilitar ordenado automatico
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
         // tabla_banhorizontales.page.len(-1).draw(false);

         // refresh so that newly shown rows are counted as sortable items
         $(this).sortable('refresh');

         // sort table by sequence
         // tabla_banhorizontales.order([4, 'asc']).draw(false);
      },
      sort: function (event, ui) {
      },
      stop: function (event, ui) {
         enlistarOrden();
      }
   });
   // Arrastrar renglón

   $("#div_archivo_cargado").hide();

   function formatearFechaHora(la_fecha) {
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
   const url_modelo_app = "../Models/Banhorizontal/App.php";
   tds_status = document.querySelectorAll(".td_status");
   function actualizarStatusRegistros() {
      let ids = "";
      por_actualizar = false;
      query = "UPDATE imagen_horizontal SET imgh_status='0', imgh_order=1000000 WHERE imgh_id IN (";
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

const url_modelo_app = "../Models/Banhorizontal/App.php";
const
   TAMANIO_MAX_BYTES = 100000000, // 1MB = 1,000,000 Bytes
   mb_max = TAMANIO_MAX_BYTES / 1000000; //convertir bytes a MB
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
   div_cargar_archivo = $("#div_cargar_archivo"),
   input_archivo = $("#input_archivo"),
   div_archivo_cargado = $("#div_archivo_cargado"),
   ver_archivo = $("#ver_archivo"),
   peso_archivo = $("#peso_archivo").text(mb_max),
   btn_quitar_archivo = $("#btn_quitar_archivo"),
   input_status = $("#input_status"),
   label_input_status = $("#label_input_status"),
   btn_enviar_formulario = $("#btn_enviar_formulario"),
   btn_reset_formulario = $("#btn_reset_formulario")
   ;
let imagen_preview;

//RECARGAR Y ACTUALIZAR STATUS DE LOS REGISTROS SI VENCIO SU FECHA FINAL
tds_status = document.querySelectorAll(".td_status");
function actualizarStatusRegistros() {
   let ids = "";
   por_actualizar = false;
   query = "UPDATE imagen_horizontal SET imgh_status='0', imgh_order=1000000 WHERE imgh_id IN (";
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
function peticionAjaxConArchivo(url, datos, funcion_en_success, funcion_en_complete) {
   if (datos == null) {
      datos = {};
   }
   $.ajax({
      url,
      type: "POST",
      data: datos,
      dataType: "json",
      contentType:false,
      cache:false,
      async: true,
      processData:false,
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
                        case "btn_quitar_archivo":
                           mostrarInputOcultarImagen(true,imagen_preview);
                           break;
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
function mostrarInputOcultarImagen(quite_archivo,imagen_preview) {
   //Si se quito la imagen, hay que limpiar la imagen preview de la tabla para que no aparezca la miniatura y al darle editar, nos muestre el input file (al menos al no haber recargado la pagina)
   if (quite_archivo) {
      imagen_preview.setAttribute("src", "../");
   }
   $("#div_cargar_archivo").show();
   $("#div_archivo_cargado").hide();
}
function mostrarImagenOcultarInput(src_archivo) {
   if (src_archivo == "../") {
      mostrarInputOcultarImagen();
   } else {
      $("#div_cargar_archivo").hide();
      $("#div_archivo_cargado").show();
   }
}

//ABRIR MODAL PARA REGISTRAR UN NEGOCIO
btn_abrir_modal.click((e) => {
   e.preventDefault();
   mostrarInputOcultarImagen();
   resetearSelect2Ubicacion();
   formulario_modal[0].reset();
   modal_title.html("<i class='fa-regular fa-file-image'></i>&nbsp; AGREGAR BANNER HORIZONTAL</h5>");
   accion_modal.val("crear_banhorizontal");
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
})

//ESTADO ACTIVO E INACTIVO
input_status.click(() => {
   estado = input_status.prop("checked")
   if (estado) { label_input_status.text("Activo"); input_status.attr("data-activo",1); }
   else { label_input_status.text("Inactivo"); input_status.attr("data-activo",0); }
});

//QUITAR SECCION DE VER ARCHIVO, ELIMINAR LA IMAGEN Y MOSTRAR LA SECCION DEL INPUT FILE
btn_quitar_archivo.click((e) => {
   e.preventDefault();
   let titulo = "¿Estás seguro de eliminar el archivo";
   let array_path_archivo = ver_archivo.attr("src").split("/");
   let nombre_archivo = array_path_archivo.reverse();
   let texto = `${nombre_archivo[0]} ?`;
   //obtener el path actual del archivo, desde la carpeta Assets
   let path_actual = ver_archivo.attr("src").split("../").reverse();
   
   let datos = {
      accion: "eliminar_archivo",
      id: Number(id.val()),
      src_archivo: path_actual[0]
   }
   mostrarAlertaConOpciones(titulo,texto,datos,false,"btn_quitar_archivo");
});

//Restringir tamñao MAX del archivo a cargar
function validarTamanioMaxDelArchivo(size_archivo) {
   if (size_archivo > TAMANIO_MAX_BYTES) {
      mostrarToast("warning",`El archivo excede el peso máximo de los ${mb_max}MB.`,"center");
      return false;
   }
   return true;
}

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

   let datos = new FormData(formulario_modal[0]);
   // return console.log(...datos);
   if (btn_enviar_formulario.text() == "AGREGAR") {
      validar_campo = validarInput(input_archivo,"ARCHIVO");
      if (!validar_campo) return;
      validar_peso_archivo = validarTamanioMaxDelArchivo($("#input_archivo")[0].files[0].size);
      if (!validar_peso_archivo) return;
      peticionAjaxConArchivo(url_modelo_app, datos, banhorizontalRegistradoEditado, "sin funcion complete");
   } else if (btn_enviar_formulario.text() == "GUARDAR") {
      let array_path_archivo = ver_archivo.attr("src").split("/");
      let id_ubicacion_actual = array_path_archivo[3]
      //obtener el path actual del archivo, desde la carpeta Assets
      let path_actual = ver_archivo.attr("src").split("../").reverse();
   
      datos = formulario_modal.serializeArray();
      let status_activo = false;
      datos.forEach(dato => {
         if (dato.name == "input_ubicacion") {
            //Si editan la ubicación del objeto, moveremos el archivo a la carpeta correspondiente
            if (dato.value != id_ubicacion_actual) {
               agregarDatoAlArray("src_archivo",path_actual[0],datos)
               agregarDatoAlArray("id_ubicacion_actual",id_ubicacion_actual,datos)
            }
         }
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

      if (input_archivo.val() == "") { //Editar el objeto sin editar el archivo
         peticionAjax(url_modelo_app,datos,banhorizontalRegistradoEditado,"sin funcion complete");
      } else { //se editarará el objeto con archvio
         validar_campo = validarInput(input_archivo,"ARCHIVO");
         if (!validar_campo) return;
         validar_peso_archivo = validarTamanioMaxDelArchivo($("#input_archivo")[0].files[0].size);
         if (!validar_peso_archivo) return;
         //Regresamos a crear el FormData para que recoja el inpur File ya con los datos agregados o editados
         datos = new FormData(formulario_modal[0]);
         // return console.log(...datos);
         datosformData = [...datos];
         datosformData.forEach(dato => {
            if (dato[0] == "input_ubicacion") {
               //Si editan la ubicación del objeto, moveremos el archivo a la carpeta correspondiente
               if (dato[1] != id_ubicacion_actual) {
                  datos.append("src_archivo",path_actual[0])
                  datos.append("id_ubicacion_actual",id_ubicacion_actual)
               }
            }
            if (dato[0] == "input_status") {
               status_activo = true;
               if (status_actual.val() == 1)
                  datos.append("asignar_orden",-1); // no hubo cambio, conservar su posicion
               else
                  datos.append("asignar_orden",1); // hubo cambio, asignar posicion
            }
         });
         if (!status_activo) {
            if (status_actual.val() == 1)
               datos.append("asignar_orden",1000000); // hubo cambio, asignar posicion 1000000=NULL
            else
               datos.append("asignar_orden",-1); // no hubo cambio, conserva posicion NULL
         }
         // return console.log(...datos);

         peticionAjaxConArchivo(url_modelo_app,datos,banhorizontalRegistradoEditado,"sin funcion complete");
      }
   }
})
function agregarDatoAlArray(nombre,valor,array){
   dato_nuevo = {
      name: nombre,
      value: valor
   }
   array.push(dato_nuevo)
}

function banhorizontalRegistradoEditado(ajaxResponse) {
   mostrarAlertaEditableConRefreshAlFinal(ajaxResponse,null,null,null,null,true);
   $(".btn-close").click();
}

//ABRIR MODAL PARA EDITAR
btns_editar.forEach((btn_editar) => {
   btn_editar.addEventListener('click',() => {
      accion_modal.val("editar_banhorizontal");
      status_actual.val(btn_editar.getAttribute("data-status-actual"));
      imagen_preview = btn_editar.parentElement.parentElement.getElementsByTagName("img")[0];
      let src_archivo = imagen_preview.getAttribute("src");

      mostrarImagenOcultarInput(src_archivo);
      modal_title.html("<i class='fa-regular fa-file-image'></i>&nbsp; EDITAR BANNER HORIZONTAL</h5>");
      btn_enviar_formulario.text("GUARDAR");

      datos = {
         accion: "mostrar_banhorizontal",
         id: Number(btn_editar.getAttribute('data-id'))
      }
      id.val(btn_editar.getAttribute("data-id"));
      peticionAjax(url_modelo_app,datos,mostrarBanhorizontal,null);
   })
});
function mostrarBanhorizontal(ajaxResponse) {
   objResponse = ajaxResponse.Datos;

   establecerValorSelect2(objResponse);
   input_fecha_inicial.val(objResponse.Fecha_inicial);
   input_fecha_final.val(objResponse.Fecha_final);
   ver_archivo.attr("src",`../${objResponse.Ruta}`);
   if (objResponse.Status == "1") {
      input_status.prop("checked", true);
      input_status.click()
      input_status.click()
   } else {
      input_status.prop("checked", false);
      input_status.click()
      input_status.click()
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
      let titulo = "¿Estás seguro de eliminar a";
      let texto = `${btn_eliminar.getAttribute("data-nombre")} ?`;

      imagen_preview = btn_eliminar.parentElement.parentElement.getElementsByTagName("img")[0]      
      let path_actual = imagen_preview.getAttribute("src").split("../").reverse();

      let datos = {
         accion: "eliminar_banhorizontal",
         id: Number(btn_eliminar.getAttribute("data-id")),
         src_archivo: path_actual[0]
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
   tabla_banhorizontales.columns(0).search(cliente).draw();
   enlistarOrden();
   tbody.fadeIn();
}
card_body.click((e) => {
   if (e.target.id == "select_filtro_cliente") {
      select_cliente = $(e.target);
      select_cliente.change(() => {
         let cliente = select_cliente.val();
         // console.log(cliente);
         tabla_banhorizontales.columns(0).search(cliente).draw();
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