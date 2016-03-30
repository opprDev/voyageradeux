<?php
	$decoded_field_value = html_entity_decode( $field_value, ENT_QUOTES );
	parse_str( $decoded_field_value, $stop );
	$stop = stripslashes_deep( $stop );

	$tooltip = __( 'Click to edit', 'thb_text_domain' );
	$default_label = __( 'New trip stop', 'thb_text_domain' );
	$label = ! empty( $stop['label'] ) ? $stop['label'] : $default_label;
?>

<input type="hidden" class="thb-stop-value" name="<?php echo $field_name; ?>" value="<?php echo $field_value; ?>">
<span class="thb-stop-edit handle">
	<a href="#" class="thb-small-btn tt" data-default-label="<?php echo $default_label; ?>" title="<?php echo $tooltip; ?>"><?php echo $label; ?></a>
</span>