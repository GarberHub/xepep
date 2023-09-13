let today = new Date();
let dd = today.getDate();
let mm = today.getMonth()+1;
let yyyy = today.getFullYear();
 if(dd<10){
        dd='0'+dd
    } 
    if(mm<10){
        mm='0'+mm
    } 

today = yyyy+'-'+mm+'-'+dd;

sushi = jQuery("#sushi").val();
if(sushi == "true"){
  document.getElementById("fecha_entrega").setAttribute("min", today);
}
jQuery(document).on("change","#localidad_entrega",function(){
  if(sushi == "true"){
    localidad = this.value;
    if(localidad == "aspe"){
      localidad_formated = "Aspe";
      codigo_postal = '03680';

    }else if(localidad == "novelda"){
      localidad_formated = "Novelda";
      codigo_postal = '03660';

    }else if(localidad == "monforte"){
      localidad_formated = "Monforte del Cid";
      codigo_postal = '03670';
    }

    jQuery("#billing_city").val(localidad_formated);
    jQuery("#billing_postcode").val(codigo_postal);
    jQuery("#billing_state").val("A");
  }
})
jQuery("input[type='radio']").click(function(){
  if(sushi == "true"){
    entrega = jQuery(this).val();
    if(entrega == "envio"){
      jQuery("#localidadEntrega").attr("style","display:block!important");
    }else{
      jQuery("#localidadEntrega").attr("style","display:none!important");
    }
  }
})


jQuery('body').on('updated_checkout', function(){
  cp = jQuery("#billing_postcode").val();
  platodia = jQuery("#platodia").val();
  console.log(platodia);
  console.log(cp);
  if(platodia == "true"){
    if((cp == "03680" || cp == "03660" || cp == "03670")){
      jQuery("#place_order").css("display", "block");
      jQuery(".woocommerce-notices-wrapper").html("");
    }else{
      jQuery("#place_order").css("display", "none");
      jQuery(".woocommerce-notices-wrapper").first().html('<div class="woocommerce-error" role="error">Algunos de estos productos no se pueden enviar a tu localidad, solo se pueden entregar en Aspe, Monforte y Novelda.<br> Por favor modifique su dirección de entrega. </div>');
    }
  }
  console.log("updatesd");
});