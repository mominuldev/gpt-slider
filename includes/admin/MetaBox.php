<?php

namespace GpTheme\GptSlider\Admin;

class MetaBox {
	// Properties

	// Methods

	public function __construct() {
		$this->init_hooks();
	}

	private function init_hooks() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_post' ) );
	}

	public function add_meta_boxes() {
		add_meta_box(
			'gpt_slider_meta_box',
			__( 'GPT Slider', 'gpt-slider' ),
			array( $this, 'render_meta_box' ),
			'gpt_slider',
			'normal',
			'high'
		);
	}

	// Render Meta Box content.
	public function render_meta_box( $post ) {
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'gpt_slider_meta_box', 'gpt_slider_meta_box_nonce' );
		// Use get_post_meta to retrieve an existing value from the database.
		$slider_id = get_post_meta( $post->ID, 'gpt_slider_id', true );
		// Display the form, using the current value.
		?>
		<p>
			<label for="gpt_slider_id"><?php _e( 'Slider ID', 'gpt-slider' ); ?></label>
			<input type="text" id="gpt_slider_id" name="gpt_slider_id" value="<?php echo esc_attr( $slider_id ); ?>" size="25" />
		</p>
		<?php
	}

	// Save Meta Box content.
	public function save_post( $post_id ) {
		// Check if our nonce is set.
		if ( ! isset( $_POST['gpt_slider_meta_box_nonce'] ) ) {
			return;
		}
		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['gpt_slider_meta_box_nonce'], 'gpt_slider_meta_box' ) ) {
			return;
		}
		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}
		/* OK, it's safe for us to save the data now. */
		// Make sure that it is set.
		if ( ! isset( $_POST['gpt_slider_id'] ) ) {
			return;
		}
		// Sanitize user input.
		$slider_id = sanitize_text_field( $_POST['gpt_slider_id'] );
		// Update the meta field in the database.
		update_post_meta( $post_id, 'gpt_slider_id', $slider_id );
	}



}