<?php if ( ! empty( $stop['stop_pictures'] ) ) : ?>
	<?php
		$data_attrs = array(
			'layout'   => thb_isset( $stop, 'mosaic_module', '' ),
			'gutter'   => thb_isset( $stop, 'mosaic_gutter', '' ),
			'lightbox' => '1'
		);
	?>

	<div class="thb-photoset-grid-container">
		<div class="thb-photoset-grid" <?php thb_data_attributes( $data_attrs ); ?>>
			<?php foreach ( $stop['stop_pictures'] as $picture ) {
				thb_image( $picture['id'], thb_isset( $stop, 'mosaic_image_size', 'large' ), array(
					'attr' => array(
						'data-mfp-src' => thb_image_get_size( $picture['id'] ),
						'title' => $picture['caption']
					)
				) );
			}
			?>
		</div>
	</div>
<?php endif; ?>