<?php
/**
 * @package WordPress
 * @subpackage Voyager
 * @since Voyager 1.0
 */

$trip_stops = thb_trip_get_stops( thb_get_page_ID(), 'full', 'page' );
$trip_map_stops = thb_trip_get_stops( thb_get_page_ID(), 'full', 'map' );
?>
<?php get_header(); ?>
<?php thb_post_before(); ?>

<div id="thb-page-content">

	<?php thb_page_start(); ?>

	<?php
		if ( thb_show_map_in_page() ) {
			if ( count( $trip_map_stops ) > 0 ) {
				echo '<div id="thb-trip-map" class="thb-map-in-page"></div>';
			}
		}
	?>

	<?php thb_page_content_start(); ?>

	<?php if ( count( $trip_stops ) > 0 && ! thb_hide_stops() ) : ?>
		<div class="thb-trip-stops-wrapper">
			<?php foreach ( $trip_stops as $index => $stop ) : ?>
			<?php
				$stop_skin = '';
				$stop_posts = thb_trip_get_posts( thb_get_page_ID(), $stop['id'] );

				if ( $stop['layout'] != 'left' && $stop['layout'] != 'right' ) {
					$stop_skin = 'thb-skin-' . thb_color_get_skin_from_comparison( $stop['overlay_color'], $stop['background_color'] );
				}

				$stop_attrs = array();

				$stop_classes = array(
					'thb-trip-stop',
					'thb-trip-stop-layout-' . $stop['layout'],
					'thb-trip-stop-background-appearance-' . $stop['background_appearance'],
					$stop_skin
				);

				if ( $stop['fit_viewport'] == '1' ) {
					$stop_classes[] = 'thb-trip-stop-fit';
				}

				if ( $stop['background_color'] != '' ) {
					$stop_attrs['style'] = sprintf( ' background-color: %s;', $stop['background_color'] );
				}
			?>

			<div class="hidden">
				<?php
					$timestamp = strtotime( get_the_date() );
					$microdate = date( 'Ymd', $timestamp );
				?>
				<ul>
					<li class="updated published" title="<?php echo $microdate; ?>"><?php echo get_the_date(); ?></li>
					<li class="vcard author"><?php _e('by', 'thb_text_domain'); ?> <span class="fn"><?php the_author_posts_link(); ?></span></li>
				</ul>
			</div>

			<section class="<?php echo implode( ' ', $stop_classes ); ?>" id="stop-<?php echo $index; ?>" <?php thb_attributes( $stop_attrs ); ?>>

				<div class="thb-trip-content-inner-wrapper">

					<div class="thb-trip-content">
						<p><?php the_title(); ?></p>

						<?php if ( ! empty( $stop['label'] ) ) : ?>
							<h2 class="thb-trip-title"><?php echo thb_text_format( $stop['label'] ); ?></h2>
						<?php endif; ?>

						<?php if ( ! empty( $stop['description'] ) ) : ?>
							<div class="thb-text entry-content">
								<?php echo thb_text_format( $stop['description'], true ); ?>
							</div>
						<?php endif; ?>

						<?php if ( function_exists( 'thb_is_lightbox_enabled' ) && thb_is_lightbox_enabled() && $stop['show_gallery'] ) : ?>
							<a href="#" class="thb-trip-view-gallery" data-stop="<?php echo $stop['id']; ?>"><?php _e( 'View gallery', 'thb_text_domain' ) ?></a>
						<?php endif; ?>

						<?php if ( $stop['layout'] == 'mosaic-center' ) : ?>
							<?php thb_get_template_part( 'partials/partial-trip-photoset-grid', array( 'stop' => $stop ) ); ?>
						<?php endif; ?>

						<?php if ( $stop['hide_posts'] != '1' && ! empty( $stop_posts ) ) : ?>
							<ul class="thb-trip-posts">
								<?php foreach ( $stop_posts as $stop_post ) : ?>
									<li>
										<article>
											<a href="<?php echo get_permalink( $stop_post->ID ); ?>">
												<h4>
													<?php echo thb_text_format( $stop_post->post_title ); ?>
												</h4>
												<p class="thb-trip-post-meta">
													<span class="thb-trip-post-date"><?php echo date( get_option( 'date_format' ), strtotime( $stop_post->post_date ) ); ?></span>
													<span class="thb-trip-post-author"><?php _e( 'by', 'thb_text_domain' ); ?> <?php echo get_the_author_meta( 'display_name', $stop_post->post_author ); ?></span>
												</p>
											</a>
										</article>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>

					<?php if ( $stop['layout'] == 'mosaic-left' || $stop['layout'] == 'mosaic-right' ) : ?>
						<?php thb_get_template_part( 'partials/partial-trip-photoset-grid', array( 'stop' => $stop ) ); ?>
					<?php endif; ?>

				</div>

				<?php if ( ! empty( $stop['background_color'] ) || ! empty( $stop['background_image'] ) ) : ?>
					<?php
						$image_holder_classes = array();
						$image_holder_attrs = array( 'style' => '' );

						$image_holder_attrs['style'] .= sprintf( ' background-image: url(%s);', thb_image_get_size( $stop['background_image'], 'full' ) );
					?>

					<div class="thb-trip-image-holder" <?php thb_attributes( $image_holder_attrs ); ?>>

						<?php
							if ( $stop['overlay_display'] == '1' && $stop['overlay_color'] != '' ) {
								thb_overlay( $stop['overlay_color'], $stop['overlay_opacity'], 'thb-overlay' );
							}
						?>

					</div>

				<?php endif; ?>
			</section>
		<?php endforeach; ?>
		</div>
	<?php endif; ?>


	<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>

		<?php if ( get_the_content() != '' || thb_show_comments() ) : ?>

			<div class="thb-content-section <?php echo thb_voyager_pagecontent_skin(); ?>">

				<div class="thb-content-section-inner-wrapper">

					<div id="thb-main-content">

						<?php if ( get_the_content() != '' || apply_filters( 'the_content', '' ) ) : ?>

							<div class="thb-text entry-content">
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