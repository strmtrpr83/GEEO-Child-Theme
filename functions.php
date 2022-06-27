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
        $title_suffix           = (get_field( 'person_title_suffix', $obj->ID )) ? ", ".get_field( 'person_title_suffix', $obj->ID ) : '';
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
                <div class="wp-block-column pt-5" style="flex-basis:60%">
            <?php endif; ?>            
            
                <?php if ( $title ): ?>
                    <h1 class="mt-4 has-sky-blue-color"><?php echo $title.$title_suffix; ?></h1>                    
                <?php endif; ?>
                <?php if ( $subtitle ): ?>
                        <p class="h3 mb-0 mb-md-2 has-bright-blue-color"><?php echo $subtitle; ?></p>
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
 * Updating the get_header_title function for GEEO Person CPT
 * 
 * @since 1.0.0*
 * @author Jonathan Hendricker
 */
function get_header_title( $obj ) {
    $title = '';
    $hide_title = ( get_field("page_header_title_hide") === true ) ? true : false;
    /*
    if ( is_home() || is_front_page() ) {
        $title = get_field( 'homepage_header_title', $obj->ID );

        if ( ! $title ) {
            $title = get_bloginfo( 'name' );
        }
    }
    elseif
    */
    if ( is_search() ) {
        $title = __( 'Search Results for:' );
        $title .= ' ' . esc_html( stripslashes( get_search_query() ) );
    }
    elseif ( is_404() ) {
        $title = __( '404 Not Found' );
    }
    elseif ( is_single() || is_page() ) {
        if ( $obj->post_type === 'person' ) {
            $title = get_theme_mod_or_default( 'person_header_title' ) ?: get_the_title( $obj );
        }
        else {
            if ( $hide_title === false )
                $title = get_field( 'page_header_title', $obj->ID );
        }

        if ( ! $title ) {
            if ( $hide_title === false )
                $title = single_post_title( '', false );
        }
    }
    elseif ( is_category() ) {
        $title = __( 'Category Archives:' );
        $title .= ' ' . single_term_title( '', false );
    }
    elseif ( is_tag() ) {
        $title = __( 'Tag Archives:' );
        $title .= ' ' . single_term_title( '', false );
    }
    elseif ( is_tax() ) {
        $tax_name = '';
        $tax = get_taxonomy( $obj->taxonomy );
        if ( $tax ) {
            $tax_name = $tax->labels->singular_name . ' ';
        }
        $title = __( $tax_name . 'Archives:' );
        $title .= ' ' . single_term_title( '', false );
    }

    return $title;
}
/**
 * Updating the get_header_subtitle function for GEEO Person CPT
 * 
 * @since 1.0.0*
 * @author Jonathan Hendricker
 */
function get_header_subtitle( $obj ) {
    $subtitle = '';

    if ( is_single() || is_page() ) {
        if ( $obj->post_type === 'person' ) {
            $subtitle = get_theme_mod_or_default( 'person_header_subtitle' ) ?: get_field( 'person_jobtitle', $obj->ID );
        }
        else {
            $subtitle = get_field( 'page_header_subtitle', $obj->ID );
        }
    }

    return $subtitle;
}

/**
 * Markup for displaying SDGs for a 'person' CPT
 * 
 * @since 1.0.0*
 * @author Jonathan Hendricker
 */
if( !function_exists( 'get_geeo_person_sdgs_markup' ) ) {
    function get_geeo_person_sdgs_markup( $post ) {
        // Check if ACF is active and that there's a 'person' CPT
        if( !class_exists('ACF') && !post_type_exists( 'person' ) ){ return; }
        // Make sure the post data being passed is for a 'person' CPT
        if ( $post->post_type !== 'person' ) { return; }

        ob_start();
        
        $field = get_field_object('geeo_associated_sdgs');
        $sdgs = $field['value'];

        if( $sdgs ): ?>
            <div class="geeo-sdgs mb-3">
            <h5>Sustainable Development Goals</h5>
            <ul class="geeo-sdgs-ul">
                <?php foreach( $sdgs as $sdg ): ?>
                    <li><span class="geeo-<?php echo $sdg['value']; ?>"><img src="<?php echo get_stylesheet_directory_uri().'/css/sdg-icons/'.$sdg['value'];?>.png" alt="<?php echo $sdg['label']; ?>" class="geeo-sdg-icon lazyload"></span></li>
                <?php endforeach; ?>
            </ul>
            </div>
        <?php endif; 

        return ob_get_clean();
    }
}

/**
 * Remove People Post List Filter in order to override it 
 */
remove_filter( 'ucf_post_list_display_people', 'colleges_post_list_display_people', 10 );

/**
 * New layout for ucf-post-list People 
 * 
 * @since 1.0.0.*
 * @author Jonathan Hendricker
 */
function geeo_post_list_display_people( $content, $items, $atts ) {
	if ( ! is_array( $items ) && $items !== false ) { $items = array( $items ); }
	ob_start();
?>
	<?php if ( $items ): ?>
	<ul class="list-unstyled row ucf-post-list-items">
		<?php foreach ( $items as $item ): ?>
		<li class="col-6 col-sm-4 col-md-3 col-xl-3 mt-3 mb-2 ucf-post-list-item">
			<a class="person-link" href="<?php echo get_permalink( $item->ID ); ?>">
				<?php echo get_person_thumbnail( $item ); ?>				
            </a>                        
            <a class="person-link" href="<?php echo get_permalink( $item->ID ); ?>">
            <h3 class="mt-2 mb-1 person-name"><?php echo get_person_name( $item ); ?></h3>   
            </a>        
            <?php if ( $job_title = get_field( 'person_jobtitle', $item->ID ) ): ?>
            <div class="font-italic person-job-title">
                <?php echo $job_title; ?>
            </div>
            <?php
                $field = get_field_object('geeo_associated_sdgs', $item->ID);
                $sdgs = $field['value'];

                if( $sdgs ): 
            ?>
                <div class="geeo-sdgs-container">
                    <span class="sdg-list-title">SDGs:</span>
                    <ul class="geeo-sdgs-ul">
                        <?php foreach( $sdgs as $sdg ): ?>
                            <li><span class="geeo-sdg geeo-<?php echo $sdg['value']; ?> "></span></li>
                        <?php endforeach; ?>
                    </ul>
                        </div>
                <?php endif; ?>
            <?php endif; ?>	            		
		</li>
		<?php endforeach; ?>
	</ul>
	<?php else: ?>
	<div class="ucf-post-list-error mb-4">No results found.</div>
	<?php endif; ?>
<?php
	return ob_get_clean();
}

add_filter( 'ucf_post_list_display_people', 'geeo_post_list_display_people', 20, 3 );


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
