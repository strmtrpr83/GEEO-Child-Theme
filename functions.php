<?php

/**
 * Enqueue parent and child stylesheets
 *
 * @since 1.0.0*
 * @author Jonathan Hendricker
 */
add_action( 'wp_enqueue_scripts', 'geeo_enqueue_styles' );
function geeo_enqueue_styles() {
    // Parent theme name
    $parent_style = 'Colleges-Theme'; 

    // Enqueue Parent Theme Style
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/static/css/style.min.css' );
    // Enqueue Child Theme Style
    wp_enqueue_style( 'style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
}

// Add theme support for Gutenberg's full width blocks
add_theme_support( 'align-wide' );

/**
 * Add custom colors to the theme color palette
 *
 * @since 1.0.0*
 * @author Jonathan Hendricker
 */
add_action( 'after_setup_theme', 'geeo_gutenberg_css' );
function geeo_gutenberg_css(){

    add_theme_support( 'editor-color-palette', array(
        array(
            'name'  => esc_attr__( 'Dark Blue', 'geeochildtheme' ),
            'slug'  => 'dark-blue',
            'color' => '#0049af',
        ),
        array(
            'name'  => esc_attr__( 'Blue', 'geeochildtheme' ),
            'slug'  => 'blue',
            'color' => '#3694ec',
        ),
        array(
            'name'  => esc_attr__( 'Bright Blue', 'geeochildtheme' ),
            'slug'  => 'bright-blue',
            'color' => '#05B1FC',
        ),
        array(
            'name'  => esc_attr__( 'Light Blue', 'geeochildtheme' ),
            'slug'  => 'light-blue',
            'color' => '#8ed1fc',
        ),
        array(
            'name'  => esc_attr__( 'Sky Blue', 'geeochildtheme' ),
            'slug'  => 'sky-blue',
            'color' => '#bae3ff',
        ),
        array(
            'name'  => esc_attr__( 'Dark Gray', 'geeochildtheme' ),
            'slug'  => 'dark-gray',
            'color' => '#1b1b1b',
        ),
        array(
            'name'  => esc_attr__( 'Gray', 'geeochildtheme' ),
            'slug'  => 'gray',
            'color' => '#3e3e3e',
        ),
        array(
            'name'  => esc_attr__( 'Light Gray', 'geeochildtheme' ),
            'slug'  => 'light-gray',
            'color' => '#e6e9f0',
        ),
        array(
            'name'  => esc_attr__( 'White', 'geeochildtheme' ),
            'slug'  => 'white',
            'color' => '#ffffff',
        ),
        array(
            'name'  => esc_attr__( 'Dark Gold', 'geeochildtheme' ),
            'slug'  => 'dark-gold',
            'color' => '#cca300',
        ),
        array(
            'name'  => esc_attr__( 'Gold', 'geeochildtheme' ),
            'slug'  => 'gold',
            'color' => '#ffc904',
        ),
        
    ) );    

}

/**
 * Custom Header Markup
 * 
 * @since 1.0.0*
 * @author Jonathan Hendricker
 */
if( !function_exists( 'get_geeo_header_markup' ) ) {
    function get_geeo_header_markup() {
		$videos = $images = null;
		$obj = get_queried_object();

		if ( is_single() || is_page() ) {
			$videos = get_header_videos( $obj );
			$images = get_header_images( $obj );
		}

		echo get_nav_markup();

		if ( $videos || $images ) {
			echo get_header_media_markup( $obj, $videos, $images );
		}
		else {
			echo get_geeo_header_default_markup( $obj );
		}
	}
}
/**
 * Returns the default markup for GEEO page headers.
 **/
if( !function_exists( 'get_geeo_header_default_markup' ) ) {
	function get_geeo_header_default_markup( $obj ) {
		$title                  = get_header_title( $obj );
		$subtitle               = get_header_subtitle( $obj );
		$extra_content          = '';
        $header_side_image      = '';
        $header_side_image_src  = '';
        $header_side_image_id   = (get_field( 'page_header_side_image', $obj->ID )) ? get_field( 'page_header_side_image', $obj->ID ) : false;
        $custom_header_layout = false;
        
        if($header_side_image_id !== false){
            $header_side_image = wp_get_attachment_image( $header_side_image_id, 'large', false );
            $header_side_image_src = wp_get_attachment_image_src( $header_side_image_id, 'large', false);
            $custom_header_layout = true;
        }
		if ( is_single() || is_page() ) {
			$extra_content = get_field( 'page_header_extra_content', $obj->ID );
		}

		ob_start();
	?>
    <div class="jumbotron has-dark-blue-background-color no-header-image 
        <?php if($custom_header_layout === true) echo "p-0"; ?> 
        ">
        <div class="container">
            
            <?php if($custom_header_layout === true): ?>
            <div class='wp-block-columns'>
                <div class="wp-block-column" style="flex-basis:60%">
            <?php endif; ?>            
            
                    <?php
                    // Don't print multiple h1's on the page for person templates
                    if ( is_single() && $obj->post_type === 'person' ):
                    ?>
                        <strong class="h1 d-block mt-4 has-sky-blue-color"><?php echo $title; ?></strong>
                    <?php else: ?>
                        <h1 class="mt-4 has-sky-blue-color"><?php echo $title; ?></h1>
                    <?php endif; ?>

                    <?php if ( $subtitle ): ?>
                        <p class="lead mb-4 mb-md-5"><?php echo $subtitle; ?></p>
                    <?php endif; ?>

                    <?php if ( $extra_content ): ?>
                        <div class="geeo-extra-content has-sky-blue-color mb-2 mb-md-3"><?php echo $extra_content; ?></div>
                    <?php endif; ?>

            <?php if($custom_header_layout === true): ?>
                </div>
                <div class="header-side-image wp-block-column is-vertically-aligned-center" style="background: url(<?php echo $header_side_image_src[0]; ?>) center center no-repeat">
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
	<?php
		return ob_get_clean();
	}
}
 
/**
 * Register custom Gutenberg blocks
 *
 * @since 1.0.0*
 * @author Jonathan Hendricker
 */
add_action('acf/init', 'my_acf_init_block_types');
function my_acf_init_block_types() {

    // Make sure ACF exists and is active
    if( function_exists('acf_register_block_type') ) {

        // Register a testimonial block
        acf_register_block_type(array(
            'name'              => 'testimonial',
            'title'             => __('Testimonial'),
            'description'       => __('A custom testimonial block.'),
            'render_template'   => 'template-parts/blocks/testimonial/testimonial.php',
            'enqueue_style'     => get_stylesheet_directory_uri() . '/template-parts/blocks/testimonial/testimonial.css',
            'align'             => 'full',
            'mode'              => 'preview',
            'example'           => array(
                'attributes' => array(                    
                    'data' => array(
                        'testimonial'   => "Blocks are...",
                        'author'        => "Jane Smith",
                        'role'          => "Person",
                        'is_preview'    => true
                    )
                )
            ),
            'category'          => 'formatting',
            'icon'              => 'admin-comments',
            'keywords'          => array( 'testimonial', 'quote' ),
            
        ));
    }
}

/**
 * Get the current color palette
 */
function output_the_colors() {
	
	// get the colors
    $color_palette = current( (array) get_theme_support( 'editor-color-palette' ) );

	// Return if there are no colors
	if ( !$color_palette )
		return;

	ob_start();

	// Loop through each color
	echo '[';
		foreach ( $color_palette as $color ) {
			echo "'" . $color['color'] . "', ";
		}
	echo ']';
    
    return ob_get_clean();

}

/**
 * Add the theme's curent color palette to the bottom of the ACF color picker
 */
add_action( 'acf/input/admin_footer', 'gutenberg_sections_register_acf_color_palette' );
function gutenberg_sections_register_acf_color_palette() {

    $color_palette = output_the_colors();
    if ( !$color_palette )
        return;
    
    ?>
    <script type="text/javascript">
        (function( $ ) {
            acf.add_filter( 'color_picker_args', function( args, $field ){

                // add the hexadecimal codes here for the colors you want to appear as swatches
                args.palettes = <?php echo $color_palette; ?>

                // return colors
                return args;

            });
        })(jQuery);
    </script>
    <?php

}
