<?php

$thb_format = '';
$thb_link_url = '';
$thb_quote_url = '';
$featured_image = '';
$header_background_id = '';

if ( ! isset( $show_featured_image ) ) {
	$show_featured_image = true;
}

if ( ! thb_is_archive() && ! is_home() && ! thb_is_blog() ) {
	$featured_image = thb_get_featured_image( 'full', thb_get_page_ID() );
	$header_background_id = thb_get_post_meta( thb_get_page_ID(), 'header_background_id' );

	if ( ! isset( $thb_title ) ) {
		$thb_title = get_the_title();
	}

	if ( ! isset( $thb_subtitle ) ) {
		$thb_subtitle = thb_get_page_subtitle();
	}
}
else {
	if ( ! isset( $thb_title ) ) {
		$thb_title = '';
	}

	if ( ! isset( $thb_subtitle ) ) {
		$thb_subtitle = '';
	}
}

if ( is_home() || thb_is_blog() ) {
	$show_featured_image = false;
}

$thb_subtitle_class = '';

if ( is_singular( 'post' ) ) {
	$thb_format = thb_get_post_format();

	if ( thb_get_post_subtitle() != '' ) {
		$thb_subtitle = thb_get_post_subtitle();
	} else {
		$thb_subtitle = get_the_date();
		$thb_subtitle_class = 'updated published';
	}

	if ( $thb_format === 'link' ) {
		if ( thb_get_post_subtitle() != '' ) {
			$thb_subtitle = thb_get_post_subtitle();
		} else {
			$thb_subtitle = thb_get_post_format_link_url();
		}
	}
	elseif( $thb_format === 'quote' ) {
		$thb_title = thb_get_post_format_quote_text();
		$thb_quote_url = thb_get_post_format_quote_url();

		if ( thb_get_post_subtitle() != '' ) {
			$thb_subtitle = thb_get_post_subtitle();
		} else {
			$thb_subtitle = thb_get_post_format_quote_author();
		}
	}
}
elseif ( is_singular( 'trip' ) ) {
	$thb_subtitle = thb_get_trip_subtitle();
}
elseif ( is_singular( 'works') ) {
	if ( thb_get_project_short_description() != '' ) {
		$thb_subtitle = thb_get_project_short_description();
	}

	if ( thb_get_hide_featured_image() == 1 ) {
		$show_featured_image = false;
	}
}

$show_page_header = (
	! thb_page_header_disabled()
	&& ! ( thb_slideshow_has_slides() && thb_is_page_layout_extended_with_title() )
);

if( ( is_single() || is_singular( 'trip' ) || is_singular( 'works' ) ) && post_password_required() ) {
	$show_featured_image = false;
}

?>
<?php if ( $show_featured_image && thb_is_page_layout_extended() ) : ?>
	<div class="thb-page-header-image-holder">
		<?php thb_get_template_part( 'partials/partial-page-featured-image', array( 'img_link' => false, 'img_overlay' => false, 'slideshow_class' => 'full_slideshow' ) ); ?>
	</div>
<?php endif; ?>

<?php
	$page_header_classes = array();

	$page_header_classes[] = thb_voyager_get_pageheader_skin();

	if ( ! $show_page_header ) {
		$page_header_classes[] = 'thb-page-header-disabled';
	}
?>

<div id="thb-page-header" class="thb-page-header-wrapper <?php echo implode( ' ', $page_header_classes ); ?>">

	<div class="thb-page-header-section">

		<div class="thb-page-header-section-extra-wrapper">

			<?php if ( $show_featured_image && thb_is_page_layout_b() ) : ?>
				<?php get_template_part( 'partials/partial-page-featured-image' ); ?>
			<?php endif; ?>

			<div class="thb-page-header-section-inner-wrapper <?php if ( ! $show_page_header ) : ?>hidden<?php endif; ?>">
				<?php if ( thb_is_subtitle_position_bottom() ) : ?>
					<?php thb_page_title( $thb_title ); ?>
				<?php endif; ?>

				<?php if( !empty($thb_subtitle) ) : ?>
					<p class="page-subtitle">
						<?php if ( $thb_format === 'link' && thb_get_post_subtitle() == '' ) : ?>
							<a href="<?php echo $thb_subtitle; ?>" target="_blank"><?php echo $thb_subtitle; ?></a>
						<?php else : ?>
							<span class="<?php echo esc_attr( $thb_subtitle_class ); ?>"><?php echo $thb_subtitle; ?></span>
						<?php endif; ?>
					</p>
				<?php endif; ?>

				<?php if ( thb_is_subtitle_position_top() ) : ?>
					<?php thb_page_title( $thb_title ); ?>
				<?php endif; ?>

				<?php if ( is_archive() ) : ?>
					<?php the_archive_description( '<div class="thb-taxonomy-description">', '</div>' ); ?>
				<?php endif; ?>

				<?php if ( is_singular( 'trip' ) && ! thb_show_map_in_page() ) : ?>
					<?php if ( count( thb_trip_get_stops() ) > 0 && ! thb_is_page_layout_a() && ! thb_is_page_layout_b() && ( ! empty( $featured_image ) || ! empty( $header_background_id ) ) ) : ?>
						<?php
							$trips_map_button_text = thb_get_option( 'trips_map_button_text' );

							if ( empty( $trips_map_button_text ) ) {
								$trips_map_button_text = __( 'Show map', 'thb_text_domain' );
							}
						?>
						<a href="#" class="thb-btn" id="thb-show-map"><?php echo $trips_map_button_text; ?></a>
					<?php endif; ?>
				<?php endif; ?>
			</div>

			<?php if ( $show_featured_image && thb_is_page_layout_a() && ! thb_is_archive() && ! is_home() && ! thb_is_blog() ) : ?>
				<?php get_template_part( 'partials/partial-page-featured-image' ); ?>
			<?php endif; ?>

		</div>

	</div>

</div>