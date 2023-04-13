<?php
// Plugin Header
/*
 * Plugin Name:       GPT Slider
 * Plugin URI: https: https://gptheme.com/gpt-slider
 * Description:       GPT Slider is a simple and easy to use slider plugin for WordPress. It is a fully responsive slider plugin that works on all devices. It is a lightweight slider plugin that loads fast and does not slow down your website. It is a free slider plugin that comes with a lot of features.
 * Version:           1.0.0
 * Author:            GPT Theme
 * Author URI:        https://gptheme.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       gpt-slider
 * Domain Path:       /languages
 * Requires at least: 5.0
 * Tested up to: 5.5
 * Requires PHP: 7.0
 */

namespace GptSlider;
use GpTheme\GptSlider\Admin\Menu;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Composer Autoload

require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

final class GptSlider {
	// Properties

	/**
	 * Plugin version.
	 * @var string
	 */

	public $version = '1.0.0';

	/**
	 * The single instance of the class.
	 * @var GptSlider
	 */

	protected static $instance = null;

	/**
	 * GptSlider Constructor.
	 */

	public function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init_hooks();
	}

	/**
	 * Main GptSlider Instance.
	 * Ensures only one instance of GptSlider is loaded or can be loaded.
	 * @return GptSlider - Main instance.
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
		register_activation_hook( __FILE__, array( 'GptSlider_Install', 'install' ) );
		add_action( 'init', array( $this, 'init' ), 0 );
	}

	/**
	 * Define GPT Slider Constants.
	 */

	private function define_constants() {
		$this->define( 'GPT_SLIDER_PLUGIN_FILE', __FILE__ );
		$this->define( 'GPT_SLIDER_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
		$this->define( 'GPT_SLIDER_VERSION', $this->version );
		$this->define( 'GPT_SLIDER_ABSPATH', dirname( GPT_SLIDER_PLUGIN_FILE ) . '/' );
		$this->define( 'GPT_SLIDER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		$this->define( 'GPT_SLIDER_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
		$this->define( 'GPT_SLIDER_INCLUDES', GPT_SLIDER_PLUGIN_PATH . 'includes/' );
		$this->define( 'GPT_SLIDER_TEMPLATES', GPT_SLIDER_PLUGIN_PATH . 'templates/' );
		$this->define( 'GPT_SLIDER_ASSETS', GPT_SLIDER_PLUGIN_URL . 'assets/' );
		$this->define( 'GPT_SLIDER_CSS', GPT_SLIDER_PLUGIN_URL . 'assets/css/' );
		$this->define( 'GPT_SLIDER_JS', GPT_SLIDER_PLUGIN_URL . 'assets/js/' );
		$this->define( 'GPT_SLIDER_IMAGES', GPT_SLIDER_PLUGIN_URL . 'assets/images/' );
	}

	/**
	 * Define constant if not already set.
	 *
	 * @param string $name
	 * @param string|bool $value
	 */

	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */

	public function includes() {
		if ( $this->is_request( 'admin' ) ) {
			$this->admin_includes();
		}
	}

	/**
	 * Include required admin files.
	 */

	public function admin_includes() {
		// Include admin functions.
		new Menu();
	}

	/**
	 * Init GPT Slider when WordPress Initialises.
	 */

	public function init() {
		// Before init action.
		do_action( 'before_gpt_slider_init' );

		// Set up localisation.
		$this->load_plugin_textdomain();

		// Init action.
		do_action( 'gpt_slider_init' );
	}

	/**
	 * Load Localisation files.
	 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
	 * Locales found in:
	 *      - WP_LANG_DIR/gpt-slider/gpt-slider-LOCALE.mo
	 *      - WP_LANG_DIR/plugins/gpt-slider-LOCALE.mo
	 */

	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'gpt-slider' );

		load_textdomain( 'gpt-slider', WP_LANG_DIR . '/gpt-slider/gpt-slider-' . $locale . '.mo' );
		load_plugin_textdomain( 'gpt-slider', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
	}

	/**
	 * What type of request is this?
	 * string $type ajax, frontend or admin.
	 * @return bool
	 */

	public function is_request( $type ) {
		switch ( $type ) {
			case 'admin':
				return is_admin();
			case 'ajax':
				return defined( 'DOING_AJAX' );
			case 'cron':
				return defined( 'DOING_CRON' );
			case 'frontend':
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
		}
	}
}

// Kickstart the plugin
if ( ! function_exists( 'gpt_slider' ) ) {
	function gpt_slider() {
		return GptSlider::instance();
	}
}

gpt_slider();