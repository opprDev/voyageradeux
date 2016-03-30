<?php
/**
 * @package WordPress
 * @subpackage Voyager
 * @since Voyager 1.0
 */
?>
				<?php thb_footer_layout(); ?>

				<?php get_template_part('partials/partial-footer'); ?>

			</div><!-- /#thb-inner-wrapper -->

		</div><!-- /#thb-external-wrapper -->

		<a href="#" class="thb-scrollup thb-go-top"><?php _e( 'Go top', 'thb_text_domain' ); ?></a>

		<aside id="slide-menu-container" class="<?php echo thb_voyager_get_slide_navigation_skin() ?>">
			<a id="thb-trigger-close" href="#"><?php _e( 'Close', 'thb_text_domain' ); ?></a>

			<div class="slide-menu-container-wrapper">
				<?php if ( thb_is_header_layout_b() ) : ?>
					<nav id="slide-nav" class="slide-navigation primary">
						<h2 class="hidden"><?php _e( 'Main navigation', 'thb_text_domain' ); ?></h2>
						<?php wp_nav_menu( array( 'theme_location' => apply_filters( 'thb_voyager_slide_navigation_primary', 'primary' ) ) ); ?>
					</nav>
				<?php endif; ?>

				<nav id="slide-nav" class="slide-navigation mobile">
					<h2 class="hidden"><?php _e( 'Secondary navigation', 'thb_text_domain' ); ?></h2>
					<?php wp_nav_menu( array( 'theme_location' => apply_filters( 'thb_voyager_slide_navigation_mobile', 'mobile' ) ) ); ?>
				</nav>

				<?php dynamic_sidebar( 'hamburger-sidebar' ); ?>
			</div>

		</aside>

		<?php thb_body_end(); ?>

		<?php thb_footer(); ?>
		<?php wp_footer(); ?>
	</body>
</html>