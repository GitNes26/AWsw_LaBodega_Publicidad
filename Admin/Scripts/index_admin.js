'use strict';
$(document).ready(() => {
   const
      badges_videos_activos = document.querySelectorAll(".badge_videos_activos"),
      badge_banverticales_activos = document.querySelectorAll(".badge_banverticales_activos"),
      badge_banhorizontales_activos = document.querySelectorAll(".badge_banhorizontales_activos"),
      badges_bancompletos_activos = document.querySelectorAll(".badge_bancompletos_activos"),
      badge_textos_activos = document.querySelectorAll(".badge_textos_activos"),
      badge_textos_promo_activos = document.querySelectorAll(".badge_textos_promo_activos")
   ;

   pintarBadge(badges_videos_activos);
   pintarBadge(badge_banverticales_activos);
   pintarBadge(badge_banhorizontales_activos);
   pintarBadge(badges_bancompletos_activos);
   pintarBadge(badge_textos_activos);
   pintarBadge(badge_textos_promo_activos);

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
      url: "../Admin/Models/Usuario/App.php",
      type: "POST",
      data: datos,
      dataType: "json",
      success: (ajaxResponse) => {
         if (ajaxResponse.Resultado == "correcto")
            window.location.href = "../"
      }
   })
});