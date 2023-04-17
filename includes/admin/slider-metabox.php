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
	echo '<div class="gpt-slider">';
	echo '<div class="gpt-accordion">';
	if($custom_repeater_values ) {
		foreach ( $custom_repeater_values as $key => $value ) {
			$active = $key == 0 ? "style='display: block;'" : '';
			$active_class = $key == 0 ? "active" : '';
			echo '<div class="gpt-accordion-item">';
			echo '<button class="list-heading '.$active_class.'"><h2>' . esc_attr($value['title']) . '</h2></button>';
			echo '<div class="list-text" '.$active.'">';
			echo '<div class="gpt-slider-item">';
			echo '<div class="gpt-slider-item-field">';
			echo '<label for="title-'.$key.'">Slider Title</label>';
			echo '<input type="text" name="title[]" value="' . esc_attr( $value['title'] ) . '" id="title-'.$key.'" />';
			echo '</div>';
			echo '</div>';
			echo '<div class="gpt-slider-item">';
			echo '<div class="gpt-slider-item-field">';
			echo '<label for="title-'.$key.'">Slider Description</label>';
			echo '<textarea name="description[]" id="description-'.$key.'">' . esc_textarea( $value['description'] ) . '</textarea>';
			echo '</div>';
			echo '</div>';
			echo '<div class="gpt-slider-item">';
			echo '<div class="gpt-slider-item-field">';
			echo '<label for="title-'.$key.'">Slider Button Label</label>';
			echo '<input type="text" name="button_label[]" value="' . esc_attr( $value['button_label'] ) . '" id="button-'.$key.'" />';
			echo '</div>';
			echo '</div>';
			echo '<div class="gpt-slider-item">';
			echo '<div class="gpt-slider-item-field">';
			echo '<label for="url-'.$key.'">Slider Button URL</label>';
			echo '<input type="text" name="button_url[]" value="' . esc_url( $value['button_url'] ) . '" id="url-'.$key.'" />';
			echo '</div>';
			echo '</div>';
			echo '<div class="gpt-slider-item">';
			echo '<div class="gpt-slider-item-field">';
			echo '<label for="image">Slider Image</label>';
			echo '<div class="gpt-media-preview">';
			if ( $value['image'] ) {
				echo '<img src="' . esc_url( wp_get_attachment_url( $value['image'] ) ) . '" alt="" style="max-width: 100%; max-height: 150px; display: block;" />';
			}
			if ( $value['image'] ) {
				echo  '<button type="button" class="button custom_remove_image_button"><svg xmlns="http://www.w3.org/2000/svg" width="800px" height="800px" viewBox="0 0 24 24" fill="none">
                        <g id="Menu / Close_LG">
                        <path id="Vector" d="M21 21L12 12M12 12L3 3M12 12L21.0001 3M12 12L3 21.0001" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </g>
                        </svg>
                        </button>';
			}
			echo '</div>';
			echo '<input type="hidden" name="image[]" class="gpt-media-id" value="' . esc_attr( $value['image'] ) . '" />';
			echo '<div>';
            echo '<button type="button" class="button gpt-slider-media-upload">' . __( 'Select Image', 'gpt-slider' ) . '</button>';

            echo '</div>';
			echo '</div>';
			echo '</div>';


			echo '<div class="gpt-slider-item"><button type="button" class="button gpt-slider-remove">' . __( 'Remove', 'gpt-slider' ) . '</button></div>';
			echo '</div>';
			echo '</div>';
		}
	} else {
		echo '<div class="gpt-slider-row">';
		echo '<div class="gpt-slider-item">';
		echo '<div class="gpt-slider-item-field">';
		echo '<label for="title">Slider Title</label>';
		echo '<input type="text" name="title[]" value="" id="tltle" />';
		echo'</div>';
		echo'</div>';
		echo '<div class="gpt-slider-item">';
		echo '<div class="gpt-slider-item-field">';
		echo '<label for="description">Slider Description</label>';
		echo '<textarea name="description[]" id="description"></textarea>';
		echo '</div>';
		echo '</div>';
		echo '<div class="gpt-slider-item">';
		echo '<div class="gpt-slider-item-field">';
		echo '<label for="button_label">Slider Button Label</label>';
		echo '<input type="text" name="button_label[]" value="" id="button_label" />';
		echo '</div>';
		echo '</div>';
		echo '<div class="gpt-slider-item">';
		echo '<div class="gpt-slider-item-field">';
		echo '<label for="button_url">Slider Button URL</label>';
		echo '<input type="text" name="button_url[]" value="" id="button_url" />';
		echo '</div>';
		echo '</div>';
		echo '<div class="gpt-slider-item">';
		echo '<div class="gpt-slider-item-field">';
		echo '<label for="image">Slider Image</label>';
		echo '<div class="gpt-media-preview">';
		echo '<input type="hidden" name="image[]" class="gpt-media-id" value="" />';
		echo '</div>';
		echo '<button type="button" class="button gpt-slider-media-upload">' . __( 'Select Image', 'gpt-slider' ) . '</button>';
		echo '</div>';
		echo '</div>';
		echo '</div>';
	}

	echo '</div>';
	echo '</div>';

	// Add button to add new repeater item
	echo '<button type="button" class="button gpt-slider-add">' . __( 'Add Item', 'gpt-slider' ) . '</button>';
	echo '</div>';

	// JavaScript code to handle repeater field functionality
	?>
    <script>
        jQuery(document).ready(function () {
            // Handle repeater field remove item click
            jQuery('.gpt-slider').on('click', '.gpt-slider-remove', function () {

                jQuery(this).closest('.gpt-accordion-item').remove();
            });

            // Handle repeater field add item click
            jQuery('.gpt-slider-wrapper').on('click', '.gpt-slider-add', function (e) {
                var $tbody = jQuery('.gpt-accordion');
                var $prototype = $tbody.find('.gpt-accordion-item:first');

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
                var $field = $button.closest('.gpt-slider-item');
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

            // Remove image
            jQuery('.custom_remove_image_button').click(function() {
                var answer = confirm('Are you sure?');
                if (answer == true) {
                    var $preview = jQuery(this).closest('.gpt-slider-item').find('.gpt-media-preview');
                    var $id = jQuery(this).closest('.gpt-slider-item').find('.gpt-media-id');
                    $preview.html('');
                    $id.val('');
                    return true;
                }
                return false;

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