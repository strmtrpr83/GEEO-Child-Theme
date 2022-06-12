<?php

/**
 * Enqueue parent and child stylesheets
 *
 * @since 1.0.0*
 * @author Jonathan Hendricker
 */
add_action( 'wp_enqueue_scripts', 'geeo_enqueue_styles' );
function geeo_enqueue_styles() {
    wp_enqueue_style( 'geeo-child-theme', get_stylesheet_uri(),
        array( 'Colleges-Theme' ), 
        wp_get_theme()->get('Version')
    );
}

