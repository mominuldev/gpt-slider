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
	}

	/**
	 * Add menu items.
	 */

	public function admin_menu() {
		add_menu_page(
			__( 'GPT Slider', 'gpt-slider' ),
			__( 'GPT Slider', 'gpt-slider' ),
			'manage_options',
			'gpt-slider',
			array( $this, 'gpt_slider_dashboard_page' ),
			'dashicons-images-alt2',
			56 );

		// Dashboard Submenu
		add_submenu_page(
			'gpt-slider',
			__( 'Dashboard', 'gpt-slider' ),
			__( 'Dashboard', 'gpt-slider' ),
			'manage_options',
			'gpt-dashboard',
			array( $this, 'gpt_slider_dashboard_page' ) );

		// Register Settings Submenu
		add_submenu_page(
			'gpt-slider',
			__( 'Settings', 'gpt-slider' ),
			__( 'Settings', 'gpt-slider' ),
			'manage_options',
			'gpt-settings',
			array( $this, 'gpt_slider_settings_page' )
		);

		// Help and Upgrade Submenu
		add_submenu_page(
			'gpt-slider',
			__( 'Help and Upgrade', 'gpt-slider' ),
			__( 'Help and Upgrade', 'gpt-slider' ),
			'manage_options',
			'gpt-help-upgrade',
			array( $this, 'gpt_slider_help_upgrade_page' )
		);
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