<?php

// Create custom metabox for repeater field
function gpt_repeater_metabox() {
	add_meta_box(
		'gpt_repeater_metabox',
		__( 'Slider Items', 'gpt-slider' ),
		'gpt_repeater_metabox_callback',
		'gpt_slider',
		'normal',
		'default'
	);
}

add_action( 'add_meta_boxes', 'gpt_repeater_metabox' );

// Callback function for repeater metabox
function gpt_repeater_metabox_callback( $post ) {
	wp_nonce_field( 'gpt_repeater_metabox', 'gpt_repeater_metabox_nonce' );

	// Get existing repeater field values
	$custom_repeater_values = get_post_meta( $post->ID, '_gpt_slider_repeater_values', true );

	// Create repeater field table
	echo '<div class="gpt-slider-wrapper">';
	echo '<table class="gpt-slider">';
	echo '<thead><tr><th>Title</th><th>Description</th><th>Button Label</th><th>Button URL</th><th>Image</th><th></th></tr></thead>';
	echo '<tbody>';
	if($custom_repeater_values ) {
	foreach ( $custom_repeater_values as $key => $value ) {
		echo '<tr class="gpt-slider-row">';
		echo '<td><input type="text" name="title[]" value="' . esc_attr( $value['title'] ) . '" /></td>';
		echo '<td><textarea name="description[]">' . esc_textarea( $value['description'] ) . '</textarea></td>';
		echo '<td><input type="text" name="button_label[]" value="' . esc_attr( $value['button_label'] ) . '" /></td>';
		echo '<td><input type="text" name="button_url[]" value="' . esc_url( $value['button_url'] ) . '" /></td>';
		echo '<td>';
		echo '<div class="custom-media-field">';
		echo '<div class="gpt-media-preview">';
		if ( $value['image'] ) {
			echo '<img src="' . esc_url( wp_get_attachment_url( $value['image'] ) ) . '" alt="" style="max-width: 100%; max-height: 150px; display: block;" />';
		}
		echo '</div>';
		echo '<input type="hidden" name="image[]" class="gpt-media-id" value="' . esc_attr( $value['image'] ) . '" />';
		echo '<button type="button" class="button gpt-slider-media-upload">' . __( 'Select Image', 'gpt-slider' ) . '</button>';
		echo '</div>';
		echo '</td>';
		echo '<td><button type="button" class="button gpt-slider-remove">' . __( 'Remove', 'gpt-slider' ) . '</button></td>';
		echo '</tr>';
	}
	} else {
		echo '<tr class="gpt-slider-row">';
		echo '<td><input type="text" name="title[]" value="" /></td>';
		echo '<td><textarea name="description[]"></textarea></td>';
		echo '<td><input type="text" name="button_label[]" value="" /></td>';
		echo '<td><input type="text" name="button_url[]" value="" /></td>';
		echo '<td>';
		echo '<div class="custom-media-field">';
		echo '<div class="gpt-media-preview">';
		echo '</div>';
		echo '<input type="hidden" name="image[]" class="gpt-media-id" value="" />';
		echo '<button type="button" class="button gpt-slider-media-upload">' . __( 'Select Image', 'gpt-slider' ) . '</button>';
		echo '</div>';
		echo '</td>';
		echo '<td><button type="button" class="button gpt-slider-remove">' . __( 'Remove', 'gpt-slider' ) . '</button></td>';
		echo '</tr>';
	}
	echo '</tbody>';
	echo '</table>';

	// Add button to add new repeater item
	echo '<button type="button" class="button gpt-slider-add">' . __( 'Add Item', 'gpt-slider' ) . '</button>';
	echo '</div>';

	// JavaScript code to handle repeater field functionality
	?>
	<script>
		jQuery(document).ready(function () {
			// Handle repeater field remove item click
			jQuery('.gpt-slider').on('click', '.gpt-slider-remove', function () {
				jQuery(this).closest('tr').remove();
			});

			// Handle repeater field add item click
			jQuery('.gpt-slider-wrapper').on('click', '.gpt-slider-add', function (e) {
				var $tbody = jQuery('.gpt-slider tbody');
				var $prototype = $tbody.find('tr.gpt-slider-row:first');

				var $row = $prototype.clone();

				// Reset field values
				$row.find('input[type=text], input[type=url], textarea, select').val('');
				$row.find('.gpt-media-preview').html('');
				$row.find('.gpt-media-id').val('');

				// Append new row
				$tbody.append($row);
			});

			// Handle repeater field media upload
			jQuery('.gpt-slider').on('click', '.gpt-slider-media-upload', function (e) {
				e.preventDefault();
				var $button = jQuery(this);
				var $field = $button.closest('.custom-media-field');
				var $preview = $field.find('.gpt-media-preview');
				var $id = $field.find('.gpt-media-id');

				var file_frame = wp.media.frames.file_frame = wp.media({
					title: '<?php _e( 'Select or Upload Image', 'gpt-slider' ); ?>',
					button: {
						text: '<?php _e( 'Use this Image', 'gpt-slider' ); ?>',
					},
					multiple: false,
				});

				file_frame.on('select', function () {
					var attachment = file_frame.state().get('selection').first().toJSON();
					$preview.html('<img src="' + attachment.url + '" alt="" style="max-width: 100%; max-height: 150px; display: block;" />');
					$id.val(attachment.id);
				});

				file_frame.open();
			});
		});
	</script>
	<?php
}

// Save custom repeater field data
function gpt_slider_repeater_save_metabox( $post_id ) {
	// Verify nonce
	if ( ! isset( $_POST['gpt_repeater_metabox_nonce'] ) || ! wp_verify_nonce( $_POST['gpt_repeater_metabox_nonce'], 'gpt_repeater_metabox' ) ) {
		return;
	}

	// Check if autosave or revision
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( wp_is_post_revision( $post_id ) ) {
		return;
	}

	$titles = $_POST['title'];
	$descriptions = $_POST['description'];
	$button_labels = $_POST['button_label'];
	$button_urls = $_POST['button_url'];
	$image_ids = $_POST['image'];

	$items = array();

	for ($i = 0; $i < count($titles); $i++) {
		$title = sanitize_text_field($titles[$i]);
		$description = sanitize_text_field($descriptions[$i]);
		$button_label = sanitize_text_field($button_labels[$i]);
		$button_url = sanitize_text_field($button_urls[$i]);
//		$image = sanitize_text_field($image[$i]);
		$image_id = absint($image_ids[$i]);

		if (!empty($title) || !empty($description) || !empty($button_label) || !empty($button_url || !empty($image))) {
			$items[] = array(
				'title' => $title,
				'description' => $description,
				'button_label' => $button_label,
				'button_url' => $button_url,
				'image' => $image_ids[$i],
			);
		}
	}

	update_post_meta( $post_id, '_gpt_slider_repeater_values', $items );
}

// Save custom repeater field data
add_action( 'save_post', 'gpt_slider_repeater_save_metabox' );