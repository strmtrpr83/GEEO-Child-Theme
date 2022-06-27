<?php get_header(); the_post(); ?>

<article class="<?php echo $post->post_status; ?> post-list-item">
	<div class="container my-5">
		<div class="row">			
            <?php
                $sdg_data = ( !empty( get_geeo_person_sdgs_markup( $post ) ) ) ? get_geeo_person_sdgs_markup( $post ) : '';
                $sdg_section = false;
                if ( get_field('person_phone_numbers') || get_field('person_email') || $sdg_data ) $sdg_section = true;

                if ( $sdg_section):
            ?>
			    <div class="col-md-8 col-lg-9">
            <? else: ?>
                <div class="col-xs-12">
            <? endif; ?>
				<section class="person-content">
					<?php //echo get_person_desc_heading( $post ); ?>
                    
					<?php echo get_person_thumbnail( $post, 'alignleft' ); ?>
					
					<?php
					if ( $post->post_content ) {
						the_content();
					}
					?>
					<?php if ( $cv_url = get_field( 'person_cv' ) ): ?>
					<p>
						<a class="btn btn-primary mt-3" href="<?php echo $cv_url; ?>">Download CV</a>
					</p>
					<?php endif; ?>
				</section>

			</div>
            <?php if ( $sdg_section): ?>
            <div class="col-md-4 col-lg-3">

				<aside class="person-contact-container">					

					<?php if ( $job_title = get_field( 'person_jobtitle' ) ): /*?>
					<div class="person-job-title text-center mb-4"><?php echo $job_title; ?></div>
                    <?php */ endif; ?>

					<?php //echo get_person_contact_btns_markup( $post ); ?>

					<?php echo get_person_dept_markup( $post ); ?>
					<?php echo get_person_office_markup( $post ); ?>

                    <?php if ( get_field('person_phone_numbers') || get_field('person_email') ): ?>
                    <h5>Contact Information</h5>
                    <div class="geeo-contact-info-block">                    
					<?php echo get_person_email_markup( $post ); ?>
					<?php echo get_person_phones_markup( $post ); ?>
                    </div>
                    <?php endif; ?>

                    <?php echo get_geeo_person_sdgs_markup( $post ); ?>

				</aside>

			</div>
            <?php endif; ?>
		</div>

		<?php echo get_person_news_publications_markup( $post ); ?>

		<?php echo get_person_videos_markup( $post ); ?>
	</div>
</article>

<?php get_footer(); ?>
