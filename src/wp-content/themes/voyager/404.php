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

				<div class="thb-text">
					<p>
						<?php _e( 'Apologies, but the page you requested could not be found.', 'thb_text_domain' ); ?><br />
						<?php _e( 'Perhaps searching will help.', 'thb_text_domain' ); ?>
					</p>

					<?php get_search_form(); ?>
					<script type="text/javascript">
						// focus on search field after it has loaded
						document.getElementById('s') && document.getElementById('s').focus();
					</script>
				</div>

			</div>

		</div>

	</div>

	<?php thb_page_end(); ?>

</div>

<?php thb_page_after(); ?>

<?php get_footer(); ?>