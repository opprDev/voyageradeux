<?php
$skin_class = '';

if ( thb_is_page_layout_extended() ) {
	if ( thb_is_page_layout_g() ) {
		$skin_class = thb_voyager_pagecontent_skin();
	}
	else {
		$skin_class = thb_voyager_get_header_skin();
	}
} else {
	$skin_class = thb_voyager_pagecontent_skin();
}

?>

<?php thb_nav_before(); ?>

<div class="thb-main-nav-wrapper <?php echo $skin_class; ?>">

	<?php if ( thb_has_side_menu() && thb_get_logo_position() == 'logo-right' ) : ?>
		<?php thb_hamburger_nav_icon(); ?>
	<?php endif; ?>

		<nav id="main-nav" class="main-navigation primary">
			<h2 class="hidden"><?php _e( 'Main navigation', 'thb_text_domain' ); ?></h2>
			<?php thb_nav_start(); ?>

			<?php if ( thb_is_header_layout_a() ) : ?>
				<?php wp_nav_menu( array( 'theme_location' => apply_filters( 'thb_voyager_main_navigation_primary', 'primary' ), 'walker' => new THB_MegaMenuWalker ) ); ?>
			<?php endif; ?>

			<?php thb_nav_end(); ?>
		</nav>

	<?php if ( thb_has_side_menu() && thb_get_logo_position() == 'logo-left' ) : ?>
		<?php thb_hamburger_nav_icon(); ?>
	<?php endif; ?>

</div>
<?php thb_nav_after(); ?>