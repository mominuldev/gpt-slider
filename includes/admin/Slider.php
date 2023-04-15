<?php

namespace GpTheme\GptSlider\Admin;

class Slider {

	// Properties
	protected $post_type = 'gpt_slider';

	/**
	 * The single instance of the class.
	 * @var Slider
	 */

	protected static $instance = null;


	public function __construct() {
		$this->init_hooks();
	}

	/**
	 * Hook into actions and filters.
	 * @since 1.0.0
	 */

	private function init_hooks() {
		add_action( 'init', array( $this, 'register_post_type' ), 0 );
//		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
//		add_action( 'save_post', array( $this, 'save_meta' ) );

		// Columns
		add_filter( 'manage_edit-' . $this->post_type . '_columns', array( $this, 'add_new_slider_columns' ) );
		add_action( 'manage_' . $this->post_type . '_posts_custom_column', array( $this, 'manage_slider_columns' ), 2 );

		// Sortable Columns
//		add_filter( 'manage_edit-' . $this->post_type . '_sortable_columns', array( $this, 'slider_column_register_sortable' ) );
//		add_action( 'pre_get_posts', array( $this, 'slider_column_orderby' ) );

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
			'featured_image'        => __( 'Slider Image', 'gpt-slider' ),
			'set_featured_image'    => __( 'Set Slider image', 'gpt-slider' ),
			'remove_featured_image' => __( 'Remove Slider image', 'gpt-slider' ),
			'use_featured_image'    => __( 'Use as Slider image', 'gpt-slider' )
		);

		$args = array(
			'label'               => __( 'Slider', 'gpt-slider' ),
			'description'         => __( 'Slider', 'gpt-slider' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'thumbnail', 'revisions' ),
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
		);

		register_post_type( $this->post_type, $args );
	}

	/**
	 * Add Meta Boxes
	 */

	public function add_meta_boxes() {
		add_meta_box(
			'slider_image',
			__( 'Slider Image', 'gpt-slider' ),
			array( $this, 'slider_image' ),
			$this->post_type,
			'normal',
			'high'
		);
	}

	/**
	 * Add new shortcode column
	 */

	public function add_new_slider_columns( $columns ) {
		$columns['shortcode'] = __( 'Shortcode', 'gpt-slider' );
		return $columns;
	}

	/**
	 * Manage slider columns
	 */

	public function manage_slider_columns( $column ) {
		global $post;
		switch ( $column ) {
			case 'shortcode' :
				echo '<input type="text" value="[gpt_slider id=&quot;' . $post->ID . '&quot;]" readonly="readonly" />';
				break;
		}
	}
}