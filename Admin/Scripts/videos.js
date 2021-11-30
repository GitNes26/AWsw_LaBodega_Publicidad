$(document).ready(() => {
   var tabla_videos = $('#tabla_videos').DataTable({
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
   const url_modelo_app = "../Models/Video/App.php";
   tds_status = document.querySelectorAll(".td_status");
   function actualizarStatusRegistros() {
      let ids = "";
      por_actualizar = false;
      query = "UPDATE video SET vid_status='0' WHERE vid_id IN (";
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
            td_status.classList.remove("fa-circle-check")
            td_status.classList.add("fa-circle-xmark")
         }
      });
   }
   actualizarStatusRegistros();   
   //RECARGAR Y ACTUALIZAR STATUS DE LOS REGISTROS SI VENCIO SU FECHA FINAL
});

/*Select2*/
$.fn.select2.defaults.set('language', 'es');
$('.select2').select2()

const url_modelo_app = "../Models/Video/App.php";
const
   TAMANIO_MAX_BYTES = 10000000, // 1MB = 1,000,000 Bytes
   mb_max = TAMANIO_MAX_BYTES / 1000000; //convertir bytes a MB
const
   btn_abrir_modal = $("#btn_abrir_modal"),
   btns_editar = document.querySelectorAll(".btn_editar"),
   btns_eliminar = document.querySelectorAll(".btn_eliminar"),
   modal_title = $(".modal-title"),
   formulario_modal = $("#formulario_modal"),
   accion_modal = $("#accion"),
   id = $("#id"),
   input_ubicacion = $("#input_ubicacion"),
   input_plantilla = $("#input_plantilla"),
   input_fecha_inicial = $("#input_fecha_inicial"),
   input_fecha_final = $("#input_fecha_final"),
   div_cargar_archivo = $("#div_cargar_archivo"),
   input_archivo = $("#input_archivo"),
   peso_archivo = $("#peso_archivo").text(mb_max),
   div_archivo_cargado = $("#div_archivo_cargado"),
   ver_archivo = $("#ver_archivo"),
   btn_quitar_archivo = $("#btn_quitar_archivo"),
   input_status = $("#input_status"),
   label_input_status = $("#label_input_status"),
   btn_enviar_formulario = $("#btn_enviar_formulario"),
   btn_reset_formulario = $("#btn_reset_formulario")
   ;
let video_preview;

//RECARGAR Y ACTUALIZAR STATUS DE LOS REGISTROS SI VENCIO SU FECHA FINAL
tds_status = document.querySelectorAll(".td_status");
function actualizarStatusRegistros() {
   let hoy = moment().format("YYYY-MM-DD HH:mm:ss");
   let momento_actual = moment(hoy)
   let ids = "";
   por_actualizar = false;
   query = "UPDATE video SET vid_status='0' WHERE vid_id IN (";
   tds_status.forEach(td_status => {
      id_registro = Number(td_status.getAttribute("data-id"));
      status_td = Number(td_status.getAttribute("data-status"));
      fecha_final = moment(td_status.getAttribute("data-fecha-final"));
      if (fecha_final["_i"].length <= 10) {
         hoy = moment().format("YYYY-MM-DD");
         momento_actual = moment(hoy).add(1,'days')
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
         td_status.classList.remove("fa-circle-check")
         td_status.classList.add("fa-circle-xmark")
      }
   });
}
const INTERVALO_MEDIA_HORA = 60000*30;
setInterval(() => {
   actualizarStatusRegistros();   
}, INTERVALO_MEDIA_HORA);
//RECARGAR Y ACTUALIZAR STATUS DE LOS REGISTROS SI VENCIO SU FECHA FINAL


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
                           mostrarInputOcultarVideo(true,video_preview);
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
function resetearSelect2Plantilla() {
   input_plantilla.prop('selectedIndex', 0);
   $("#select2-input_plantilla-container").text('Selecciona una opción');
   $("#select2-input_plantilla-container").attr('title', 'Selecciona una opción');
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
function mostrarInputOcultarVideo(quite_archivo,video_preview) {
   //Si se quito el video, hay que limpiar el video preview de la tabla para que no aparezca la miniatura y al darle editar, nos muestre el input file (al menos al no haber recargado la pagina)
   if (quite_archivo) {
      video_preview.setAttribute("src", "../");
   }
   $("#div_cargar_archivo").show();
   $("#div_archivo_cargado").hide();
}
function mostrarVideOcultarInput(src_archivo) {
   if (src_archivo == "../") {
      mostrarInputOcultarVideo();
   } else {
      $("#div_cargar_archivo").hide();
      $("#div_archivo_cargado").show();
   }
}

//ABRIR MODAL PARA REGISTRAR
btn_abrir_modal.click((e) => {
   e.preventDefault();
   mostrarInputOcultarVideo();
   resetearSelect2Ubicacion();
   resetearSelect2Plantilla();
   formulario_modal[0].reset();
   modal_title.html("<i class='fa-regular fa-file-video'></i>&nbsp; AGREGAR VIDEO</h5>");
   accion_modal.val("crear_video");
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
   resetearSelect2Plantilla();
})

//ESTADO ACTIVO E INACTIVO
input_status.click(() => {
   estado = input_status.prop("checked")
   if (estado) { label_input_status.text("Activo"); input_status.attr("data-activo",1); }
   else { label_input_status.text("Inactivo"); input_status.attr("data-activo",0); }
});

//QUITAR SECCION DE VER ARCHIVO, ELIMINAR EL VIDEO Y MOSTRAR LA SECCION DEL INPUT FILE
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
      let mb_max = TAMANIO_MAX_BYTES / 1000000; //convertir bytes a MB
      mostrarToast("warning",`El archivo excede el peso máximo de los ${mb_max}MB.`,"center");
      return false;
   }
   return true;
}

// REGISTRAR O EDITAR VIDEO
formulario_modal.on("submit",(e) => {
   e.preventDefault();
   validar_campo = validarInput(input_ubicacion,"UBICACIÓN");
   if (!validar_campo) return;
   validar_campo = validarInput(input_plantilla,"PLANTILLA");
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
      peticionAjaxConArchivo(url_modelo_app, datos, videoRegistradoEditado, "sin funcion complete");
   } else if (btn_enviar_formulario.text() == "GUARDAR") {
      let array_path_archivo = ver_archivo.attr("src").split("/");
      let id_ubicacion_actual = array_path_archivo[3]
      //obtener el path actual del archivo, desde la carpeta Assets
      let path_actual = ver_archivo.attr("src").split("../").reverse();
   
      datos = formulario_modal.serializeArray();
      datos.forEach(dato => {
         if (dato.name == "input_ubicacion") {
            //Si editan la ubicación del objeto, moveremos el archivo a la carpeta correspondiente
            if (dato.value != id_ubicacion_actual) {
               agregarDatoAlArray("src_archivo",path_actual[0],datos)
               agregarDatoAlArray("id_ubicacion_actual",id_ubicacion_actual,datos)
            }
         }
      });

      if (input_archivo.val() == "") { //Editar el objeto sin editar el archivo
         peticionAjax(url_modelo_app,datos,videoRegistradoEditado,"sin funcion complete");
      } else { //se editarará el objeto con archvio
         validar_campo = validarInput(input_archivo,"ARCHIVO");
         if (!validar_campo) return;
         validar_peso_archivo = validarTamanioMaxDelArchivo($("#input_archivo")[0].files[0].size);
      if (!validar_peso_archivo) return;
         //Regresamos a crear el FormData para que recoja el inpur File ya con los datos agregados o editados
         datos = new FormData(formulario_modal[0]);

         peticionAjaxConArchivo(url_modelo_app,datos,videoRegistradoEditado,"sin funcion complete");
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

function videoRegistradoEditado(ajaxResponse) {
   mostrarAlertaEditableConRefreshAlFinal(ajaxResponse,null,null,null,null,true);
   $(".btn-close").click();
}

//ABRIR MODAL PARA EDITAR
btns_editar.forEach((btn_editar) => {
   accion_modal.val("editar_video");
   btn_editar.addEventListener('click',() => {
      video_preview = btn_editar.parentElement.parentElement.getElementsByTagName("video")[0];
      let src_archivo = video_preview.getAttribute("src");
      mostrarVideOcultarInput(src_archivo);
      modal_title.html("<i class='fa-regular fa-file-video'></i>&nbsp; EDITAR VIDEO</h5>");
      btn_enviar_formulario.text("GUARDAR");

      datos = {
         accion: "mostrar_video",
         id: Number(btn_editar.getAttribute('data-id'))
      }
      id.val(btn_editar.getAttribute("data-id"));
      peticionAjax(url_modelo_app,datos,mostrarVideo,null);
   })
});
function mostrarVideo(ajaxResponse) {
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
   peticionAjax(`../Models/Cliente/App.php`,datos,rellenarSelect2Ubicacion,"sin funcion complete");
   
   rellenarSelect2Plantilla(objResponse);
}
function rellenarSelect2Ubicacion(ajaxResponse) {
   objResponse = ajaxResponse.Datos;
   objResponse.forEach(Ubicacion => {
      input_ubicacion.append(`<option value=${Ubicacion.cli_id}>${Ubicacion.cli_nom_empresa}</option>`);
   });
}
function rellenarSelect2Plantilla(objResponse) {
   input_plantilla.html("");
   texto_plantilla = "";
   switch (objResponse.Plantilla) {
      case "1":
         texto_plantilla = "Plantilla 1 (1920x1080)";
         break;
      case "2":
         texto_plantilla = "Plantilla 2 (1920x800 MP4)";
         break;
      case "3":
         texto_plantilla = "Plantilla 3 (???)";
         break;
      default:
         break;
   }
   input_plantilla.append(`<option select value=${objResponse.Plantilla}>${texto_plantilla}</option>`);
   input_plantilla.append(`<option value="1">Plantilla 1 (1920x1080)</option>`);
   input_plantilla.append(`<option value="2">Plantilla 2 (1920x800 MP4)</option>`);
   input_plantilla.append(`<option value="3">Plantilla 3 (???)</option>`);
}

//ELIMINAR OBJETO
btns_eliminar.forEach((btn_eliminar) => {
   btn_eliminar.addEventListener('click', () => {
      let titulo = "¿Estás seguro de eliminar a";
      let texto = `${btn_eliminar.getAttribute("data-nombre")} ?`;

      video_preview = btn_eliminar.parentElement.parentElement.getElementsByTagName("video")[0]      
      let path_actual = video_preview.getAttribute("src").split("../").reverse();

      let datos = {
         accion: "eliminar_video",
         id: Number(btn_eliminar.getAttribute("data-id")),
         src_archivo: path_actual[0]
      }
      mostrarAlertaConOpciones(titulo,texto,datos,true,null);
   })
});
