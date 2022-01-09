'use strict';
$(document).ready(() => {
   const
      badges_videos_activos = document.querySelectorAll(".badge_videos_activos"),
      badges_banverticales_activos = document.querySelectorAll(".badge_banverticales_activos"),
      badges_banhorizontales_activos = document.querySelectorAll(".badge_banhorizontales_activos"),
      badges_bancompletos_activos = document.querySelectorAll(".badge_bancompletos_activos"),
      badges_textos_activos = document.querySelectorAll(".badge_textos_activos"),
      badges_textos_promo_activos = document.querySelectorAll(".badge_textos_promo_activos")
   ;

   pintarBadge(badges_videos_activos);
   pintarBadge(badges_banverticales_activos);
   pintarBadge(badges_banhorizontales_activos);
   pintarBadge(badges_bancompletos_activos);
   pintarBadge(badges_textos_activos);
   pintarBadge(badges_textos_promo_activos);

   function pintarBadge(badges) {
      badges.forEach(badge => {
      let cantidad = Number(badge.innerHTML);
      if (cantidad > 0 ) {
         badge.classList.remove("bg-danger");
         badge.classList.add("bg-success")
      } else {
         badge.classList.remove("bg-success");
         badge.classList.add("bg-danger");
      }
      })
   }
});


// /* CERRAR SESION
const btn_cerrar_sesion = document.getElementById("btn_cerrar_sesion")
const i = btn_cerrar_sesion.querySelector("i")
$("#btn_cerrar_sesion").mouseover(function () {
   i.classList.remove("fa-door-closed");
   i.classList.add("fa-door-open");
})
$("#btn_cerrar_sesion").mouseleave(function () {
   i.classList.remove("fa-door-open");
   i.classList.add("fa-door-closed");
})

$("#btn_cerrar_sesion").click((e) => {
   e.preventDefault();
   let datos = {accion:"cerrar_sesion"};
   $.ajax({
      url: "../Models/Usuario/App.php",
      type: "POST",
      data: datos,
      dataType: "json",
      success: (ajaxResponse) => {
         if (ajaxResponse.Resultado == "correcto")
            window.location.href = "../../"
      }
   })
});

//Mostrar imagen en grande en hover
$(`.tooltip_imagen`).fadeOut(1);
$(`.tooltip_video`).fadeOut(1);

$(".td_img").hover(function () {
      // over
      // console.log("dentro");
      let id = $(this).attr("data-id");
      let tooltip_imagen = $(`img[data-id='${id}'].tooltip_imagen`)
      tooltip_imagen.fadeIn("fast");
   }, function () {
      // out
      // console.log("fuera");
      let id = $(this).attr("data-id");
      let tooltip_imagen = $(`img[data-id='${id}'].tooltip_imagen`)
      tooltip_imagen.fadeOut("fast");
   }
);

$(".td_video").hover(function () {
   // over
   // console.log("dentro video");
   let id = $(this).attr("data-id");
   let tooltip_video = $(`video[data-id='${id}'].tooltip_video`)
   tooltip_video.fadeIn("fast");
}, function () {
   // out
   // console.log("fuera video");
   let id = $(this).attr("data-id");
   let tooltip_video = $(`video[data-id='${id}'].tooltip_video`)
   tooltip_video
   tooltip_video.fadeOut("fast");
}
);