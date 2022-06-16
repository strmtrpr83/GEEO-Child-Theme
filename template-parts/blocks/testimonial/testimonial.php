<?php

/**
 * Testimonial Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'testimonial-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'testimonial';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

// Load values and assign defaults.
$text = get_field('testimonial') ?: 'Your testimonial here...';
$author = get_field('author') ?: 'Author name';
$role = get_field('role') ?: 'Author role';
$image = get_field('image') ?: 813;
$background_color = get_field('background_color');
$text_color = get_field('text_color');

?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <div class="geeo-testimonial-wrapper wp-container-3 wp-block-columns">
        <div class="wp-container-1 wp-block-column geeo-testimonial-text-block">
            <blockquote class="testimonial-blockquote">
                <h2 class="testimonial-text "><?php echo $text; ?></h2>
                <h5 class="testimonial-author "><?php echo $author; ?></h5>
                <h6 class="testimonial-role "><?php echo $role; ?></h6>
            </blockquote>
        </div>
        <div class="wp-container-2 wp-block-column testimonial-image wp-block-image">
            <?php echo wp_get_attachment_image( $image, 'full' ); ?>
        </div>
        <style type="text/css">
            #<?php echo $id; ?> {
                background: <?php echo $background_color; ?>;
                color: <?php echo $text_color; ?>;
            }
            #<?php echo $id; ?> h2, #<?php echo $id; ?> h5, #<?php echo $id; ?> h6 {
                color: <?php echo $text_color; ?>;
            }
        </style>
    </div>
</div>