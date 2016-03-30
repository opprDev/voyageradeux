<?php

if ( ! isset( $slides ) ) {
	$slides = '';
}

if ( ! isset( $image_size ) ) {
	$image_size = '';
}

if ( ! isset( $featured_image_config ) ) {
	$featured_image_config = '';
}

if ( ! isset( $slides_config ) ) {
	$slides_config = array();
}

$slides_display = thb_get_post_meta( thb_get_page_ID(), 'voyager_single_work_display' );
$is_lightbox_disabled = thb_get_post_meta( thb_get_page_ID(), 'disable_work_image_link' );

$slides_have_video = false;

if ( ! empty( $slides ) ) {
	foreach ( $slides as $slide ) {
		if ( $slide['type'] != 'image' ) {
			$slides_have_video = true;
			break;
		}
	}
}

$is_slideshow = $slides_display == 'slideshow';
$is_mosaic = $slides_display == 'mosaic' && ! $slides_have_video;
$is_stream = $slides_display == '';

if ( $is_lightbox_disabled ) {
	$slides_config['link'] = false;
}

$data_attrs = array();
$classes = array(
	'work-slides-container',
	'thb-images-container'
);

if ( $is_slideshow ) {
	$classes[] = 'thb-work-slideshow rsTHB';
}
elseif ( $is_mosaic ) {
	$classes[] = 'thb-photoset-grid';

	$mosaic_module = thb_get_post_meta( thb_get_page_ID(), 'voyager_single_work_mosaic_module' );
	$mosaic_gutter = thb_get_post_meta( thb_get_page_ID(), 'voyager_single_work_mosaic_gutter' );
	$image_size = thb_get_post_meta( thb_get_page_ID(), 'voyager_single_work_mosaic_image_size' );
	$mosaic_module_repeat = 1;

	if ( $mosaic_module != '' ) {
		$mosaic_module_count = array_sum( str_split( $mosaic_module ) );

		if ( $mosaic_module_count < count( $slides ) ) {
			$mosaic_module_repeat = absint( count( $slides ) / $mosaic_module_count );
			$mosaic_module_repeat += count( $slides ) % $mosaic_module_count;
		}
	}

	$data_attrs['layout'] = str_repeat( $mosaic_module, $mosaic_module_repeat );
	$data_attrs['gutter'] = $mosaic_gutter;
	$data_attrs['lightbox'] = (int) ! $is_lightbox_disabled;
}

?>

<?php if ( ! empty( $slides ) ) : ?>
	<div class="<?php thb_classes( $classes ); ?>" <?php thb_data_attributes( $data_attrs ); ?>>
		<?php
			foreach ( $slides as $slide ) {
				if ( $is_slideshow || $is_stream ) {
					echo "<div class='slide thb-image-wrapper " . esc_attr( $slide['class'] ) . "'>";
				}

					if ( $slide['type'] == 'image' ) {
						if ( ! isset( $slides_config['attr'] ) ) {
							$slides_config['attr'] = array();
						}

						if ( $is_slideshow ) {
							$slides_config['attr']['class'] = 'rsImg';
						}
						elseif ( $is_mosaic ) {
							$slides_config['attr']['data-mfp-src'] = thb_image_get_size( $slide['id'] );
							$slides_config['attr']['title'] = $slide['caption'];
							$slides_config['link'] = false;
							$slides_config['overlay'] = false;
						}

						thb_image( $slide['id'], $image_size, $slides_config );
					}
					elseif ( ! $is_mosaic ) {
						echo "<a class='mfp-iframe' href='" . $slide['id'] . "'></a>";
						thb_video( $slide['id'], array( 'holder' => false ) );
					}

					if ( ! $is_mosaic ) {
						if ( $slide['caption'] != '' ) {
							echo "<div class='thb-caption-content'>";
								echo $slide['caption'];
							echo "</div>";
						}
					}

				if ( $is_slideshow || $is_stream ) {
					echo "</div>";
				}
			}
		?>
	</div>
<?php endif; ?>