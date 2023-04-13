<?php

namespace GpTheme\GptSlider\Admin;

class Slider {
	// Register Custom Post Type

	public function __construct() {
		$this->init_hooks();
	}

	/**
	 * Hook into actions and filters.
	 * @since 1.0.0
	 */

	private function init_hooks() {
		add_action( 'init', array( $this, 'register_post_type' ), 0 );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_meta' ) );
	}

	/**
	 * Register Custom Post Type
	 */

	public function register_post_type() {
		$labels = array(
			'name'                  => _x( 'GPT Sliders', 'Post Type General Name', 'gpt-slider' ),
			'singular_name'         => _x( 'GPT Slider', 'Post Type Singular Name', 'gpt-slider' ),
			'menu_name'             => __( 'GPT Slider', 'gpt-slider' ),
			'name_admin_bar'        => __( 'GPT Slider', 'gpt-slider' ),
			'archives'              => __( 'Item Archives', 'gpt-slider' ),
			'attributes'            => __( 'Item Attributes', 'gpt-slider' ),
			'parent_item_colon'     => __( 'Parent Item:', 'gpt-slider' ),
			'all_items'             => __( 'All Items', 'gpt-slider' ),
			'add_new_item'          => __( 'Add New Item', 'gpt-slider' ),
			'add_new'               => __( 'Add New', 'gpt-slider' ),
			'new_item'              => __( 'New Item', 'gpt-slider' ),
			'edit_item'             => __( 'Edit Item', 'gpt-slider' ),
			'update_item'           => __( 'Update Item', 'gpt-slider' ),
			'view_item'             => __( 'View Item', 'gpt-slider' ),
			'view_items'            => __( 'View Items', 'gpt-slider' ),
			'search_items'          => __( 'Search Item', 'gpt-slider' ),
			'not_found'             => __( 'Not found', 'gpt-slider' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'gpt-slider' ),
			'featured_image'        => __( 'Featured Image', 'gpt-slider' ),
			'set_featured_image'    => __( 'Set featured image', 'gpt-slider' ),
			'remove_featured_image' => __( 'Remove featured image', 'gpt-slider' ),
			'use_featured_image'    => __( 'Use as featured image', 'gpt-slider' ),
		];

		$args = array(
			'label'               => __( 'Slider', 'gpt-slider' ),
			'description'         => __( 'Slider', 'gpt-slider' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'thumbnail', 'revisions' ),
			'taxonomies'          => array( 'category', 'post_tag' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 56,
			'menu_icon'           => 'dashicons-images-alt2',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
		);

		register_post_type( 'gpt_slider', $args );
	}

	// Add the meta box
	public function add_meta_box() {
		add_meta_box(
			'gpt_slider_meta_box', // $id
			'GPT Slider', // $title
			array( $this, 'show_meta_box' ), // $callback
			'gpt_slider', // $page
			'normal', // $context
			'high' // $priority
		);
	}

	// Field Array
	private $prefix = 'gpt_slider_';
	private $meta_fields = array(
		// Add multiple slider
		array(
			'label' => 'Slider Image',
			'desc'  => 'Upload an image or enter an URL.',
			'id'    => 'slider_image',
			'type'  => 'image',
		),
		array(
			'label' => 'Slider Title',
			'desc'  => 'Enter the title for the slider',
			'id'    => 'slider_title',
			'type'  => 'text',
		),
		array(
			'label' => 'Slider Description',
			'desc'  => 'Enter the description for the slider',
			'id'    => 'slider_description',
			'type'  => 'textarea',
		),
		array(
			'label' => 'Slider Link',
			'desc'  => 'Enter the link for the slider',
			'id'    => 'slider_link',
			'type'  => 'text',
		),
		array(
			'label' => 'Slider Link Text',
			'desc'  => 'Enter the link text for the slider',
			'id'    => 'slider_link_text',
			'type'  => 'text',
		),

	);

	// The Callback
	public function show_meta_box() {
		global $post;
		// Use nonce for verification
		wp_nonce_field( basename( __FILE__ ), 'gpt_slider_nonce' );
		// Begin the field table and loop
		echo '<table class="form-table">';
		foreach ( $this->meta_fields as $field ) {
			// get value of this field if it exists for this post
			$meta = get_post_meta( $post->ID, $field['id'], true );
			// begin a table row with
			echo '<tr>
					<th><label for="' . $field['id'] . '">' . $field['label'] . '</label></th>
					<td>';
			switch ( $field['type'] ) {
				// text
				case 'text':
					echo '<input type="text" name="' . $field['id'] . '" id="' . $field['id'] . '" value="' . $meta . '" size="30" />
						<br /><span class="description">' . $field['desc'] . '</span>';
					break;
				// textarea
				case 'textarea':
					echo '<textarea name="' . $field['id'] . '" id="' . $field['id'] . '" cols="60" rows="4">' . $meta . '</textarea>
						<br /><span class="description">' . $field['desc'] . '</span>';
					break;
				// checkbox
				case 'checkbox':
					echo '<input type="checkbox" name="' . $field['id'] . '" id="' . $field['id'] . '" ' . checked( $meta, true, false ) . ' />
						<label for="' . $field['id'] . '">' . $field['desc'] . '</label>';
					break;
				// select
				case 'select':
					echo '<select name="' . $field['id'] . '" id="' . $field['id'] . '">';
					foreach ( $field['options'] as $option ) {
						echo '<option ' . selected( $meta, $option['value'], false ) . ' value="' . $option['value'] . '">' . $option['label'] . '</option>';
					}
					echo '</select><br /><span class="description">' . $field['desc'] . '</span>';
					break;
				// radio
				case 'radio':
					foreach ( $field['options'] as $option ) {
						echo '<input type="radio" name="' . $field['id'] . '" value="' . $option['value'] . '" ' . checked( $meta, $option['value'], false ) . ' /> ' . $option['label'] . ' ';
					}
					echo '<br /><span class="description">' . $field['desc'] . '</span>';
					break;
				// image
				case 'image':
					$image = get_template_directory_uri() . '/images/image.png';
					echo '<span class="custom_default_image" style="display:none">' . $image . '</span>';
					if ( $meta ) {
						$image = wp_get_attachment_image_src( $meta, 'medium' );
						$image = $image[0];
					}
					echo '<input name="' . $field['id'] . '" type="hidden" class="custom_upload_image" value="' . $meta . '" />
						<img src="' . $image . '" class="custom_preview_image" alt="" /><br />
						<input class="custom_upload_image_button button" type="button" value="Choose Image" />
						<small> <a href="#" class="custom_clear_image_button">Remove Image</a></small>
						<br clear="all" /><span class="description">' . $field['desc'] . '</span>';
					break;
			} //end switch
			echo '</td></tr>';
		} // end foreach
		echo '</table>'; // end table

	}

	// Save the Data
	public function save_meta( $post_id ) {
		// verify nonce
		if ( ! wp_verify_nonce( $_POST['gpt_slider_nonce'], basename( __FILE__ ) ) ) {
			return $post_id;
		}
		// check autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}
		// check permissions
		if ( 'page' === $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return $post_id;
			}
		} elseif ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		// loop through fields and save the data
		foreach ( $this->meta_fields as $field ) {
			$old = get_post_meta( $post_id, $field['id'], true );
			$new = $_POST[ $field['id'] ];
			if ( $new && $new !== $old ) {
				update_post_meta( $post_id, $field['id'], $new );
			} elseif ( '' === $new && $old ) {
				delete_post_meta( $post_id, $field['id'], $old );
			}
		} // end foreach
	}

	public function add_meta_boxes() {
		add_meta_box(
			'gpt_slider_meta',
			'Slider Options',
			array( $this, 'show_meta_box' ),
			'slider',
			'normal',
			'high'
		);
	}

}