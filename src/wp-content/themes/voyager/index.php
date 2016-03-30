<?php
/**
 * @package WordPress
 * @subpackage Voyager
 * @since Voyager 1.0
 */
get_header(); ?>

	<?php thb_page_before(); ?>

		<div id="thb-page-content">
			<?php thb_page_start(); ?>

			<?php thb_page_content_start(); ?>

			<div class="thb-content-section <?php echo thb_voyager_pagecontent_skin(); ?>">

				<div class="thb-content-section-inner-wrapper">

					<div id="thb-main-content">

						<?php get_template_part('loop/blog', 'classic'); ?>

					</div>

					<?php thb_display_sidebar('post-sidebar', 'main'); ?>

				</div>

			</div>

			<?php thb_page_end(); ?>

		</div>

	<?php thb_page_after(); ?>

<?php get_footer(); ?>