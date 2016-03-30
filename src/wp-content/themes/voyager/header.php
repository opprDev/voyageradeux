<?php
/**
 * @package WordPress
 * @subpackage Voyager
 * @since Voyager 1.0
 */
thb_before_doctype();
?>
<!doctype html>
<html <?php language_attributes(); ?> <?php thb_html_class(); ?>>
	<head>
		<?php thb_head_meta(); ?>
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>

		<?php thb_body_start(); ?>

		<div id="thb-main-external-wrapper">

			<?php get_template_part( 'partials/partial-header' ); ?>

			<div id="thb-inner-wrapper">