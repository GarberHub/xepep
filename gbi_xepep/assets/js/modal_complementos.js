jQuery('body').on('adding_to_cart', function($thisbutton, data){


    
    id_producto = jQuery(data).data("product_id");

    jQuery.ajax({
        type : "post",
        url : ajax_url.ajaxurl, 
        data : {
            action: "comprobar_plato_dia_producto", 
            id_producto : id_producto,
        },
        error: function(response){
            //console.log(response);
        },
        success: function(respuesta) {
           console.log(respuesta);
           if(respuesta == true){
            jQuery("#modalComplementos").modal('show');
           }
          }
    })
  });