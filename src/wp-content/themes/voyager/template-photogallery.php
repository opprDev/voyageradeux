<?php
/**
 * @package WordPress
 * @subpackage Voyager
 * @since Voyager 1.0
 * Template name: Photogallery
 */
get_header(); ?>

	<?php thb_page_before(); ?>

		<div id="thb-page-content">

			<?php thb_page_start(); ?>

			<?php thb_page_content_start(); ?>

			<div class="thb-content-section <?php echo thb_voyager_pagecontent_skin(); ?>">

				<div class="thb-content-section-inner-wrapper">

					<?php thb_photogallery(); ?>

				</div>

			</div>

			<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>

				<?php if ( get_the_content() != '' || apply_filters( 'the_content', '' ) || thb_show_comments() ) : ?>

					<div class="thb-content-section <?php echo thb_voyager_pagecontent_skin(); ?>">

						<div class="thb-content-section-inner-wrapper">

							<div id="thb-main-content">

								<?php if ( get_the_content() != '' || apply_filters( 'the_content', '' ) ) : ?>
									<div class="thb-text">
										<?php if ( get_the_content() ) : ?>
											<?php the_content(); ?>
										<?php else : ?>
											<?php echo apply_filters( 'the_content', '' ); ?>
										<?php endif; ?>
									</div>
								<?php endif; ?>

								<?php if( thb_show_comments() ) : ?>
									<section class="thb-secondary-content">
										<?php thb_comments( array('title_reply' => '<span>' . __('Leave a reply', 'thb_text_domain') . '</span>' )); ?>
									</section>
								<?php endif; ?>

							</div>

						</div>

					</div>

				<?php endif; ?>

			<?php endwhile; endif; ?>

			<?php thb_page_end(); ?>

		</div>

	<?php thb_page_after(); ?>

<?php get_footer(); ?>