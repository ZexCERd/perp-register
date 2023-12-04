<?php
/*
Plugin Name: CSV Upload Plugin
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Required admin files 
include( plugin_dir_path( __FILE__ ) . 'inc/utils.php' );
include( plugin_dir_path( __FILE__ ) . 'inc/cpt.php' );
include( plugin_dir_path( __FILE__ ) . 'inc/options.php' );
include( plugin_dir_path( __FILE__ ) . 'inc/acf.php' );
include( plugin_dir_path( __FILE__ ) . 'inc/shortcode.php' );
