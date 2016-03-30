<?php
/**
 * @package WordPress
 * @subpackage Voyager
 * @since Voyager 1.0
 * Template name: Portfolio
 */
get_header(); ?>

<?php thb_page_before(); ?>

	<div id="thb-page-content">

		<?php thb_page_start(); ?>

		<?php thb_page_content_start(); ?>

		<div class="thb-content-section <?php echo thb_voyager_pagecontent_skin(); ?>">

			<div class="thb-content-section-inner-wrapper">

				<?php if ( function_exists( 'thb_portfolio_loop' ) ) : ?>
					<div id="thb-portfolio-container" class="thb-portfolio <?php echo thb_get_portfolio_filter_alignment(); ?>" <?php thb_portfolio_data_attributes(); ?>>
						<?php
							thb_portfolio_filter();
							thb_portfolio_loop();
							thb_pagination();
							wp_reset_query();
						?>
					</div>
				<?php else : ?>
					<?php echo "<p class='thb-message warning'>" . __( "It looks like the THB Portfolio plugin is not active.</br>Please install or activate it in order to display your portfolio items.", "thb_text_domain" ) . "</p>"; ?>
				<?php endif; ?>

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