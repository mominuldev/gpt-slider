<?php

// Create custom metabox for repeater field
function custom_repeater_metabox() {
	add_meta_box(
		'custom_repeater_metabox',
		__( 'Custom Repeater Field', 'custom-repeater' ),
		'custom_repeater_metabox_callback',
		'gpt_slider',
		'normal',
		'default'
	);
}

add_action( 'add_meta_boxes', 'custom_repeater_metabox' );

// Callback function for repeater metabox
function custom_repeater_metabox_callback( $post ) {
	wp_nonce_field( 'custom_repeater_metabox', 'custom_repeater_metabox_nonce' );

	// Get existing repeater field values
	$custom_repeater_values = get_post_meta( $post->ID, '_custom_repeater_values', true );

	var_dump($custom_repeater_values);

	if ( ! $custom_repeater_values ) {
		$custom_repeater_values = array(
			array(
				'title'        => '',
				'description'  => '',
				'button_label' => '',
				'button_url'   => '',
				'image'        => '',
			),
		);
	}

	// Create repeater field table
	echo '<div class="custom-repeater-wrapper">';
	echo '<table class="custom-repeater">';
	echo '<thead><tr><th>Title</th><th>Description</th><th>Button Label</th><th>Button URL</th><th>Image</th><th></th></tr></thead>';
	echo '<tbody>';
	foreach ( $custom_repeater_values as $key => $value ) {
	echo '<tr class="custom-repeater-row">';
		echo '<td><input type="text" name="custom_repeater[' . $key . ']" value="' . esc_attr( $value['title'] ) . '" /></td>';
		echo '<td><textarea name="custom_repeater[' . $key . '][description]">' . esc_textarea( $value['description'] ) . '</textarea></td>';
		echo '<td><input type="text" name="custom_repeater[' . $key . '][button_label]" value="' . esc_attr( $value['button_label'] ) . '" /></td>';
		echo '<td><input type="url" name="custom_repeater[' . $key . '][button_url]" value="' . esc_url( $value['button_url'] ) . '" /></td>';
		echo '<td>';
			echo '<div class="custom-media-field">';
				echo '<div class="custom-media-preview">';
					if ( $value['image'] ) {
					echo '<img src="' . esc_url( wp_get_attachment_url( $value['image'] ) ) . '" alt="" style="max-width: 100%; max-height: 150px; display: block;" />';
					}
					echo '</div>';
				echo '<input type="hidden" name="custom_repeater[' . $key . '][image]" class="custom-media-id" value="' . esc_attr( $value['image'] ) . '" />';
				echo '<button type="button" class="button custom-media-upload">' . __( 'Select Image', 'custom-repeater' ) . '</button>';
				echo '</div>';
			echo '</td>';
		echo '<td><button type="button" class="button custom-repeater-remove">' . __( 'Remove', 'custom-repeater' ) . '</button></td>';
		echo '</tr>';
	}
	echo '</tbody>';
	echo '</table>';

  // Add button to add new repeater item
  echo '<button type="button" class="button custom-repeater-add">' . __( 'Add Item', 'custom-repeater' ) . '</button>';
  echo '</div>';

  // JavaScript code to handle repeater field functionality
  ?>
  <script>
	  jQuery(document).ready(function () {
		  // Handle repeater field remove item click
		  jQuery('.custom-repeater').on('click', '.custom-repeater-remove', function () {
			  jQuery(this).closest('tr').remove();
		  });

		  // Handle repeater field add item click
		  jQuery('.custom-repeater-wrapper').on('click', '.custom-repeater-add', function (e) {
			  var $tbody = jQuery('.custom-repeater tbody');
			  var $prototype = $tbody.find('tr.custom-repeater-row:first');

			  var $row = $prototype.clone();

			  // Reset field values
			  $row.find('input[type=text], input[type=url], textarea, select').val('');
			  $row.find('.custom-media-preview').html('');
			  $row.find('.custom-media-id').val('');

			  // Append new row
			  $tbody.append($row);
		  });

		  // Handle repeater field media upload
		  jQuery('.custom-repeater').on('click', '.custom-media-upload', function (e) {
			  e.preventDefault();
			  var $button = jQuery(this);
			  var $field = $button.closest('.custom-media-field');
			  var $preview = $field.find('.custom-media-preview');
			  var $id = $field.find('.custom-media-id');

			  var file_frame = wp.media.frames.file_frame = wp.media({
				  title: '<?php _e( 'Select or Upload Image', 'custom-repeater' ); ?>',
				  button: {
					  text: '<?php _e( 'Use this Image', 'custom-repeater' ); ?>',
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
function custom_repeater_save_metabox( $post_id ) {
	// Verify nonce
	if ( ! isset( $_POST['custom_repeater_metabox_nonce'] ) || ! wp_verify_nonce( $_POST['custom_repeater_metabox_nonce'], 'custom_repeater_metabox' ) ) {
		return;
	}

	// Check if autosave or revision
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( wp_is_post_revision( $post_id ) ) {
		return;
	}

	var_dump($_POST['custom_repeater']);

	// Save repeater field data
	if ( isset( $_POST['custom_repeater'] ) ) {
		$repeater_data = array();
		foreach ( $_POST['custom_repeater'] as $key => $value ) {
			if ( isset( $value['title'] ) ) {
				$repeater_data[] = array(
					'title'        => sanitize_text_field( $value['title'] ),
					'description'  => sanitize_textarea_field( $value['description'] ),
					'button_label' => sanitize_text_field( $value['button_label'] ),
					'button_url'   => esc_url_raw( $value['button_url'] ),
					'image'     => absint( $value['image'] ),
				);
			}
		}
	}

//	update_post_meta( $post_id, 'custom_repeater', $repeater_data );
//	error_log( 'custom_repeater_save_metabox update_post_meta called for post ID ' . $post_id );
//	update_post_meta( $post_id, 'custom_repeater', $repeater_data );

	error_log( 'custom_repeater_save_metabox called for post ID ' . $post_id );
}

// Save custom repeater field data
add_action( 'save_post', 'custom_repeater_save_metabox' );