<?php
/**
 * @package WordPress
 * @subpackage Voyager
 * @since Voyager 1.0
 */

$thb_page_id = thb_get_page_ID();

$slides = thb_get_portfolio_item_slides( $thb_page_id );

$slides_config = $featured_image_config = array(
	'overlay'    => true,
	'link'       => true,
	'link_class' => 'item-thumb'
);

if ( thb_get_disable_work_image_link() == 1 ) {
	$slides_config = array(
		'overlay'    => false,
		'link'       => false
	);

	$featured_image_config = array(
		'link'       => false,
		'overlay'    => false
	);
}

$image_size = 'large';

$prj_info = thb_duplicable_get('prj_info', $thb_page_id);
$has_prj_info = !empty($prj_info);

$work_categories = wp_get_object_terms($thb_page_id, 'portfolio_categories');
$cats = array();
foreach( $work_categories as $cat ) {
	$cats[] = $cat->name;
}
get_header(); ?>
<?php thb_post_before(); ?>

<div id="thb-page-content">

<?php thb_post_start(); ?>

	<?php thb_single_content_start(); ?>

	<div class="thb-content-section <?php echo thb_voyager_pagecontent_skin(); ?>">

		<div class="thb-content-section-inner-wrapper">

			<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>

				<div id="thb-main-content">

					<div id="single-work-container" <?php post_class(); ?>>

						<?php thb_get_template_part( 'partials/partial-single-work-slides', array(
							'slides'                => $slides,
							'image_size'            => $image_size,
							'featured_image_config' => $featured_image_config,
							'slides_config'         => $slides_config
						) ); ?>

						<div class="single-work-secondary-data">

							<div class="thb-project-text">

								<?php if ( get_the_content() != '' || apply_filters( 'the_content', '' ) ) : ?>

									<div class="thb-text">
										<?php if ( get_the_content() ) : ?>
											<?php the_content(); ?>
										<?php else : ?>
											<?php echo apply_filters( 'the_content', '' ); ?>
										<?php endif; ?>
									</div>

								<?php endif; ?>

								<?php if ( thb_is_enable_social_share() || thb_is_portfolio_likes_active() ) : ?>
									<div class="meta social-actions">
										<?php if ( thb_is_enable_social_share() ) : ?>
											<?php thb_get_template_part('partials/partial-share'); ?>
										<?php endif; ?>

										<?php if ( thb_is_portfolio_likes_active() ) : ?>
											<?php thb_like(); ?>
										<?php endif; ?>
									</div>
								<?php endif; ?>

							</div>

							<aside class="thb-project-info">
								<?php if( $has_prj_info ) : ?>
									<?php foreach( $prj_info as $info ) : ?>
										<p>
											<?php if ( thb_text_startsWith( $info['value']['value'], 'http://' ) ) : ?>
												<a href="<?php echo $info['value']['value']; ?>"><?php echo $info['value']['key']; ?></a>
											<?php else : ?>
												<span class="thb-project-label"><?php echo $info['value']['key']; ?></span>
												<?php echo $info['value']['value']; ?>
											<?php endif; ?>
										</p>
									<?php endforeach; ?>
								<?php endif; ?>

								<?php if( ! empty($cats) ) : ?>
									<p><span class="thb-project-label"><?php _e( 'Project categories', 'thb_text_domain' ); ?>: </span><?php echo implode(', ', $cats); ?></p>
								<?php endif; ?>

								<?php if ( thb_portfolio_item_get_external_url() != '' ) : ?>
									<?php $external_url = thb_portfolio_item_get_external_url(); ?>
									<p><span class="thb-project-label"><?php _e( 'External URL', 'thb_text_domain' ); ?>: </span><a href="<?php echo $external_url; ?>" rel="external"><?php echo $external_url; ?></a></p>
								<?php endif; ?>

							</aside>

						</div>

					</div>

					<?php
						if( thb_show_pagination() ) {
							add_action( 'thb_between_navigation', 'thb_portfolio_index' );

							thb_pagination(
								array(
									'single_prev'     => __( 'Previous', 'thb_text_domain' ),
									'single_next'     => __( 'Next', 'thb_text_domain' ),
								)
							);
						}
					?>

					<?php if( thb_show_comments() ) : ?>
						<section class="secondary">
						<?php if( thb_show_comments() ) : ?>
							<?php thb_comments( array('title_reply' => '<span>' . __('Leave a reply', 'thb_text_domain') . '</span>' )); ?>
						<?php endif; ?>
						</section>
					<?php endif; ?>

				</div>

			<?php endwhile; endif; ?>

			<?php thb_page_sidebar(); ?>

		</div>

	</div>

<?php thb_post_end(); ?>

</div>

<?php thb_post_after(); ?>

<?php get_footer(); ?>