<?php

function gbi_get_platos_del_dia(){
    date_default_timezone_set("Europe/Madrid");
    $dia = date("D");

    if(date("H")>=15 || date("H") == 14 && date("i") > 30){
        $dia = date("D", strtotime(date("D")."+ 1 days"));
    }
    $eldia = "";

    switch($dia)
    {
        case "Mon":
            $eldia = "lunes";
            $diaformated = "Lunes";
            break;
        case "Tue":
            $eldia = "martes";
            $diaformated = "Martes";
            break;
        case "Wed":
            $eldia = "miercoles";
            $diaformated = "Miércoles";
            break;
        case "Thu":
            $eldia = "jueves";
            $diaformated = "Jueves";
            break;
        case "Fri":
            $eldia = "viernes";
            $diaformated = "Viernes";
            break;
        case "Sat":
            $eldia = "sabado";
            $diaformated = "Sábado";
            break;
        case "Sun":
            $eldia = "domingo";
            $diaformated = "Domingo";
            break;
    }

    $productos = wc_get_products(array(
        'category' => array("$eldia"),
        'status' => 'publish',
    ));


    ?>
    <div class="header_platos" style="margin-bottom: 2% !important;color: #999000 !important; font-size: 30px !important;">Platos de hoy <?php echo $diaformated?></div>
    <div class="woocommerce columns-2 ">
        <ul class="products columns-2 row">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <?php

        if($eldia != "domingo"){
            
            foreach($productos as $producto){
                $id_producto = $producto->get_id();
                $url_producto = get_permalink( $id_producto);
                //echo $id_producto;
                //Terminar control dias, si hoy mas de las 15, fecha = mañana
                ?>
                
                <div class="col-6 col-sm-3" style="display:flex; justify-content:center; align-items:center; text-align:center;">
                <li class="product type-product post-<?php echo $id_producto?> status-publish first instock product_cat-menu-semanal has-post-thumbnail sale taxable shipping-taxable purchasable product-type-simple" style="width:80%">
                    <a href="<?php echo $url_producto?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link" style="text-decoration:none;">
                        <span class="onsale" style="background-color:#663333">¡Oferta!</span>
                        <?php echo $producto->get_image('woocommerce_single');?>
                        <h2 class="woocommerce-loop-product__title"><?php echo $producto->get_title()?></h2>
                
                        <span class="price">
                            <ins aria-hidden="true" style="text-decoration:none;">
                                <span class="woocommerce-Price-amount amount">
                                    <bdi style="font-size:1.5em;color:#663333;"><?php echo number_format($producto->get_regular_price(),2);?>
                                        <span class="woocommerce-Price-currencySymbol">€</span>
                                    </bdi>
                                </span>
                            </ins>                    
                        </span>
                    </a>
                    <a href="?add-to-cart=<?php echo $id_producto?>" data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo $id_producto?>" data-product_sku="" aria-label="Añadir a tu carrito" rel="nofollow">Añadir al carrito</a>
                </li>
                </div>
                <?php
            }

        }else{

            echo "Realizamos platos del día de Lunes a Sábado, por favor consulte más abajo nuestros platos diarios.";

        }
        ?>
        </ul>
    </div>
    <?php
    wp_reset_query();
}

add_shortcode( "gbi_get_platos_del_dia", "gbi_get_platos_del_dia" );


function comprobar_plato_dia_producto(){
    $id_producto = $_POST['id_producto'];
    $platodeldia = false;
    $terms = get_the_terms( $id_producto, 'product_cat' );
        foreach ( $terms as $categoria ) {
            if ( $categoria->slug == 'plato-del-dia' ) {
                $platodeldia = true;
            }
        }
    wp_send_json($platodeldia);
}


add_action('wp_ajax_comprobar_plato_dia_producto', 'comprobar_plato_dia_producto');
add_action("wp_ajax_nopriv_comprobar_plato_dia_producto", "comprobar_plato_dia_producto");