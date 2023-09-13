<?php
include_once __DIR__.'/../vendor/ApiRedsysREST/apiRedsys.php';

function gbi_form_pedido_rapido(){
    wp_enqueue_script( 'modal_complemento_js', plugin_dir_url( __FILE__ ). '../assets/js/modal_complementos.js', array(), '1.0', true );
    wp_enqueue_script( 'pedido_rapido_js', plugin_dir_url( __FILE__ ). '../assets/js/pedido_rapido.js', array(), '1.0', true );
    wp_enqueue_script( 'sweet_alert_js', '//cdn.jsdelivr.net/npm/sweetalert2@11', array(), '1.0', true );

    wp_localize_script( 'modal_complemento_js', 'ajax_url', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
    wp_localize_script( 'pedido_rapido_js', 'ajax_url', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

    ?>
        <style>
            .added_to_cart{
                display: none !important;
            }
        </style>
        <div class="row gbi_container">
            <div class="col-12 col-sm-6">
                <h4>Tu pedido</h4>
                <?php gbi_generar_tabla_carrito_rapido(); ?>
            </div>

            <div class="col-12 col-sm-6">
               <h4>Tu información</h4>

                <form id="form_compra_rapida">
                    <div class="row form-group">
                        <div class="col-12 col-sm-6">
                            <label for="nombre_cliente">Tu nombre</label>
                            <input type="text" id="nombre_cliente" style="border: 0;border-bottom: 3px solid #999900;" placeholder="Tu nombre" required class="form-control" />   
                        </div>
                        <div class="col-12 col-sm-6">
                            <label for="apellidos_cliente">Tus apellidos</label>
                            <input type="text" id="apellidos_cliente" style="border: 0;border-bottom: 3px solid #999900;" placeholder="Tus apellidos" required class="form-control" />
                        </div>
                        <div class="col-12 col-sm-6 ">
                            <label for="telefono_cliente">Teléfono de contacto</label>
                            <input type="tel" id="telefono_cliente" style="border: 0;border-bottom: 3px solid #999900;" placeholder="Tu teléfono" required>
                        </div>
                        <div class="col-12 col-sm-6 ">
                            <label for="metodo_envio">Método de envío</label>
							<div>
								<label>
									<input type="radio" name="metodo_envio" value="recogida_tienda"> Recoger en tienda
								</label>
								<br>
								<label>
									<input type="radio" name="metodo_envio" value="a_domicilio"> A domicilio
								</label>
							</div>
                        </div>
                    </div>

                    <div class="envio_a_domicilio" style="display:none">
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <label for="entrega_cliente">¿Donde entregamos la comida?</label>
                                <input type="text" id="entrega_cliente" class="form-control" style="border: 0;border-bottom: 3px solid #999900;" placeholder="Dirección de entrega">
                            </div>
                            <div class="col-12 col-sm-6">
                                <label for="poblacion_entrega">Población</label>
                                <select id="poblacion_entrega" class="form-control" style="border: 0;border-bottom: 3px solid #999900;">
                                    <option value="aspe">Aspe</option>
                                    <option value="novelda">Novelda</option>
                                    <option value="monforte">Monforte Del Cid</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <label for="metodo_pago">Método de pago</label>
                                <select id="metodo_pago_domicilio" class="form-control" style="border: 0;border-bottom: 3px solid #999900;">
                                    <option value="tarjeta">Tarjeta (Pago online)</option>
                                    <option value="tarjeta-domicilio">Tarjeta (Pago domicilio)</option>
                                    <option value="metalico">Metálico</option>
                                </select>
                            </div>
                            <div class="col-12 col-sm-6">
                                <label for="hora_entrega">Hora de entrega</label>
                                <select id="hora_entrega" class="form-control" style="border: 0;border-bottom: 3px solid #999900;">
                                    <option value="13:00">13:00</option>
                                    <option value="13:15">13:15</option>
                                    <option value="13:30">13:30</option>
                                    <option value="13:45">13:45</option>
                                    <option value="14:00">14:00</option>
                                    <option value="14:15">14:15</option>
                                    <option value="14:30">14:30</option>
                                    <option value="14:45">14:45</option>
                                    <option value="15:00">15:00</option>
                                </select>
                            </div>
                            <small style="color:red;">El pago se realizará en el momento de la entrega.</small>
                        </div>
                    </div>

                    <div class="envio_recogida_tienda" style="display:none">
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <label for="metodo_pago">Método de pago</label>
                                <select id="metodo_pago_tienda" class="form-control" style="border: 0;border-bottom: 3px solid #999900;">
                                    <option value="pago_tienda">Pago en tienda (Efectivo/Tarjeta)</option>
                                    <option value="tarjeta">Tarjeta (Pago online)</option>
                                </select>
                            </div>
                            <div class="col-12 col-sm-6">
                                <label for="hora_entrega">Hora de recogida</label>
                                <select id="hora_entrega" class="form-control" style="border: 0;border-bottom: 3px solid #999900;">
                                    <option value="13:00">13:00</option>
                                    <option value="13:15">13:15</option>
                                    <option value="13:30">13:30</option>
                                    <option value="13:45">13:45</option>
                                    <option value="14:00">14:00</option>
                                    <option value="14:15">14:15</option>
                                    <option value="14:30">14:30</option>
                                    <option value="14:45">14:45</option>
                                    <option value="15:00">15:00</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row" style="margin-top:10px">
                            <div class="col-12 col-sm-12">
                                <input type="checkbox" required> Tus datos personales se utilizarán para procesar tu pedido, mejorar tu experiencia en esta web y otros propósitos descritos en nuestra <a href="https://dev.xepep.com/politica-de-privacidad/" class="woocommerce-privacy-policy-link" target="_blank">política de privacidad</a>.</input>
                            </div>
                    </div>

                    <button id="gbi_generar_pedido">Realizar pedido</button>
                </form>
            </div>
        </div>
    <?php
}

add_shortcode( "gbi_form_pedido_rapido", "gbi_form_pedido_rapido");

function gbi_actualizar_tabla_pedido_rapido(){
    /*
    ?>
    <table class="table" id=gbi_productos_pedido_rapido>
        <thead>
            <tr>
                <td>Nombre del producto</td>
                <td>Cantidad</td>
                <td>Eliminar</td>
            </tr>
        </thead>
        <tbody>
            <?php
            $carrito = WC()->cart->get_cart();
            $platodeldia = false;
            if(count($carrito) == 0){
                echo "<p>No tienes productos en tu carrito</p>";
            }
            foreach($carrito as $item => $values) { 
                $_product =  wc_get_product( $values['data']->get_id()); 
                $terms = get_the_terms( $_product->get_id(), 'product_cat' );
                // foreach ( $terms as $categoria ) {
                //     if ( $categoria->slug == 'plato-del-dia' ) {
                //         $platodeldia = true;
                //     }
                // }
                //if($platodeldia == true){
                    if($values['quantity'] == 1){
                        $str = "ud";
                    }else{
                        $str = "uds";
                    }
                    ?>
                    <tr>
                        <td><?php echo $_product->get_title()?></td>
                        <td><input type="number" class="gbi_product_quantity" data-id="<?php echo $_product->get_id()?>" style="width:50% !important;min-width:50px" value="<?php echo $values['quantity']?>"> <?php echo $str?></td>
                        <td><button class="gbi_eliminar_carrito_pedido_rapido btn btn-warning" data-id="<?php echo $_product->get_id()?>">Eliminar</button>
                    </tr>
                    <?php
                    //$platodeldia = false;
                // }else{
                //     WC()->cart->remove_cart_item( $item );
                // }
            }

            $amount = number_format(WC()->cart->cart_contents_total + WC()->cart->tax_total, 2);

            ?>
            <tr>
                <td colspan="3">Subtotal del pedido: <?php echo number_format(WC()->cart->cart_contents_total,2)?>€</td>
            </tr>
            <tr>
                <td colspan="3">Impuestos: <?php echo number_format(WC()->cart->tax_total,2)?>€</td>
            </tr>
            <tr>
                <td colspan="2" id="total_carrito_rapido">Total del pedido: <?php echo $amount?>€</td>
                <td><button class="gbi_actualizar_pedido btn btn-success">Actualizar carrito</button></td>
                <input type="hidden" name="gb_total_carrito" value="<?php echo $amount?>">

            </tr>
        </tbody>
    </table>
    <?php
    */

    gbi_generar_tabla_carrito_rapido();
    
    wp_die();
}

add_action("wp_ajax_gbi_actualizar_tabla_pedido_rapido", "gbi_actualizar_tabla_pedido_rapido");
add_action("wp_ajax_nopriv_gbi_actualizar_tabla_pedido_rapido", "gbi_actualizar_tabla_pedido_rapido");


function gbi_eliminar_product_carrito_pedido_rapido(){
    $id_producto = $_REQUEST['id_producto'];

    if($id_producto != ""){
        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            if ( $cart_item['product_id'] == $id_producto ) {
                 WC()->cart->remove_cart_item( $cart_item_key );
                 echo true;
            }else{
                echo false;
            }
       }
    }
    wp_die();
}

add_action("wp_ajax_gbi_eliminar_product_carrito_pedido_rapido", "gbi_eliminar_product_carrito_pedido_rapido");
add_action("wp_ajax_nopriv_gbi_eliminar_product_carrito_pedido_rapido", "gbi_eliminar_product_carrito_pedido_rapido");


function gbi_actualizar_carrito_pedido_rapido(){
    $arr_producto = $_REQUEST['arr_product'];
    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        $id_product =   $cart_item['data']->get_id(); 
        for($i=0; $i<count($arr_producto); $i++){
            if($arr_producto[$i]['id_producto'] == $id_product){
                WC()->cart->set_quantity($cart_item_key, $arr_producto[$i]['unidades']);
            }
        }
    } 
    echo true;
    wp_die();
}

add_action("wp_ajax_gbi_actualizar_carrito_pedido_rapido", "gbi_actualizar_carrito_pedido_rapido");
add_action("wp_ajax_nopriv_gbi_actualizar_carrito_pedido_rapido", "gbi_actualizar_carrito_pedido_rapido");


function gbi_crear_pedido_rapido(){
    global $wpdb;
    $arr_producto = $_REQUEST['arr_product'];
    $nombre_cliente = $_REQUEST['nombre_cliente'];
    $apellidos_cliente = $_REQUEST['apellidos_cliente'];
    $metodo_envio = $_REQUEST['metodo_envio'];
    $poblacion_entrega = $_REQUEST['poblacion_entrega'];
    $metodo_pago = $_REQUEST['metodo_pago'];
    $hora_entrega = $_REQUEST['hora_entrega'];
    $entrega_cliente = $_REQUEST['entrega_cliente'];
    $telefono_cliente = $_REQUEST['telefono_cliente'];
    $estado = $_REQUEST['estado'];

    if($metodo_envio == "recogida_tienda"){
        $address = array(
            'first_name' => $nombre_cliente,
            'last_name'  => $apellidos_cliente,
            'company'    => '',
            'email'      => '',
            'phone'      => $telefono_cliente,
            'address_1'  => 'Calle Virgen de las Nieves 53',
            'address_2'  => '',
            'city'       => 'Aspe',
            'state'      => 'Alicante',
            'postcode'   => '03680',
            'country'    => 'ES'
        );

        if($metodo_pago == "tarjeta"){
            $post_excerpt = 'Pedido rápido recogida en tienda a las '.$hora_entrega.". El cliente ha pagado por REDSYS";
        }else{
            $post_excerpt = 'Pedido rápido recogida en tienda a las '.$hora_entrega.". El cliente paga en tienda";

        }
    }elseif($metodo_envio == "a_domicilio"){
        $address = array(
            'first_name' => $nombre_cliente,
            'last_name'  => $apellidos_cliente,
            'company'    => '',
            'email'      => '',
            'phone'      => $telefono_cliente,
            'address_1'  => $entrega_cliente,
            'address_2'  => '',
            'city'       => $poblacion_entrega,
            'state'      => 'Alicante',
            'postcode'   => '03680',
            'country'    => 'ES'
        );
        if($metodo_pago == "tarjeta"){
            $post_excerpt = 'Pedido rápido a domicilio. Entrega a las '.$hora_entrega.". El cliente ha pagado por REDSYS";
        }else if($metodo_pago == "tarjeta-domicilio"){
            $post_excerpt = 'Pedido rápido a domicilio. Entrega a las '.$hora_entrega.". El cliente ha pagará con tarjeta en el domicilio";
        }else{
            $post_excerpt = 'Pedido rápido a domicilio. Entrega a las '.$hora_entrega.". El cliente ha pagará en metálico";
        }
    }

    $order = wc_create_order();
    $order->set_customer_note($post_excerpt);
    for($i=0; $i<count($arr_producto); $i++){
        $id_producto = $arr_producto[$i]['id_producto'];
        $cantidad = $arr_producto[$i]['unidades'];
        $order->add_product( wc_get_product($id_producto), $cantidad); // This is an existing SIMPLE product
        
    }
    $order->set_address( $address, 'billing' );

    $arr_discounts = WC()->cart->get_applied_coupons();

    if(count($arr_discounts) > 0){
        foreach ($arr_discounts as $coupon_code) {

            $order->apply_coupon($coupon_code);
        }
    }


    $order->calculate_totals();
    $order_id = $order->save();
    if($estado == "pagado"){
        $order->update_status("processing", 'Pedido rápido', TRUE); 

    }else{

        $payment_gateways = WC()->payment_gateways->payment_gateways();
        $order->set_payment_method($payment_gateways['redsys']);
        $order->update_status("pending", 'Pedido rápido', TRUE);
        
    }
    update_post_meta($order->get_id(), "gbi_metodo_envio", $metodo_envio);
    update_post_meta($order->get_id(), "gbi_metodo_pago", $metodo_pago);
    update_post_meta($order->get_id(), "gbi_metodo_hora_entrega", $hora_entrega);
    WC()->cart->empty_cart(); 
    echo $order->get_id();
    //echo 6704;
    wp_die();


}

add_action("wp_ajax_gbi_crear_pedido_rapido", "gbi_crear_pedido_rapido");
add_action("wp_ajax_nopriv_gbi_crear_pedido_rapido", "gbi_crear_pedido_rapido");

function gbi_obtener_orden_creada_pedido_rapido(){
    $html = "";
    $order_id = $_REQUEST['id_orden'];
    $order = wc_get_order( $order_id );
    $nombre = $order->get_formatted_billing_full_name();
    $direccion = $order->get_formatted_billing_address();
    $total = $order->get_total();
    $metodo_envio = get_post_meta($order_id,"gbi_metodo_envio", true);
    $metodo_pago = get_post_meta($order_id, "gbi_metodo_pago", true);
    $hora_entrega = get_post_meta($order_id, "gbi_metodo_hora_entrega", true);
    $html.="<p class='text-center'>Hola ".$nombre.", hemos recibido tu pedido, nos ponemos manos a la obra.</p>";
    if($metodo_envio == "recogida_tienda"){
        $html.="<p>Puedes pasar por nuestra tienda a recogerlo.</p>";
    }else{
        $html.="<p>Esta es la dirección de entrega:<br>".$direccion.".</p>";
        $html.="<p>El método de pago seleccionado es ".$metodo_pago.".</p>";
    }
    $html.="<p>El total del pedido es: ".$total."€</p>";
    $html.="<p><b>Este es el resumen de tu pedido:</b>";
    foreach ( $order->get_items() as $item_id => $item ) {
        $product_name = $item->get_name();
        $quantity = $item->get_quantity();
        $total_product_line = $item->get_total() + $item->get_subtotal_tax();
        $html.="<br>Producto: ".$product_name." x ".$quantity." - ".number_format($total_product_line,2)."€";
    }
    $html.="</p>";
    if($metodo_envio != "recogida_tienda"){
        $html.="<p>Será entregado a las ".$hora_entrega."</p>";
    }

    echo $html;
    wp_die();

}

add_action("wp_ajax_gbi_obtener_orden_creada_pedido_rapido", "gbi_obtener_orden_creada_pedido_rapido");
add_action("wp_ajax_nopriv_gbi_obtener_orden_creada_pedido_rapido", "gbi_obtener_orden_creada_pedido_rapido");

function gb_generar_form_redsys(){
    $total_pedido = $_REQUEST["total_pedido"];
    $id_order = $_REQUEST["id_order"];
    $date = new DateTimeImmutable();
    $timestamp = $date->getTimestamp();
    $myRedsysTPV = array(
		'fuc' => '348516873',//'348516873',
		'terminal' =>'1',
		'kc' => 'sq7HjrUOBfKmC576ILgskD5srU870gJ7',//'i59YT6Ksb5eSeAcVCymPhp9t9wlsGYLG',
		'currency' => '978',//978-euros, 840-dólares, 826-libras, 392-yenes, 32-australargentino, 124-dólarcanadiense, 152-pesochileno, 170-pesocolombiano, 356-rupiaindia, 484-nuevopesomexicano, 604-nuevossoles, 75-6francosuizo, 986-realBrasileño, 937-bolívarfuerte, 949-liraturca
		'transactionType' => '0',//0 –Autorización, 1 –Preautorización, 2 –Confirmación de preautorización, 3 –Devolución Automática, 5 –Transacción Recurrente, 6 –Transacción Sucesiva, 7 –Pre-autenticación, 8 –Confirmación de pre-autenticación, 9 –Anulación Preautorización, O –Autorización en diferido, P–Confirmación de autorización en diferido, Q-Anulación de autorización en diferido, R–Cuota inicial diferido, S –Cuota sucesiva diferido
		'ConsumerLanguage' => '001',//Castellano-001,Inglés-002,Catalán-003,Francés-004,Alemán-005,Holandés-006,Italiano-007,Sueco-008,Portugués-009,Valenciano-010,Polaco-011,Gallego-012,Euskera-013,Danés-208
		'PayMethods' => 'C',//C: Sólo Tarjeta; R = Pago por Transferencia (solo, si tiene activo este método de pago); D = Domiciliacion (solo, si tiene activo este método de pago); T: Tarjeta + iupay 
		'merchantUrl' =>'https://' . $_SERVER['HTTP_HOST'] . '/gracias-por-tu-compra?id='.$_GET['id'],
		'UrlOK' => 'https://' . $_SERVER['HTTP_HOST'] . '/gracias-por-tu-compra?id='.$_GET['id'],
		'UrlKO' => 'https://' . $_SERVER['HTTP_HOST'] . '/gracias-por-tu-compra?id='.$_GET['id'].'&ko=1',
		'ProductDescription' => 'Compra en ' . $_SERVER['HTTP_HOST'],
		'MerchantName' => 'XEPEP COCINA TRADICIONAL',
		'order' => $timestamp,
		'amount' => ($total_pedido * 100),
		'machine' => new RedsysAPI,
		'action' =>  'https://sis-t.redsys.es:25443/sis/realizarPago',
		'signatureVersion' => 'HMAC_SHA256_V1',
		'merchantParameters' => '',
		'MerchantData' => array("id_order"=> $id_order),
		'signature' => ''
	);
	$myRedsysTPV['machine']->setParameter('DS_MERCHANT_AMOUNT', $myRedsysTPV['amount']);
	$myRedsysTPV['machine']->setParameter('DS_MERCHANT_ORDER', $myRedsysTPV['order']);
	$myRedsysTPV['machine']->setParameter('DS_MERCHANT_MERCHANTCODE', $myRedsysTPV['fuc']);
	$myRedsysTPV['machine']->setParameter('DS_MERCHANT_CURRENCY', $myRedsysTPV['currency']);
	$myRedsysTPV['machine']->setParameter('DS_MERCHANT_TRANSACTIONTYPE', $myRedsysTPV['transactionType']);
	$myRedsysTPV['machine']->setParameter('DS_MERCHANT_TERMINAL', $myRedsysTPV['terminal']);
	$myRedsysTPV['machine']->setParameter('DS_MERCHANT_CONSUMERLANGUAGE', $myRedsysTPV['ConsumerLanguage']);
	$myRedsysTPV['machine']->setParameter('DS_MERCHANT_PRODUCTDESCRIPTION', $myRedsysTPV['ProductDescription']);
	$myRedsysTPV['machine']->setParameter('DS_MERCHANT_MERCHANTNAME', $myRedsysTPV['MerchantName']);
	$myRedsysTPV['machine']->setParameter('DS_MERCHANT_MERCHANTDATA', $myRedsysTPV['MerchantData']);
	//$myRedsysTPV['machine']->setParameter('DS_MERCHANT_MERCHANTURL', $myRedsysTPV['merchantUrl']);
	$myRedsysTPV['machine']->setParameter('DS_MERCHANT_URLOK', $myRedsysTPV['UrlOK']);		
    //DS_MERCHANT_URL
	//$myRedsysTPV['machine']->setParameter('DS_MERCHANT_MERCHANTDATA', $myRedsysTPV['UrlOK']);		
	$myRedsysTPV['machine']->setParameter('DS_MERCHANT_URLKO', $myRedsysTPV['UrlKO']);
	$myRedsysTPV['merchantParameters'] = $myRedsysTPV['machine']->createMerchantParameters();
	$myRedsysTPV['signature'] = $myRedsysTPV['machine']->createMerchantSignature($myRedsysTPV['kc']);	
				
			
	$html='<form action="'.$myRedsysTPV['action'].'" enctype="application/x-www-form-urlencoded" id="form_redsys" method="post" name="form_redsys">
		<input type="hidden" name="Ds_SignatureVersion" value="'.$myRedsysTPV['signatureVersion'].'">
		<input type="hidden" name="Ds_MerchantParameters" value="'.$myRedsysTPV['merchantParameters'].'">
		<input type="hidden" name="Ds_Signature" value="'.$myRedsysTPV['signature'].'">
	     </form>';

    echo $html;
    wp_die();


}

add_action("wp_ajax_gb_generar_form_redsys", "gb_generar_form_redsys");
add_action("wp_ajax_nopriv_gb_generar_form_redsys", "gb_generar_form_redsys");

function gb_compra_redsys_callback(){
    var_dump($_REQUEST);
    $parameters_json = base64_decode($_REQUEST["Ds_MerchantParameters"]);
    $parameters = json_decode(stripslashes($parameters_json));
    $html="<div class='container'>";
    $response = $parameters->Ds_Response;
    var_dump($parameters);
    if(!is_null($response)){
        if($response < 100 && $parameters_json != ""){
            $merchantData = json_decode(urldecode(stripslashes($parameters->Ds_MerchantData)));
            $order_id = $merchantData->id_order;
            if($order_id != ""){
                $html.="<h1 style='text-align: center; color: #999900;'>Pago recibido del pedido Nº$order_id</h1>";
                $order = wc_get_order( $order_id );
                $order->update_status("processing", 'Pedido rápido REDSYS', TRUE); 
                $fecha = urldecode($parameters->Ds_Date);
                $hora = urldecode($parameters->Ds_Hour);
                $redsys_order = urldecode($parameters->Ds_Order);
                $redsys_auth_code = $parameters->Ds_AuthorisationCode;
                update_post_meta( $order_id, "_payment_date_redsys", $fecha);
                update_post_meta( $order_id, "_payment_hour_redsys", $hora);
                update_post_meta( $order_id, "_payment_order_number_redsys", $redsys_order);
                update_post_meta( $order_id, "_authorisation_code_redsys", $redsys_auth_code);
                
                $nombre = $order->get_formatted_billing_full_name();
                $direccion = $order->get_formatted_billing_address();
                $total = $order->get_total();
                $metodo_envio = get_post_meta($order_id,"gbi_metodo_envio", true);
                $metodo_pago = get_post_meta($order_id, "gbi_metodo_pago", true);
                $hora_entrega = get_post_meta($order_id, "gbi_metodo_hora_entrega", true);
                $html.="<p class='text-center'>Hola <b>".$nombre."</b>, hemos recibido tu pedido, nos ponemos manos a la obra.</p>";
                if($metodo_envio == "recogida_tienda"){
                    $html.="<p>Puedes pasar por nuestra tienda a recogerlo.</p>";
                }else{
                    $html.="<p>Esta es la dirección de entrega:<br>".$direccion.".</p>";
                    $html.="<p>El método de pago seleccionado es ".$metodo_pago.".</p>";
                }
                $html.="<p>El total del pedido es: ".$total."€</p>";
                $html.="<p><b>Este es el resumen de tu pedido:</b>";
                foreach ( $order->get_items() as $item_id => $item ) {
                    $product_name = $item->get_name();
                    $quantity = $item->get_quantity();
                    $total_product_line = $item->get_total() + $item->get_subtotal_tax();
                    $html.="<br>Producto: ".$product_name." x ".$quantity." - ".number_format($total_product_line,2)."€";
                }
                $html.="</p>";
                if($metodo_envio != "recogida_tienda"){
                    $html.="<p><b>Será entregado a las ".$hora_entrega."</b></p>";
                }
            }else{
                $html.="<h1 style='text-align: center; color: #999900;'>Error en el pago #XG101</h1>";
                $html.="<p>Parece que ha habido un problema con el pago, puedes ponerte en contacto con nosotros en el <a href='tel:629442767'>629 442 767</a></p>";
            }
        }else{
            $html.="<h1 style='text-align: center; color: #999900;'>Error en el pago #XG102</h1>";
            $html.="<p>Parece que ha habido un problema con el pago, puedes ponerte en contacto con nosotros en el <a href='tel:629442767'>629 442 767</a></p>";
        }
    

    }else{
        $html.="<h1 style='text-align: center; color: #999900;'>Error en el pago #XG103</h1>";
        $html.="<p>Parece que ha habido un problema con el pago, puedes ponerte en contacto con nosotros en el <a href='tel:629442767'>629 442 767</a></p>";

    }
    echo $html;

}

add_shortcode("gb_compra_redsys_callback", "gb_compra_redsys_callback");

function gbi_aplicar_cupon_carrito(){

    $discount = $_REQUEST["discount"];
    $coupon_exists = false;
    $coupon_not_applied = false;
    $error = false;
    $message = "";
    $result = [];

    if( wc_get_coupon_id_by_code( $discount ) ) {
        $coupon_exists = true;

    }else{
        $error = true;
        $message = "El cupón '$discount' no es válido";
    }

    if(!$error){

        $coupon = new WC_Coupon($discount);

        if( !WC()->cart->has_discount( $discount ) ) {
            
            $coupon_not_applied = true;

        }else{

            $error = false;
            $message = "¡Ya tienes el cupón '$discount' añadido en tu carrito!";

        }
    
        if( $coupon_exists && $coupon_not_applied ){

            WC()->cart->apply_coupon( $discount );
            $message = "¡Cupón '$discount' añadido a tu carrito!";
            $result["discount"] = $coupon->get_amount();
    
        }

    }

    $result["error"] = $error;
    $result["message"] = $message;
    $result["total"] = number_format(WC()->cart->cart_contents_total + WC()->cart->tax_total, 2);
    
    echo json_encode($result);
    wp_die();

}

add_action("wp_ajax_gbi_aplicar_cupon_carrito", "gbi_aplicar_cupon_carrito");
add_action("wp_ajax_nopriv_gbi_aplicar_cupon_carrito", "gbi_aplicar_cupon_carrito");

function gbi_eliminar_cupon_carrito(){

    $code = $_REQUEST["code"];
    $result = [];
    $result["remove"] = false;
    $result["remove"] = WC()->cart->remove_coupon( $code );

    if($result["remove"]){

        $coupon = new WC_Coupon($code);
        $result["total"] = number_format(WC()->cart->cart_contents_total + WC()->cart->tax_total + $coupon->get_amount(), 2);

    }

    echo json_encode($result);
    wp_die();

}

add_action("wp_ajax_gbi_eliminar_cupon_carrito", "gbi_eliminar_cupon_carrito");
add_action("wp_ajax_nopriv_gbi_eliminar_cupon_carrito", "gbi_eliminar_cupon_carrito");

function gbi_generar_tabla_carrito_rapido(){

    ?>
    <div id="gbi_table">
        <table class="table" id=gbi_productos_pedido_rapido>
            <thead>
                <tr>
                    <td><b>Nombre del producto</b></td>
                    <td><b>Cantidad</b></td>
                    <td><b>Eliminar</b></td>
                </tr>
            </thead>
            <tbody>
                <?php
                $carrito = WC()->cart->get_cart();
                $arr_discounts = WC()->cart->get_applied_coupons();
                
                $platodeldia = false;
                if(count($carrito) == 0){
                    echo "<p>No tienes productos en tu carrito</p>";
                    $amount = 0.00;
                }else{

                    foreach($carrito as $item => $values) { 
                        $_product =  wc_get_product( $values['data']->get_id()); 
                        $terms = get_the_terms( $_product->get_id(), 'product_cat' );
            
                        if($values['quantity'] == 1){
                            $str = "ud";
                        }else{
                            $str = "uds";
                        }
                        ?>
                        <tr>
                            <td><?php echo $_product->get_title()?></td>
                            <td><input type="number" class="gbi_product_quantity" data-id="<?php echo $_product->get_id()?>" style="width:50% !important;min-width:50px" value="<?php echo $values['quantity']?>"> <?php echo $str?></td>
                            <td><button class="gbi_eliminar_carrito_pedido_rapido btn btn-danger" data-id="<?php echo $_product->get_id()?>">Eliminar</button>
                        </tr>
                        <?php
        
                    }
                    $amount = number_format(WC()->cart->cart_contents_total + WC()->cart->tax_total, 2);

                }

                ?>
                <tr>
                    <td colspan="2">
                        <input type="text" name="cupon_cliente" id="cupon_cliente" placeholder="CUPÓN DESCUENTO">
                    </td>
                    <td>
                        <input type="button" class="gbi_aplicar_cupon btn btn-info" value="Aplicar">
                    </td>
                </tr>
                <input type="hidden" id="bloque_descuentos_carrito_rapido" value="">
                <?php 
                
                if(count($arr_discounts)){

                    foreach ($arr_discounts as $code) {

                        $coupon = new WC_Coupon($code);

                        ?>
                        <tr id="bloque_cupon_<?php echo $code; ?>">
                            <td colspan="2">Cupón <?php echo "<b>".strtoupper($code)."</b>: -".$coupon->amount."€"; ?></td>
                            <td><input type="button" class="gbi_eliminar_cupon btn btn-warning" data-code="<?php echo $code ?>" value="Eliminar cupón"></td>
                        </tr>
                        <?php

                    }
            
                }
                
                ?>
                
                <tr>
                    <td colspan="3"><b>SUBTOTAL DEL PEDIDO:</b> <?php echo number_format(WC()->cart->subtotal,2); ?>€</td>
                </tr>
                <tr>
                    <td colspan="3"><b>IMPUESTOS:</b> <?php echo number_format(WC()->cart->tax_total,2)?>€</td>
                </tr>
                
                <tr>
                    <td colspan="2" id="total_carrito_rapido"><b>TOTAL DEL PEDIDO:</b> <?php echo $amount ?>€</td>
                    <td><button class="gbi_actualizar_pedido btn btn-success">Actualizar carrito</button></td>
                    <input type="hidden" name="gb_total_carrito" value="<?php echo $amount?>">

                </tr>
            </tbody>
        </table>
    </div>
    <?php

}