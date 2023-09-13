<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
// carga de scripts para cada página

function load_scripts() {
    global $post;
    if ( is_page() ) {
        if ( $post->post_name == 'finalizar-compra' ) {

            // boostrap
            //wp_enqueue_style( 'boostrap_css', 'https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css', false, '4.5.3', 'all' );
            //wp_enqueue_script( 'jquery_js', 'https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js', array(), '3.5.1', true );
            //wp_enqueue_script( 'boostrap_js', 'https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js', array(), '4.5.3', true );
            // datatables
            //wp_enqueue_style( 'datatables_css', 'https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css', false, '1.10.22', 'all' );
            //wp_enqueue_script( 'datables_js', 'https:////cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js', array(), '1.10.22', true );
            // propios
            wp_enqueue_style( 'custom_css', plugin_dir_url( __FILE__ ). 'assets/css/custom.css', false, '1.0', 'all' );
            wp_enqueue_script( 'custom_js', plugin_dir_url( __FILE__ ). 'assets/js/custom.js', array(), '1.0', true );
            //uso de ajax
            wp_localize_script( 'custom_js', 'ajax_url', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
        }else if( $post->post_name == "plato-del-dia"){
            wp_enqueue_style( 'boostrap_css', 'https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css',false,'4.5.3','all');
            wp_enqueue_script( 'jquery_js', 'https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js', array(), '3.5.1', true); 
            wp_enqueue_script( 'boostrap_js', 'https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js', array(), '4.5.3', true); 
            wp_enqueue_script( 'modal_complemento_js', plugin_dir_url( __FILE__ ). 'assets/js/modal_complementos.js', array(), '1.0', true );
            wp_localize_script( 'modal_complemento_js', 'ajax_url', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
        
        }
    }
    // boostrap
   
}

add_action( 'wp_enqueue_scripts', 'load_scripts' );

?>