<?php
add_action( 'woocommerce_checkout_billing', 'generarCamposSushi', 10, 1 );

function generarCamposSushi( $order_id ) {

    $sushi = false;

    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        // Comprobamos si hay productos que no sean virtuales
        //if ( ! $cart_item['data']->is_virtual() ) $sushi = true;

        $tag_ids =  $cart_item['tag_ids'][0];

        $terms = get_the_terms( $cart_item['product_id'], 'product_tag' );
        foreach ( $terms as $etiqueta ) {
            if ( $etiqueta->slug == 'sushi' ) {
                $sushi = true;
            }
        }
        //var_dump( $sushi );
    }

    if ( $sushi ) {
        ?>
            <input type='hidden' id='sushi' value='true'>
            <div class="" style="margin-bottom: 4%;padding:20px;background-color: #a2a2a214;color:black;border-top: 3px solid #673631;">
                <div style="margin-bottom:3%;">
                    <i class="fas fa-fish" style="color: #673631;font-size:23px;"></i><span style="text-align:center;font-size: 24px;color:#999900;padding-left: 10px;">¿Cuando quieres recibir tu SUSHI?</span>
                </div>
                <b>Recuerda que:</b><br>
                <ul style='margin-top:6px;'>
                    <li>El horario del servicio de medio día es de Lunes a Sábado hasta las 15:00. Encargos hasta las 14:00.</li>
                    <li>El horario del servicio nocturno es de Viernes a Sábado de 19:00 a 22:00. Encargos hasta las 22:00</li>
                    <li>La entrega a domicilio se realizará de Lunes a Viernes (mediodia) y de Viernes a Sábado (medio día y noche).</li>
                    <li>Entrega en Aspe, Novelda y Monforte del Cid.</li>
                </ul>
                <p><span style=''><b>Importante:</b> Necesitamos 1 hora para preparar tu pedido.</span></p>
                <h5>Datos de entrega</h5>
                <p class='form-row form-row-first'><label for='fecha_entrega'>Fecha </label>
                <input type='date' name='fecha_entrega' id='fecha_entrega' style='height: 35px;'></p>
                <p class='form-row form-row-last'><label for='horario_entrega'>Hora</label>
                <input type='time' name='horario_entrega' id='horario_entrega' style='height: 35px; width:100%'></p>
                <div id='entregaSushi'>
                    <label><input type="radio" name="entrega" value="recogida" checked> Lo recojo en tienda</label>&nbsp;&nbsp;
                    <label><input type="radio" name="entrega" value="envio"> Me lo envias a casa</label>
                </div>
                <div id='localidadEntrega' style='display:none!important'>
                    <br>
                    <label for='localidad_entrega'>Localidad</label>
                    <br>
                    <select name='localidad_entrega' id='localidad_entrega'>
                        <option value='aspe'>Aspe</option>
                        <option value='novelda'>Novelda</option>
                        <option value='monforte'>Monforte del Cid</option>
                    </select>
                </div>
                <br>
            </div>
        <?php
    }else{
        echo "<input type='hidden' id='sushi' value='false'>";
    }
}

add_action( 'woocommerce_checkout_update_order_meta', 'guardarCamposSushi' );

function guardarCamposSushi( $order_id ) {
    if ( !empty( $_POST['fecha_entrega'] ) ) {
        update_post_meta( $order_id, 'fecha_entrega', sanitize_text_field( $_POST['fecha_entrega'] ) );
    }
    if ( !empty( $_POST['horario_entrega'] ) ) {
        update_post_meta( $order_id, 'horario_entrega', sanitize_text_field( $_POST['horario_entrega'] ) );
    }
    if ( !empty( $_POST['localidad_entrega'] ) ) {
        update_post_meta( $order_id, 'localidad_entrega', sanitize_text_field( $_POST['localidad_entrega'] ) );
    }
    if ( !empty( $_POST['entrega'] ) ) {
        update_post_meta( $order_id, 'entrega', sanitize_text_field( $_POST['entrega'] ) );
    }
}

add_action( 'woocommerce_admin_order_data_after_billing_address', 'mostrarCamposSushiWP', 10, 1 );

function mostrarCamposSushiWP( $order ) {
    if ( get_post_meta( $order->id, 'fecha_entrega', true ) != null ) {
        echo '<h4>Detalles de entrega</h4>';
        echo '<ul>';
            echo '<li><b>' . __( 'Fecha de entrega:' ).'</b> '. get_post_meta( $order->id, 'fecha_entrega', true ) . '</li>';
            echo '<li><b>' . __( 'Horario de entrega:' ).'</b> '. get_post_meta( $order->id, 'horario_entrega', true ) . '</li>';
            echo '<li><b>' . __( 'Localidad de entrega:' ).'</b> '. get_post_meta( $order->id, 'localidad_entrega', true ) . '</li>';
            echo '<li><b>' . __( 'Método de envío:' ).'</b> '. get_post_meta( $order->id, 'entrega', true ) . '</li>';
        echo '</ul>';
    }
}

add_filter( 'woocommerce_checkout_fields', 'default_values_checkout_fields' );

function default_values_checkout_fields( $fields ) {

    $sushi = false;

    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        // Comprobamos si hay productos que no sean virtuales
        //if ( ! $cart_item['data']->is_virtual() ) $sushi = true;

        $tag_ids =  $cart_item['tag_ids'][0];

        $terms = get_the_terms( $cart_item['product_id'], 'product_tag' );
        foreach ( $terms as $etiqueta ) {
            if ( $etiqueta->slug == 'sushi' ) {
                $sushi = true;
            }
        }
        //var_dump( $sushi );

        if ( $sushi ) {
            $fields['billing']['billing_postcode']['default'] = '03680';
            $fields['billing']['billing_city']['default'] = 'Aspe';
            $fields['billing']['billing_state']['default'] = 'A';

            $fields['shipping']['shipping_postcode']['default'] = '03680';
            $fields['shipping']['shipping_city']['default'] = 'Aspe';
            $fields['shipping']['shipping_state']['default'] = 'A';
        }

        return $fields;
    }
}


add_filter( 'woocommerce_checkout_fields', 'gbi_comprobar_platos_dia_pedido' );

function gbi_comprobar_platos_dia_pedido( $fields ) {

    $platodeldia = false;

    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        // Comprobamos si hay productos que no sean virtuales
        //if ( ! $cart_item['data']->is_virtual() ) $sushi = true;

        $tag_ids =  $cart_item['tag_ids'][0];

        $terms = get_the_terms( $cart_item['product_id'], 'product_cat' );
        foreach ( $terms as $categoria ) {
            if ( $categoria->slug == 'plato-del-dia' ) {
                $platodeldia = true;
            }
        }

        if ( $platodeldia ) {
            $fields['billing']['billing_postcode']['default'] = '03680';
            $fields['billing']['billing_city']['default'] = 'Aspe';
            $fields['billing']['billing_state']['default'] = 'A';

            $fields['shipping']['shipping_postcode']['default'] = '03680';
            $fields['shipping']['shipping_city']['default'] = 'Aspe';
            $fields['shipping']['shipping_state']['default'] = 'A';
        }

        return $fields;
    }
}



add_action ("woocommerce_checkout_before_customer_details", "gbi_comprobar_platos_dia_field");

function bbloomer_change_default_checkout_state() {
  return 'OR'; // state code
}

function gbi_comprobar_platos_dia_field(){
    $platodeldia = false;

    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        // Comprobamos si hay productos que no sean virtuales
        //if ( ! $cart_item['data']->is_virtual() ) $sushi = true;

        $tag_ids =  $cart_item['tag_ids'][0];

        $terms = get_the_terms( $cart_item['product_id'], 'product_cat' );
        foreach ( $terms as $categoria ) {
            if ( $categoria->slug == 'plato-del-dia' ) {
                $platodeldia = true;
            }
        }
    }
    if($platodeldia == true){
        echo "<input type='hidden' id='platodia' value='true'>";
    }else{
        echo "<input type='hidden' id='platodia' value='false'>";
        
    }
}


function gbi_modal_complementos(){
    ?>
    <style>
        .gbi_addcart{
    
        }
        .col-12.col-sm-4.gbi_bloque.woocommerce{
            max-width: 300px;
        }
        .col-sm-12.col-12.gbi_imagen{
            min-height: 255px;
            max-height: 260px;
        }
        .gbi_bloque{
            text-align: center;
    
        }

        .gbi_cerrar{
            background-color: #663333;
            color: #999900;
            border-color: #663333;
        }
        .gbi_cerrar:hover{
            color: white;
            background-color: #999900;
            border-color: #999900;
        }

        .gbi_cerrar:active{
            color: white !important;
            background-color: #663333 !important;
            border-color: #663333 !important; 
        }
        </style>
    
    <div class="modal fade" tabindex="-1" id="modalComplementos">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" style="text-align:center">¿Algún complemento?</h5>
          </div>
          <div class="modal-body">
          <h4 style="text-align:center">¿Quieres añadir alguno de estos complementos a tu carrito?</h4>
    
            <div class="row" style="justify-content:center">
                <?php
                    $productos = wc_get_products(array(
                        'category' => array("complementos"),
                        'status' => 'publish',
                    ));
                    foreach ($productos as $product){
                        $image_id = $product->get_image_id();
                        $url_imagen = wp_get_attachment_url($image_id);
                        ?>
                            <div class="col-12 col-sm-4 gbi_bloque woocommerce">
                                <div class="col-sm-12 col-12 gbi_imagen">
                                <?php echo $product->get_image('woocommerce_single', array("loading" => "lazyload"))?>
                                </div>
                                <div class="col-sm-12 col-12">
                                    <h3 class="gbi_nombre_producto"><?php echo $product->get_name()?></h3>
                                </div>
                                <div class="col-sm-12 col-12 ">
                                    <p class="gbi_precio"><?php echo number_format($product->get_regular_price(),2)?>€</p>
                                </div>
                                <div class="col-sm-12 col-12">
                                    <p>
                                        <a href="?add-to-cart=<?php echo $product->get_id()?>" data-quantity="1" class="gbi_addcart button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo $product->get_id()?>" data-product_sku="" aria-label="Añadir a tu carrito" rel="nofollow">Añadir al carrito</a>
                                    </p>
                                </div>
                            </div>
                        <?php
                    }
                ?>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary gbi_cerrar" data-bs-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
    <?php
    }
    function gbi_insertar_modal(){
        global $post;
        if($post->post_name == "plato-del-dia" || $post->post_name == "pedido-rapido"){
            add_action("wp_body_open", "gbi_modal_complementos");
        }
    }

    add_action("template_redirect", "gbi_insertar_modal");

    /*
    * Añadir hora de entrega en el checkout
    */
    function woo_custom_field_checkout($checkout) {
        echo '<div id="additional_checkout_field">';
        woocommerce_form_field( 'hora_entrega', array( // Identificador del campo 
        'type'          => 'select',
        'class'         => array('my-field-class form-row-wide'),
        'required'      => true,            // Aquí muestra que no es obligatorio, si queres que sea obligatorio pon 'True' en vez de 'False'
        'label'       => __('Hora de entrega. '),   // Nombre del campo 
        'options' => array("" => "Selecciona hora de entrega", '13:00' =>"13:00",'13:15' =>"13:15", '13:30' =>"13:30", '13:45' =>"13:45", '14:00' =>"14:00",'14:15' =>"14:15",'14:30' =>"14:30",'14:45' =>"14:45",'14:55' =>"14:55", )
        ), $checkout->get_value( 'hora_entrega' ));    // Identificador del campo 
        echo '</div>'; 
    }
    add_action( 'woocommerce_after_checkout_billing_form', 'woo_custom_field_checkout' );
    /*
    * INCLUYE NIF/CIF EN LOS DETALLES DEL PEDIDO CON EL NUEVO CAMPO
    */
    function woo_custom_field_checkout_update_order($order_id) {
        if ( ! empty( $_POST['hora_entrega'] ) ) {
        update_post_meta( $order_id, 'hora_entrega', sanitize_text_field( $_POST['hora_entrega'] ) );
        }
    }
    add_action( 'woocommerce_checkout_update_order_meta', 'woo_custom_field_checkout_update_order' );
    /*
    * MUESTRA EL VALOR DEL CAMPO NIF/CIF LA PÁGINA DE MODIFICACIÓN DEL PEDIDO
    */
    function woo_custom_field_checkout_edit_order($order){
        $order_id = trim(str_replace('#', '', $order->get_order_number()));
    
        echo '<p><strong>'.__('Hora de entrega').':</strong> ' . get_post_meta( $order_id, 'hora_entrega', true ) . '</p>';
    }
    add_action( 'woocommerce_admin_order_data_after_billing_address', 'woo_custom_field_checkout_edit_order', 10, 1 );

?>