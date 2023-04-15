<?php

namespace GpTheme\GptSlider;

class Assets {
	// Properties

	/**
	 * The single instance of the class.
	 * @var Assets
	 */

	protected static $instance = NULL;

	/**
	 * Main Assets Instance.
	 * Ensures only one instance of Assets is loaded or can be loaded.
	 * @return Assets - Main instance.
	 */

	/**
	 * GptSlider Constructor.
	 */

	public function __construct() {
		$this->init_hooks();
	}

	/**
	 * Hook into actions and filters.
	 * @since 1.0.0
	 */

	private function init_hooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
	}

	/**
	 * Enqueue scripts.
	 * @since 1.0.0
	 */

	public function enqueue_scripts() {
		wp_enqueue_style( 'gpt-slider', GPT_SLIDER_ASSETS . '/css/gpt-slider.css', array(), GPT_SLIDER_VERSION );
		wp_enqueue_script( 'gpt-slider', GPT_SLIDER_ASSETS . '/js/gpt-slider.js', array( 'jquery' ), GPT_SLIDER_VERSION, true );
	}

	/**
	 * Enqueue admin scripts.
	 * @since 1.0.0
	 */

	public function enqueue_admin_scripts( $hook ) {
		global $post_type;
		wp_enqueue_style( 'gpt-slider-admin', GPT_SLIDER_ASSETS . '/css/admin.css', array(), GPT_SLIDER_VERSION );
		wp_enqueue_script( 'gpt-slider-admin', GPT_SLIDER_ASSETS . '/js/admin.js', array( 'jquery' ), GPT_SLIDER_VERSION, true );

		if ( 'post.php' == $hook || 'post-new.php' == $hook ) {
			if ( 'gpt_slider' == $post_type ) {

				wp_enqueue_media();
				wp_enqueue_script('jquery');
				wp_enqueue_script('jquery-ui-core');
				wp_enqueue_script('jquery-ui-sortable');
				wp_enqueue_script('repeater-metabox', GPT_SLIDER_ASSETS . '/js/admin-post.js', array('jquery'), '1.0', true);
//				wp_enqueue_style('repeater-metabox', GPT_SLIDER_ASSETS . '/css/metabox.css', array(), '1.0');


//				wp_enqueue_script( 'gpt-slider-admin-post', GPT_SLIDER_ASSETS . '/js/admin-post.js', array( 'jquery' ), GPT_SLIDER_VERSION, true );

				// Enqueue styles for metaboxes
				wp_enqueue_style( 'gpt-slider-admin-metabox', GPT_SLIDER_ASSETS . '/css/metabox.css', array(), GPT_SLIDER_VERSION );
			}
		}

	}
}