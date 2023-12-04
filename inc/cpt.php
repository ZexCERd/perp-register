<?php
/**
 * Custom post type settings
 */

// Create perpetual_register CPT
function perpetual_register_post_type() {
    register_post_type( 'Perpetual Register',
        array(
            'labels' => array(
                'name' => __( 'Perpetual Register' ),
                'singular_name' => __( 'Perpetual Register' )
            ),
            'public' => false,
            'publicly_queryable' => true,
            'show_ui' => true,
            'exclude_from_search' => true,
            'show_in_nav_menus' => false,
            'has_archive' => false,
            'rewrite' => false,
            'show_in_rest' => false,
            'supports' => array('title'),
        )
    );
}
add_action( 'init', 'perpetual_register_post_type' );
