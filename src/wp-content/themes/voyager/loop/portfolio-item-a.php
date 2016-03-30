<?php
	$thb_fi = $args['thb_fi'];
?>

<?php if( $thb_fi != '' ) : ?>
	<a href="<?php echo thb_portfolio_item_get_permalink(); ?>" rel="bookmark" class="work-thumb">
		<img src="<?php echo $thb_fi; ?>" alt="">

		<div class="thb-work-overlay">
			<span class="thb-work-detail"></span>
		</div>
	</a>
<?php endif; ?>

<?php thb_get_template_part( 'templates/thb-portfolio/portfolio-item-data-a', $args ); ?>