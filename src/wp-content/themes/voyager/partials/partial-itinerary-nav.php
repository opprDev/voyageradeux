<?php
	$trip = thb_post_get_trip();
?>

<?php if ( $trip !== false ) : ?>
	<?php
		$trip_url = get_permalink( $trip->ID );
	?>
	<div class="thb-trip-itinerary-nav">
		<h3>
			<a href="<?php echo $trip_url; ?>">
				<?php echo wptexturize( $trip->post_title ); ?>
			</a>
		</h3>

		<?php $posts = thb_trip_get_posts( $trip->ID, '' ); ?>
		<?php if ( ! empty( $posts ) ) : ?>
			<ul>
				<?php foreach( $posts as $post ) : ?>
					<?php
						$post_class = '';

						if( $post->ID == thb_get_page_ID() ) {
							$post_class = 'current';
						}
					?>
					<li class="<?php echo $post_class; ?>">
						<a href="<?php echo get_permalink( $post->ID ); ?>">
							<?php echo wptexturize( $post->post_title ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php $stops = thb_trip_get_stops( $trip->ID ); ?>

		<?php foreach( $stops as $index => $stop ) : ?>
			<?php $posts = thb_trip_get_posts( $trip->ID, $stop['id'] ); ?>
			<div class="thb-trip-itinerary-stop-posts">
				<h4>
					<a href="<?php echo $trip_url; ?>#stop-<?php echo $index; ?>">
						<?php echo wptexturize( $stop['label'] ); ?>
					</a>
				</h4>

				<?php if ( ! empty( $posts ) ) : ?>
					<ul>
						<?php foreach( $posts as $post ) : ?>
							<?php
								$post_class = '';

								if( $post->ID == thb_get_page_ID() ) {
									$post_class = 'current';
								}
							?>
							<li class="<?php echo $post_class; ?>">
								<a href="<?php echo get_permalink( $post->ID ); ?>">
									<?php echo wptexturize( $post->post_title ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
<?php endif; ?>