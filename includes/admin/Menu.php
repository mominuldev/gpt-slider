<?php

namespace GpTheme\GptSlider\Admin;

class Menu {
	// Properties

	/**
	 * The single instance of the class.
	 * @var Menu
	 */

	protected static $instance = null;

	/**
	 * Menu Constructor.
	 */

	public function __construct() {
		$this->init_hooks();
	}

	/**
	 * Main Menu Instance.
	 * Ensures only one instance of Menu is loaded or can be loaded.
	 * @return Menu - Main instance.
	 */

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	// Initialize

	/**
	 * Hook into actions and filters.
	 * @since 1.0.0
	 */

	private function init_hooks() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 9 );
		add_action( 'admin_post_save_gpt_slider', [$this, 'admin_post_save_gpt_slider'] );
	}

	/**
	 * Add menu items.
	 */

	public function get_admin_menu_list() {
		$all_menu = array(
			'dashboard' => array(
				'title' => __( 'Dashboard', 'gpt-slider' ),
				'capability' => 'manage_options',
				'slug' => 'gpt_slider',
				'callback' => array( $this, 'gpt_slider_dashboard_page' ),
			),
			'settings' => array(
				'title' => __( 'Settings', 'gpt-slider' ),
				'capability' => 'manage_options',
				'slug' => 'gpt-slider-settings',
				'callback' => array( $this, 'gpt_slider_settings_page' ),
			),
			'help_upgrade' => array(
				'title' => __( 'Help and Upgrade', 'gpt-slider' ),
				'capability' => 'manage_options',
				'slug' => 'gpt-slider-help-upgrade',
				'callback' => array( $this, 'gpt_slider_help_upgrade_page' ),
			),
		);

		return apply_filters('gpt_slider_admin_menu_list', $all_menu);
	}

	public function admin_menu() {
		$all_menu = $this->get_admin_menu_list();

		add_menu_page( __( 'GPT Slider', 'gpt-slider' ), __( 'GPT Slider', 'gpt-slider' ), 'manage_options', 'gpt_slider', [$this, 'gpt_slider_dashboard_page'], 'dashicons-images-alt2', 56);

		foreach ( $all_menu as $key => $menu ) {

			add_submenu_page( 'gpt_slider', $menu['title'], $menu['title'], $menu['capability'], $menu['slug'], $menu['callback'] );
		}
	}

	/**
	 * Init the settings page.
	 */

	public function gpt_slider_settings_page() {
		include_once( dirname( __FILE__ ) . '/views/html-admin-settings.php' );
	}

	/**
	 * Dashboard Page
	 */
	public function gpt_slider_dashboard_page() {
		include_once( dirname( __FILE__ ) . '/views/html-admin-dashboard.php' );
	}

	/**
	 * Help and Upgrade Page
	 */

	public function gpt_slider_help_upgrade_page() {
		include_once( dirname( __FILE__ ) . '/views/html-admin-help-upgrade.php' );
	}
}