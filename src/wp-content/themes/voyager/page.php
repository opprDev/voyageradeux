<?php
/**
 * @package WordPress
 * @subpackage Voyager
 * @since Voyager 1.0
 */
$page_sidebar = thb_get_page_sidebar( thb_get_page_ID() );
get_header(); ?>

<?php thb_page_before(); ?>

<div id="thb-page-content">

	<?php thb_page_start(); ?>

	<?php thb_page_content_start(); ?>

	<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>

		<?php if ( get_the_content() != '' || ! empty( $page_sidebar ) || thb_show_comments() ) : ?>

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
							<section class="secondary">
								<?php thb_comments( array('title_reply' => '<span>' . __('Leave a reply', 'thb_text_domain') . '</span>' )); ?>
							</section>
						<?php endif; ?>

					</div>

					<?php thb_page_sidebar(); ?>

				</div>

			</div>

		<?php endif; ?>

	<?php endwhile; endif; ?>

	<?php thb_page_end(); ?>

</div>

<?php thb_page_after(); ?>

<?php get_footer(); ?>