let envio = "recogida_tienda";
let id_order;
function gbi_add_spinner_to_table(){
    jQuery("#gbi_table").html('<div class="d-flex justify-content-center"><div class="spinner-grow text-success" role="status"><span class="sr-only">Añadiendo...</span></div></div>');
}

function gbi_update_cart_table(){
    jQuery.ajax({
        type : "post",
        url : ajax_url.ajaxurl, 
        data : {
            action: "gbi_actualizar_tabla_pedido_rapido", 
        },
        error: function(response){

        },
        success: function(respuesta) {
           //console.log(respuesta);
           jQuery("#gbi_table").html(respuesta);
           
          }
    })
}


function gbi_get_order_created(id_orden,pago){
    if(pago == "pagado"){
        gbi_add_spinner_to_table();
        jQuery.ajax({
            type : "post",
            url : ajax_url.ajaxurl, 
            data : {
                action: "gbi_obtener_orden_creada_pedido_rapido", 
                id_orden : id_orden,
            },
            error: function(response){

            },
            success: function(respuesta) {
            //console.log(respuesta);
                Swal.fire({
                    title: '<strong>Pedido realizado</strong>',
                    icon: 'success',
                    html: respuesta,
                    showCloseButton: true,
                    showCancelButton: false,
                    focusConfirm: false,
                    confirmButtonText: 'Aceptar',
                    showClass: {
                        popup: 'animate__animated animate__backInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__backOutDown'
                    }
                });
                jQuery(".gbi_container").attr("style",'background: #80808024; border-radius: 14px;')
                jQuery(".gbi_container").html("<h3 style='color:#999900;' class='text-center'>¡Pedido recibido!</h3>"+respuesta);
                
            }
        })
    }
}

function gbi_generar_pago(nombre_cliente, apellidos_cliente, metodo_envio, poblacion_entrega, metodo_pago, hora_entrega, entrega_cliente, telefono_cliente, arr_product){

    if(metodo_pago != "tarjeta"){
        console.log(arr_product);
        jQuery.ajax({
            type : "post",
            url : ajax_url.ajaxurl, 
            data : {
                action: "gbi_crear_pedido_rapido", 
                nombre_cliente : nombre_cliente,
                apellidos_cliente : apellidos_cliente,
                metodo_envio : metodo_envio,
                poblacion_entrega : poblacion_entrega,
                metodo_pago : metodo_pago,
                hora_entrega : hora_entrega,
                entrega_cliente : entrega_cliente,
                telefono_cliente : telefono_cliente,
                arr_product : arr_product,
                estado: "pagado"
            },
            error: function(response){
    
            },
            success: function(respuesta) {
                if(respuesta != 0){
                    id_order = respuesta;
                    gbi_get_order_created(respuesta,"pagado");
                }
            }
        })

    }else{

        console.log("Redsys");
        
        jQuery.ajax({
            type : "post",
            url : ajax_url.ajaxurl, 
            data : {
                action: "gbi_crear_pedido_rapido", 
                nombre_cliente : nombre_cliente,
                apellidos_cliente : apellidos_cliente,
                metodo_envio : metodo_envio,
                poblacion_entrega : poblacion_entrega,
                metodo_pago : metodo_pago,
                hora_entrega : hora_entrega,
                entrega_cliente : entrega_cliente,
                telefono_cliente : telefono_cliente,
                arr_product : arr_product,
                estado: "pendiente"
            },
            error: function(response){
    
            },
            success: function(respuesta) {
                if(respuesta != 0){
                    gbi_get_order_created(respuesta, "redsys");
                    id_order = respuesta;
                    console.log(respuesta);

                    jQuery.ajax({
                        type : "post",
                        url : ajax_url.ajaxurl, 
                        data : {
                            action: "gb_generar_form_redsys", 
                            total_pedido : total_pedido,
                            id_order : id_order,
                        },
                        error: function(response){
                
                        },
                        success: function(respuesta) {
                            console.log(respuesta);
                            form = respuesta;
                            jQuery(".gbi_container").append(form);
                            jQuery("#form_redsys").unbind('submit').submit();

                        }
                    })
                }
            }
        })

    }
}

jQuery('body').on('added_to_cart', function(){
    gbi_update_cart_table();
})

jQuery('body').on('adding_to_cart', function(){
    gbi_add_spinner_to_table();
})

jQuery('body').on('click',".gbi_eliminar_carrito_pedido_rapido", function(){
    gbi_add_spinner_to_table();
})



jQuery(document).ready(function(){
	jQuery("input[type='radio'][name='metodo_envio']").change(function() {
		if (this.value == "a_domicilio") {
            jQuery(".envio_a_domicilio").attr("style", "display:block");
            jQuery(".envio_recogida_tienda").attr("style", "display:none");
            envio = "a_domicilio";
        }else{
            jQuery(".envio_a_domicilio").attr("style", "display:none");
            jQuery(".envio_recogida_tienda").attr("style", "display:block");
            envio = "recogida_tienda";
        }
	});

    jQuery("body").on('click',".gbi_eliminar_carrito_pedido_rapido",function(){
        id_producto = jQuery(this).data("id");
        jQuery.ajax({
            type : "post",
            url : ajax_url.ajaxurl, 
            data : {
                action: "gbi_eliminar_product_carrito_pedido_rapido", 
                id_producto : id_producto,
            },
            error: function(response){
    
            },
            success: function(respuesta) {
               //console.log(respuesta);
               if(respuesta == true){
                    gbi_update_cart_table();
               }
              }
        })
    })

    /*
    jQuery("body").on("submit","#form_compra_rapida",function(e){
        e.preventDefault()
        let arr_product = new Array();
        nombre_cliente = jQuery("#nombre_cliente").val();
        apellidos_cliente = jQuery("#apellidos_cliente").val();
        metodo_envio = jQuery("input[name=metodo_envio]:checked").val();
        poblacion_entrega = jQuery("#poblacion_entrega").val();
        metodo_pago = jQuery("#metodo_pago").val();
        hora_entrega = jQuery("#hora_entrega").val();
        entrega_cliente = jQuery("#entrega_cliente").val();
        telefono_cliente = jQuery("#telefono_cliente").val();
        
        jQuery( ".gbi_product_quantity" ).each(function( i ) {
            id_producto = jQuery(this).data("id");
            quantity = jQuery(this).val();
            arr_product[arr_product.length] = {"id_producto":id_producto, "unidades": quantity} 
        });
        //gbi_add_spinner_to_table();
        if(arr_product.length>0){
            total_pedido = jQuery("input[name=gb_total_carrito]").val();
            precio_minimo = true;

            if(parseInt(total_pedido) < 7.90){
                precio_minimo = false;
            }

            if(precio_minimo){

                jQuery(".gbi_container").html('<div class="d-flex justify-content-center"><div class="spinner-grow text-success" role="status"><span class="sr-only">Añadiendo...</span></div></div>');

                if(metodo_pago != "tarjeta"){
                    console.log(arr_product);
                    jQuery.ajax({
                        type : "post",
                        url : ajax_url.ajaxurl, 
                        data : {
                            action: "gbi_crear_pedido_rapido", 
                            nombre_cliente : nombre_cliente,
                            apellidos_cliente : apellidos_cliente,
                            metodo_envio : metodo_envio,
                            poblacion_entrega : poblacion_entrega,
                            metodo_pago : metodo_pago,
                            hora_entrega : hora_entrega,
                            entrega_cliente : entrega_cliente,
                            telefono_cliente : telefono_cliente,
                            arr_product : arr_product,
                            estado: "pagado"
                        },
                        error: function(response){
                
                        },
                        success: function(respuesta) {
                            if(respuesta != 0){
                                id_order = respuesta;
                                gbi_get_order_created(respuesta,"pagado");
                            }
                        }
                    })

                }else{

                    console.log("Redsys");
                    
                    jQuery.ajax({
                        type : "post",
                        url : ajax_url.ajaxurl, 
                        data : {
                            action: "gbi_crear_pedido_rapido", 
                            nombre_cliente : nombre_cliente,
                            apellidos_cliente : apellidos_cliente,
                            metodo_envio : metodo_envio,
                            poblacion_entrega : poblacion_entrega,
                            metodo_pago : metodo_pago,
                            hora_entrega : hora_entrega,
                            entrega_cliente : entrega_cliente,
                            telefono_cliente : telefono_cliente,
                            arr_product : arr_product,
                            estado: "pendiente"
                        },
                        error: function(response){
                
                        },
                        success: function(respuesta) {
                            if(respuesta != 0){
                                gbi_get_order_created(respuesta, "redsys");
                                id_order = respuesta;
                                console.log(respuesta);

                                jQuery.ajax({
                                    type : "post",
                                    url : ajax_url.ajaxurl, 
                                    data : {
                                        action: "gb_generar_form_redsys", 
                                        total_pedido : total_pedido,
                                        id_order : id_order,
                                    },
                                    error: function(response){
                            
                                    },
                                    success: function(respuesta) {
                                        console.log(respuesta);
                                        form = respuesta;
                                        jQuery(".gbi_container").append(form);
                                        jQuery("#form_redsys").unbind('submit').submit();
            
                                        }
                                })
                            }
                        }
                    })

                }

            }else{

                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'No puedes realizar pedidos inferiores a 7.90€.',
                })

            }

            
        }else{
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Parece que el carrito está vacío, añade algún producto antes de realizar el pedido.',
              })
        }
    })
    */

    jQuery("body").on("submit","#form_compra_rapida",function(e){
        e.preventDefault();
        
        let arr_product = new Array();
        total_pedido = jQuery("input[name=gb_total_carrito]").val();
        precio_minimo = false;
        hay_productos = false;


        // Recogemos el total de productos
        jQuery( ".gbi_product_quantity" ).each(function( i ) {
            id_producto = jQuery(this).data("id");
            quantity = jQuery(this).val();
            arr_product[arr_product.length] = {"id_producto":id_producto, "unidades": quantity} 
        });


        // Hay productos
        if(arr_product.length > 0){
            hay_productos = true;

        }else{
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Parece que el carrito está vacío, añade algún producto antes de realizar el pedido.',
            })

            return;
        }


        // Hay precio mínimo
        if(parseInt(total_pedido) >= 7.90){
            precio_minimo = true;

        }else{
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'No puedes realizar pedidos inferiores a 7.90€.',
            })

            return;

        }


        if(precio_minimo && hay_productos){

            if(envio == "recogida_tienda"){

                nombre_cliente = jQuery("#nombre_cliente").val();
                apellidos_cliente = jQuery("#apellidos_cliente").val();
                metodo_envio = jQuery("input[name=metodo_envio]:checked").val();
                poblacion_entrega = "";
                metodo_pago = jQuery("#metodo_pago_tienda").val();
                hora_entrega = jQuery("#hora_entrega").val();
                entrega_cliente = "";
                telefono_cliente = jQuery("#telefono_cliente").val();
    
                console.log("nombre_cliente: "+nombre_cliente);
                console.log("apellidos_cliente: "+apellidos_cliente);
                console.log("telefono_cliente: "+telefono_cliente);
                console.log("metodo_envio: "+metodo_envio);
                console.log("metodo_pago: "+metodo_pago);
                console.log("hora_entrega: "+hora_entrega);

                jQuery(".gbi_container").html('<div class="d-flex justify-content-center"><div class="spinner-grow text-success" role="status"><span class="sr-only">Añadiendo...</span></div></div>');

                gbi_generar_pago(nombre_cliente, apellidos_cliente, metodo_envio, poblacion_entrega, metodo_pago, hora_entrega, entrega_cliente, telefono_cliente, arr_product);
    
            }else{
    
                nombre_cliente = jQuery("#nombre_cliente").val();
                apellidos_cliente = jQuery("#apellidos_cliente").val();
                metodo_envio = jQuery("input[name=metodo_envio]:checked").val();
                poblacion_entrega = jQuery("#poblacion_entrega").val();
                metodo_pago = jQuery("#metodo_pago_domicilio").val();
                hora_entrega = jQuery("#hora_entrega").val();
                entrega_cliente = jQuery("#entrega_cliente").val();
                telefono_cliente = jQuery("#telefono_cliente").val();
          
                //gbi_add_spinner_to_table();
                
    
                jQuery(".gbi_container").html('<div class="d-flex justify-content-center"><div class="spinner-grow text-success" role="status"><span class="sr-only">Añadiendo...</span></div></div>');

                /*
                if(metodo_pago != "tarjeta"){
                    console.log(arr_product);
                    jQuery.ajax({
                        type : "post",
                        url : ajax_url.ajaxurl, 
                        data : {
                            action: "gbi_crear_pedido_rapido", 
                            nombre_cliente : nombre_cliente,
                            apellidos_cliente : apellidos_cliente,
                            metodo_envio : metodo_envio,
                            poblacion_entrega : poblacion_entrega,
                            metodo_pago : metodo_pago,
                            hora_entrega : hora_entrega,
                            entrega_cliente : entrega_cliente,
                            telefono_cliente : telefono_cliente,
                            arr_product : arr_product,
                            estado: "pagado"
                        },
                        error: function(response){
                
                        },
                        success: function(respuesta) {
                            if(respuesta != 0){
                                id_order = respuesta;
                                gbi_get_order_created(respuesta,"pagado");
                            }
                        }
                    })

                }else{

                    console.log("Redsys");
                    
                    jQuery.ajax({
                        type : "post",
                        url : ajax_url.ajaxurl, 
                        data : {
                            action: "gbi_crear_pedido_rapido", 
                            nombre_cliente : nombre_cliente,
                            apellidos_cliente : apellidos_cliente,
                            metodo_envio : metodo_envio,
                            poblacion_entrega : poblacion_entrega,
                            metodo_pago : metodo_pago,
                            hora_entrega : hora_entrega,
                            entrega_cliente : entrega_cliente,
                            telefono_cliente : telefono_cliente,
                            arr_product : arr_product,
                            estado: "pendiente"
                        },
                        error: function(response){
                
                        },
                        success: function(respuesta) {
                            if(respuesta != 0){
                                gbi_get_order_created(respuesta, "redsys");
                                id_order = respuesta;
                                console.log(respuesta);

                                jQuery.ajax({
                                    type : "post",
                                    url : ajax_url.ajaxurl, 
                                    data : {
                                        action: "gb_generar_form_redsys", 
                                        total_pedido : total_pedido,
                                        id_order : id_order,
                                    },
                                    error: function(response){
                            
                                    },
                                    success: function(respuesta) {
                                        console.log(respuesta);
                                        form = respuesta;
                                        jQuery(".gbi_container").append(form);
                                        jQuery("#form_redsys").unbind('submit').submit();
            
                                    }
                                })
                            }
                        }
                    })

                }
                */

                gbi_generar_pago(nombre_cliente, apellidos_cliente, metodo_envio, poblacion_entrega, metodo_pago, hora_entrega, entrega_cliente, telefono_cliente, arr_product);
    
            }
            
        }

    });

    jQuery("body").on('click',".gbi_actualizar_pedido",function(){

        let arr_product = new Array();

        jQuery( ".gbi_product_quantity" ).each(function( i ) {
            id_producto = jQuery(this).data("id");
            quantity = jQuery(this).val();
            arr_product[arr_product.length] = {"id_producto":id_producto, "unidades": quantity} 
        });
        gbi_add_spinner_to_table();

        jQuery.ajax({
            type : "post",
            url : ajax_url.ajaxurl, 
            data : {
                action: "gbi_actualizar_carrito_pedido_rapido", 
                arr_product : arr_product,
            },
            error: function(response){
    
            },
            success: function(respuesta) {
               if(respuesta == true){
                    gbi_update_cart_table();
               }
              }
        })
        

    })

    jQuery("body").on("click", ".gbi_aplicar_cupon", function(e){

        e.preventDefault();

        Swal.showLoading();
        discount = jQuery("#cupon_cliente").val();

        if(discount != ""){

            jQuery.ajax({
                type : "post",
                url : ajax_url.ajaxurl,
                data : {
                    action : "gbi_aplicar_cupon_carrito",
                    discount : discount
                },
                error : function(response){

                },
                success : function(response){

                    result = JSON.parse(response);
                    console.log(result);

                    if(result.error == true){
                        Swal.fire({
                            icon: 'warning',
                            title: result.message,
                        });

                      
                    }else{

                        Swal.fire({
                            icon: 'success',
                            title: result.message,
                        });

                        jQuery("#bloque_descuentos_carrito_rapido").after(
                            '<tr id="bloque_cupon_'+discount+'">'+
                                '<td colspan="2">Cupón <b>'+discount.toUpperCase()+'</b>: -'+result.discount+'€</td>'+
                                '<td><input type="button" class="gbi_eliminar_cupon btn btn-warning" data-code="'+discount+'" value="Eliminar cupón"></td>'+
                            '</tr>'
                        );

                        jQuery("#total_carrito_rapido").html("<b>TOTAL DEL PEDIDO:</b> "+result.total+"€");

                    }
                }
            })

        }else{

            Swal.fire({
                icon: 'error',
                title: 'No has aplicado un ningún cupon!',
            })
        }

    })

    jQuery("body").on("click", ".gbi_eliminar_cupon", function(e){

        e.preventDefault();

        Swal.showLoading();
        code = jQuery(this).data("code");

        jQuery.ajax({
            type : "post",
            url : ajax_url.ajaxurl,
            data : {
                action : "gbi_eliminar_cupon_carrito",
                code : code,
            },
            error : function(response){

            },
            success : function(response){
                console.log("ELIMINAR CUPON");
                result = JSON.parse(response);
                
                console.log(result);
                if(result.remove){

                    Swal.fire({
                        icon: 'success',
                        title: 'Cupón eliminado del carrito!',
                    })

                    jQuery("#bloque_cupon_"+code).remove();
                    jQuery("#total_carrito_rapido").html("<b>TOTAL DEL PEDIDO:</b> "+result.total+"€");

                }

            }
        })

    })

})