<?php

namespace GpTheme\GptSlider\Admin;

class MetaBox {
	// Properties

	// Methods

	public function __construct() {
		$this->init_hooks();
	}

	private function init_hooks() {
		add_action('add_meta_boxes', [$this, 'my_custom_meta_box']);
		add_action('save_post', [$this, 'save_my_repeater']);
		add_action('save_post', [$this, 'save_slider_settings']);
	}

	function my_custom_meta_box() {
		add_meta_box(
			'my_repeater',
			__('Add Slider Items', 'gpt-slider'),
			[$this, 'gpt_render_slider_post_meta'],
			'gpt_slider',
			'normal',
			'high'
		);

		add_meta_box('slider-settings', 'Slider Settings', [$this, 'slider_settings_callback'], 'gpt_slider');

	}

	function gpt_render_slider_post_meta($post) {
		wp_nonce_field('my_repeater', 'my_repeater_nonce');
		$items = get_post_meta($post->ID, '_gpt_slider_items', true);

//        var_dump($items);

		echo '<div id="gpt-slider-items-wrapper">';
		echo '<div id="gpt-slider-items">';
        echo '<div class="gpt-accordion">';
		if ($items) {
			foreach ($items as $key => $item) {
                $active = $key == 0 ? "style='display: block;'" : '';
                $active_class = $key == 0 ? "active" : '';
				echo '<div class="gpt-accordion-item">';
                echo '<button class="list-heading '.$active_class.'"><h2>' . esc_attr($item['title']) . '</h2></button>';
                echo '<div class="list-text" '.$active.'">';
				echo '<div class="gpt-slider-item">';
				echo '<div class="gpt-slider-item-fields">';
				echo '<div class="gpt-slider-item-field">';
                echo '<label for="title-'.$key.'">Slider Title</label>';
				echo '<input type="text" name="title[]" id="title-'.$key.'" placeholder="Title" value="' . esc_attr($item['title']) . '" />';
                echo '</div>';
				echo '<div class="gpt-slider-item-field">';
                echo '<label for="description-'.$key.'">Slider Description</label>';
                echo '<textarea name="description[]" id="description-'.$key.'" placeholder="Description" rows="2">' . esc_attr($item['description']) . '</textarea>';
                echo '</div>';
				echo '<div class="gpt-slider-button">';
                echo '<div class="gpt-slider-item-field">';
                echo '<label for="button_text-'.$key.'">Slider Button Text</label>';
				echo '<input type="text" name="button_text[]" placeholder="Button Text" value="' . esc_attr($item['button_text']) . '" />';
                echo '</div>';
                echo '<div class="gpt-slider-item-field">';
                echo '<label for="button_link-'.$key.'">Slider Button Link</label>';
				echo '<input type="text" name="button_link[]" placeholder="Button Link" value="' . esc_attr($item['button_link']) . '" />';
                echo '</div>';
                echo '</div>';
                echo '<div class="gpt-slider-image">';
				echo '<div class="gpt-slider-item-field">';
				if ( $item['image']) {
                    echo '<div class="slider-image-preview">';
					echo '<img class="slider-image-preview" src="' . $item['image'] . '" width="150">';
					if ( $item['image'] ) {
						echo  '<button type="button" class="button custom_remove_image_button"><svg xmlns="http://www.w3.org/2000/svg" width="800px" height="800px" viewBox="0 0 24 24" fill="none">
                        <g id="Menu / Close_LG">
                        <path id="Vector" d="M21 21L12 12M12 12L3 3M12 12L21.0001 3M12 12L3 21.0001" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </g>
                        </svg>
                        </button>';
                        }
                        echo '</div>';
                    }
				    echo ' <label for="custom-image-upload">Upload Image:</label>';
                    echo '<div class="slider-image-upload">';
                    echo '<input type="text" name="image[]" id="custom-image-upload" class="regular-text" value="' . esc_attr( $item['image'] ) .'">';
                    echo '<input type="button" class="button custom_upload_image_button" value="Upload Image">';
                    echo '</div>';
                echo '</div>';
                echo '</div>';
				echo '</div>'; // .gpt-slider-item-fields
				echo '<a href="#" class="remove-item button">Remove</a>';
				echo '</div>'; // .gpt-slider-item
                echo '</div>';
                echo '</div>';
			}
		} else {
			echo '<div class="gpt-slider-item">';
			echo '<div class="gpt-slider-item-fields">';
			echo '<div class="gpt-slider-item-field">';
            echo '<label for="title">Slider Title</label>';
			echo '<input type="text" name="title[]" placeholder="Title" />';
            echo '</div>';
            echo '<div class="gpt-slider-item-field">';
            echo '<label for="description">Slider Description</label>';
            echo '<textarea name="description[]" placeholder="Description" rows="5"></textarea>';
            echo '</div>';
            echo '<div class="gpt-slider-button">';
			echo '<div class="gpt-slider-item-field">';
            echo '<label for="button_text">Slider Button Text</label>';
			echo '<input type="text" name="button_text[]" placeholder="Button Text" />';
            echo '</div>';
            echo '</div>';
            echo '<div class="gpt-slider-item-field">';
            echo '<label for="button_link">Slider Button Link</label>';
			echo '<input type="text" name="button_link[]" placeholder="Button Link" />';
            echo '</div>';
			echo '<div class="gpt-slider-image">';
			echo '<div class="gpt-slider-item-field">';
			echo ' <label for="custom-image-upload">Upload Image:</label>';
			echo '<div class="slider-image-upload">';
			echo '<input type="text" name="image[]" id="custom-image-upload" class="regular-text">';
			echo '<input type="button" class="button custom_upload_image_button" value="Upload Image">';
			echo '</div>';
			echo '</div>';
			echo '</div>';
            echo '</div>';
			echo '</div>'; // .gpt-slider-item-fields
			echo '<a href="#" class="remove-item button">Remove</a>';
			echo '</div>'; // .gpt-slider-item
		}
        echo '</div>'; // .gpt-accordion
		echo '</div>'; // #gpt-slider-items
		echo '<a href="#" id="add-item" class="button">Add Item</a>';
		echo '</div>'; // #repeater

	}

	function save_my_repeater($post_id) {
		if (!isset($_POST['my_repeater_nonce']) || !wp_verify_nonce($_POST['my_repeater_nonce'], 'my_repeater')) {
			return;
		}

		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}

		if (!current_user_can('edit_post', $post_id)) {
			return;
		}

		if (!isset($_POST['title']) || !isset($_POST['description']) || !isset($_POST['button_text']) || !isset($_POST['button_link'])) {
			delete_post_meta($post_id, '_gpt_slider_items');
			return;
		}

		$titles = $_POST['title'];
		$descriptions = $_POST['description'];
		$button_texts = $_POST['button_text'];
		$button_links = $_POST['button_link'];
		$image = $_POST['image'];

		$items = array();

		for ($i = 0; $i < count($titles); $i++) {
			$title = sanitize_text_field($titles[$i]);
			$description = sanitize_text_field($descriptions[$i]);
			$button_text = sanitize_text_field($button_texts[$i]);
			$button_link = sanitize_text_field($button_links[$i]);
            // Image Upload sanitize
            $image = sanitize_text_field($image[$i]);



			if (!empty($title) || !empty($description) || !empty($button_link || !empty($image))) {
				$items[] = array(
					'title' => $title,
					'description' => $description,
					'button_text' => $button_text,
					'button_link' => $button_link,
                    'image' => $image,
				);
			}
		}

		update_post_meta($post_id, '_gpt_slider_items', $items);
	}


	function slider_settings_callback($post) {
		wp_nonce_field('slider_settings_nonce', 'slider_settings_nonce');
		$slider_loop = get_post_meta($post->ID, '_slider_loop', true);
		$slider_speed = get_post_meta($post->ID, '_slider_speed', true);
		$slider_per_view = get_post_meta($post->ID, '_slider_per_view', true);
		?>

		<label><input type="checkbox" name="slider_loop" <?php checked($slider_loop, 'on'); ?>>Loop Slider</label>
		<br>
		<label>Slide Speed:</label>
		<input type="number" name="slider_speed" value="<?php echo esc_attr($slider_speed); ?>">
		<br>
		<label>Slides Per View:</label>
		<input type="number" name="slider_per_view" value="<?php echo esc_attr($slider_per_view); ?>">

		<?php
	}


	function save_slider_settings( $post_id ) {
		if ( ! isset( $_POST['slider_settings_nonce'] ) || ! wp_verify_nonce( $_POST['slider_settings_nonce'], 'slider_settings_nonce' ) ) {
			return;
		}

		if ( isset( $_POST['slider_loop'] ) ) {
			update_post_meta( $post_id, '_slider_loop', 'on' );
		} else {
			delete_post_meta( $post_id, '_slider_loop' );
		}

		if ( isset( $_POST['slider_speed'] ) ) {
			update_post_meta( $post_id, '_slider_speed', sanitize_text_field( $_POST['slider_speed'] ) );
		}

		if ( isset( $_POST['slider_per_view'] ) ) {
			update_post_meta( $post_id, '_slider_per_view', sanitize_text_field( $_POST['slider_per_view'] ) );
		}
	}


}