<?php
add_shortcode( 'perpetual_register', 'perpetual_register_callback' );
function perpetual_register_callback( ) {
    include( plugin_dir_path( __DIR__  ) . '/template/perpetual-register.php' );
}