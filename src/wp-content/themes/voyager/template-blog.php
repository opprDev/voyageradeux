<?php
/**
 * @package WordPress
 * @subpackage Voyager
 * @since Voyager 1.0
 * Template name: Blog
 */
get_header(); ?>

<?php thb_page_before(); ?>

<div id="thb-page-content">

	<?php thb_page_start(); ?>

	<?php thb_page_content_start(); ?>

	<div class="thb-content-section <?php echo thb_voyager_pagecontent_skin(); ?>">

		<div class="thb-content-section-inner-wrapper">

			<div id="thb-main-content">

				<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>
					<?php global $more; $more = 0; ?>

					<?php if ( get_the_content() != '' || apply_filters( 'the_content', '' ) ) : ?>
						<div class="thb-text">
							<?php if ( get_the_content() ) : ?>
								<?php the_content(); ?>
							<?php else : ?>
								<?php echo apply_filters( 'the_content', '' ); ?>
							<?php endif; ?>
						</div>
					<?php endif; ?>

				<?php endwhile; endif; ?>

				<?php get_template_part('loop/blog', 'classic'); ?>
				<?php thb_numeric_pagination(); ?>

				<?php wp_reset_query(); ?>

			</div>

			<?php thb_page_sidebar(); ?>

		</div>

	</div>

	<?php thb_page_end(); ?>

</div>

<?php thb_page_after(); ?>

<?php get_footer(); ?>