<?php
/**
 * @package WordPress
 * @subpackage Voyager
 * @since Voyager 1.0
 * Template name: Blank - No header, no footer
 */
thb_before_doctype();

$page_sidebar = thb_get_page_sidebar( thb_get_page_ID() );
?>
<!doctype html>
<html <?php language_attributes(); ?> <?php thb_html_class(); ?>>
	<head>
		<?php thb_head_meta(); ?>

		<title><?php thb_title(); ?></title>

		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>

	<?php thb_body_start(); ?>
		<div id="thb-main-external-wrapper">

			<div id="thb-header" class="<?php echo thb_voyager_get_header_skin(); ?>">
				<div class="thb-header-section">
					<div class="thb-header-section-inner-wrapper">
						<?php thb_header_end(); ?>
					</div>
				</div>
			</div>

			<div id="thb-inner-wrapper">

				<?php thb_page_before(); ?>

				<?php thb_page_content_start(); ?>

				<div id="thb-page-content">

					<?php thb_page_start(); ?>

					<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>

						<?php if ( get_the_content() != '' || apply_filters( 'the_content', '' ) || ! empty( $page_sidebar ) || thb_show_comments() ) : ?>

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

			</div><!-- /#thb-inner-wrapper -->

		</div><!-- /#thb-main-external-wrapper -->

		<a href="#" class="thb-scrollup thb-go-top"><?php _e( 'Go top', 'thb_text_domain' ); ?></a>

		<?php thb_body_end(); ?>

		<?php thb_footer(); ?>
		<?php wp_footer(); ?>
	</body>
</html>