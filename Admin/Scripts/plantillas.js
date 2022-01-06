$(document).ready(() => {

});
moment.locale()

const
   id_negocio = $("#id_negocio"),
   tag_video = document.querySelector('video'),
   img_banvertical = $("#img_banvertical"),
   img_banhorizontal = $("#img_banhorizontal"),
   img_bancompleto = $("#img_bancompleto"),
   texto_marquee = $("#texto_marquee")
   ;
let 
   datos,
   hoy_inicial = moment().startOf("day").format('YYYY-MM-DD HH:mm:ss'),
   hoy_final = moment().endOf("day").format('YYYY-MM-DD HH:mm:ss')
   ;

let lista_videos = []; v=0;
let lista_banverticales = []; bv=0;
let lista_banhorizontales = []; bh=0;
let lista_bancompletos = []; bc=0;
let lista_textos = []; t=0;

let vueltas_actualizacion = 1;
const UN_SEGUNDO = 1000;
const UN_MINUTO = 60000;
const INTERVALO_ENTRE_IMAGENES_BANVERTICALES = 5*UN_SEGUNDO;
const INTERVALO_ENTRE_IMAGENES_BANHORIZONTALES = 7*UN_SEGUNDO;
const INTERVALO_ENTRE_IMAGENES_COMPLETAS = 10*UN_SEGUNDO;
const VELOCIDAD_CINTILLA = 15*UN_SEGUNDO;
const INTERVALO_REFRESCAR_MULTIMEDIA = 15*UN_MINUTO;
const INTERVALO_UNA_HORA = 60*UN_MINUTO;

function peticionAjax(datos,lista,funcion_en_complete) {
   $.ajax({
      url: "Admin/Models/Reproductor/App.php",
      type: "POST",
      data: datos,
      dataType: "json",
      success: (ajaxResponse) => {
         if (ajaxResponse.Resultado == "correcto") {
            switch (lista) {
               case "lista_videos":
                  lista_videos = ajaxResponse.Datos;
                  break;
               case "lista_banverticales":
                  lista_banverticales = ajaxResponse.Datos;
                  break;
               case "lista_banhorizontales":
                  lista_banhorizontales = ajaxResponse.Datos;
                  break;
               case "lista_bancompletos":
                  lista_bancompletos = ajaxResponse.Datos;
                  break;
               case "lista_textos":
                  lista_textos = ajaxResponse.Datos;
                  break;
               default:
                  break;
            }
         }
      },
      complete: () => {
         if (funcion_en_complete != null)
            funcion_en_complete();
      }
   })
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

function listaVideos() {
   datos = {
      accion: "lista_videos",
      id_ubicacion: Number(id_negocio.val()),
      fecha_inicial: hoy_inicial,
      fecha_final: hoy_final,
   };
   // console.log(datos);
   peticionAjax(datos,"lista_videos",listaBanverticales);
}
function listaBanverticales() {
   datos = {
      accion: "lista_banverticales",
      id_ubicacion: Number(id_negocio.val()),
      fecha_inicial: hoy_inicial,
      fecha_final: hoy_final,
   };
   // console.log(datos);
   peticionAjax(datos,"lista_banverticales",listaBanhorizontales);
}
function listaBanhorizontales() {
   datos = {
      accion: "lista_banhorizontales",
      id_ubicacion: Number(id_negocio.val()),
      fecha_inicial: hoy_inicial,
      fecha_final: hoy_final,
   };
   // console.log(datos);
   peticionAjax(datos,"lista_banhorizontales",listaBancompletos);
}
function listaBancompletos() {
   datos = {
      accion: "lista_bancompletos",
      id_ubicacion: Number(id_negocio.val()),
      fecha_inicial: hoy_inicial,
      fecha_final: hoy_final,
   };
   // console.log(datos);
   peticionAjax(datos,"lista_bancompletos",listaTextos);
}
function listaTextos() {
   datos = {
      accion: "lista_textos",
      id_ubicacion: Number(id_negocio.val()),
      fecha_inicial: hoy_inicial,
      fecha_final: hoy_final,
   };
   // console.log(datos);
   if (vueltas_actualizacion == 1)
      peticionAjax(datos,"lista_textos",empezarAreproducir);
   peticionAjax(datos,"lista_textos",null);
}

mostrarBlockOutCargando();
actualizarListas();
function actualizarListas() {
   listaVideos();
}
function empezarAreproducir() {
   mostrarBlockOutListo();
   
   cambiarVideo()
   cambiarBanvertical();
   cambiarBanhorizontal();
   cambiarBancompleto();
   cambiarTextosMarquee();
   vueltas_actualizacion++;
   // console.log(lista_videos);
   // console.log(lista_banhorizontales);
   // console.log(lista_banhorizontales);
   // console.log(lista_bancompletos);
   // console.log(lista_textos);
}
setInterval(() => { actualizarListas(); }, INTERVALO_REFRESCAR_MULTIMEDIA);

function cambiarVideo() {
   if (lista_videos.length > 0) {
      if (tag_video.getAttribute("src") == "") {
         tag_video.setAttribute("src",`Admin/${lista_videos[0].vid_ruta}`);
         tag_video.play();
         tag_video.addEventListener("ended", () => {
            v++;
            cambiarVideo();
         });
      } else {
         if(v >= lista_videos.length) {v=0}
         tag_video.setAttribute("src",`Admin/${lista_videos[v].vid_ruta}`);
         tag_video.play();
         tag_video.addEventListener("ended", () => {
            v++;
         });
      }
   } else {
      // tag_video.setAttribute("src",`Admin/Assets/Archivos_panel/Defaults/aw_video.mp4`);
      // tag_video.play();
      if (tag_video.getAttribute("src") == "") {
         setTimeout(() => { cambiarVideo() }, 10*UN_SEGUNDO);
      }
      tag_video.addEventListener("ended", () => {
         v++;
         cambiarVideo();
      });
   }
}

function cambiarBanvertical() {
   if (lista_banverticales.length > 0) {
      if (img_banvertical.attr("src") == "") {
         img_banvertical.attr("src",`Admin/${lista_banverticales[0].img_ruta}`);
         setTimeout(() => { bv++; cambiarBanvertical() }, INTERVALO_ENTRE_IMAGENES_BANVERTICALES);
      } else {
         if(bv >= lista_banverticales.length) {bv=0}
         img_banvertical.attr("src",`Admin/${lista_banverticales[bv].img_ruta}`);
         setTimeout(() => { bv++; cambiarBanvertical() }, INTERVALO_ENTRE_IMAGENES_BANVERTICALES);
      }
   } else {
      // img_banvertical.attr('src',"Admin/Assets/Archivos_panel/Defaults/aw_banvertical.png");
      setTimeout(() => { bh=0; cambiarBanvertical() }, INTERVALO_ENTRE_IMAGENES_BANVERTICALES);
   }
}

function cambiarBanhorizontal() {
   if (lista_banhorizontales.length > 0) {
      if (img_banhorizontal.attr("src") == "") {
         img_banhorizontal.attr("src",`Admin/${lista_banhorizontales[0].imgh_ruta}`);
         setTimeout(() => { bh++; cambiarBanhorizontal() }, INTERVALO_ENTRE_IMAGENES_BANHORIZONTALES);
      } else {
         if(bh >= lista_banhorizontales.length) {bh=0}
         img_banhorizontal.attr("src",`Admin/${lista_banhorizontales[bh].imgh_ruta}`);
         setTimeout(() => { bh++; cambiarBanhorizontal() }, INTERVALO_ENTRE_IMAGENES_BANHORIZONTALES);
      }
   } else {
      // img_banhorizontal.attr('src',"Admin/Assets/Archivos_panel/Defaults/aw_banhorizontal.png");
      setTimeout(() => { bh=0; cambiarBanhorizontal() }, INTERVALO_ENTRE_IMAGENES_BANHORIZONTALES);
   }
}

function cambiarBancompleto() {
   if (lista_bancompletos.length > 0) {
      if (img_bancompleto.attr("src") == "") {
         img_bancompleto.attr("src",`Admin/${lista_bancompletos[0].imgc_ruta}`);
      } else {
         if(bc >= lista_bancompletos.length) {bc=0}
         img_bancompleto.attr("src",`Admin/${lista_bancompletos[bc].imgc_ruta}`);
      }
      setTimeout(() => { bc++; cambiarBancompleto() }, INTERVALO_ENTRE_IMAGENES_COMPLETAS);
   } else {
      // img_bancompleto.attr('src',"Admin/Assets/Archivos_panel/Defaults/aw_bancompleto.png");
      setTimeout(() => { bc=0; cambiarBancompleto() }, INTERVALO_ENTRE_IMAGENES_COMPLETAS);
   }
}

function cambiarTextosMarquee() {
   texto_marquee.marquee('destroy')
   if (lista_textos.length > 0) {
      if (texto_marquee.text() == "") {
         texto_marquee.text(`${lista_textos[0].text_spot}`);
         
         if(lista_textos[0].text_tipo == "1") {
            texto_marquee.text(`${lista_textos[0].text_spot}`);
            texto_marquee.css("background-color","#000");
            texto_marquee.css("color","#FFF");
         } else {
            hoy = moment().format("YYYY-MM-DD HH:mm:ss");
            momento_actual = moment(hoy);
            fecha_inicial_promo = moment(`${lista_textos[0].text_fecha_ini} ${lista_textos[0].text_hora_ini}`,"YYYY-MM-DD HH:mm:ss");
            fecha_final_promo = moment(`${lista_textos[0].text_fecha_fin} ${lista_textos[0].text_hora_fin}`,"YYYY-MM-DD HH:mm:ss");

            ya_inicio_dia_promo = fecha_inicial_promo.isBefore(momento_actual);
            ya_acabo_dia_promo = momento_actual.isAfter(fecha_final_promo);

            if (ya_inicio_dia_promo && !ya_acabo_dia_promo) {
               texto_marquee.text(`${lista_textos[0].text_spot}`);
               texto_marquee.css("background-color",lista_textos[0].text_fondo_color);
               texto_marquee.css("color",lista_textos[0].text_color);
            }
         }
      } else {
         if(t >= lista_textos.length) {t=0}
         
         if(lista_textos[t].text_tipo == "1") {
            texto_marquee.css("background-color","#000");
            texto_marquee.css("color","#FFF");
            texto_marquee.text(`${lista_textos[t].text_spot}`);
            
         } else {
            hoy = moment().format("YYYY-MM-DD HH:mm:ss");
            momento_actual = moment(hoy);
            fecha_inicial_promo = moment(`${lista_textos[t].text_fecha_ini} ${lista_textos[t].text_hora_ini}`,"YYYY-MM-DD HH:mm:ss");
            fecha_final_promo = moment(`${lista_textos[t].text_fecha_fin} ${lista_textos[t].text_hora_fin}`,"YYYY-MM-DD HH:mm:ss");

            ya_inicio_dia_promo = fecha_inicial_promo.isBefore(momento_actual);
            ya_acabo_dia_promo = momento_actual.isAfter(fecha_final_promo);

            if (ya_inicio_dia_promo && !ya_acabo_dia_promo) {
               texto_marquee.text(`${lista_textos[t].text_spot}`);
               texto_marquee.css("background-color",lista_textos[t].text_fondo_color);
               texto_marquee.css("color",lista_textos[t].text_color);
            }
         }
      }
      texto_marquee.marquee({
         //velocidad en milisegundos de la marquesina
         duration: VELOCIDAD_CINTILLA,
         //espacio en píxeles entre los tickers
         gap: 50,
         //tiempo en milisegundos antes de que la marquesina comience a animarse
         delayBeforeStart: 0,
         direction: 'left',
         //verdadero o falso: si la marquesina debe duplicarse para mostrar un efecto de flujo continuo
         duplicated: false
      });
      texto_marquee.bind("finished", () => {
         t++; cambiarTextosMarquee();
      })
   }
}