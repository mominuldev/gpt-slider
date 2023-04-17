<?php

namespace GpTheme\GptSlider\Frontend;

class Shortcode
{

	// Properties

	/**
	 * The single instance of the class.
	 * @var Shortcode
	 */

	protected static $instance = NULL;

	/**
	 * Main Shortcode Instance.
	 * Ensures only one instance of Shortcode is loaded or can be loaded.
	 * @return Shortcode - Main instance.
	 */

	public static function instance()
	{
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Shortcode Constructor.
	 */

	public function __construct()
	{
		$this->init_hooks();
	}

	/**
	 * Hook into actions and filters.
	 * @since 1.0.0
	 */

	private function init_hooks()
	{
		add_shortcode( 'gpt_slider', array( $this, 'gpt_slider_shortcode' ) );
	}

	/**
	 * Gpt Slider Shortcode.
	 * @since 1.0.0
	 */

	public function gpt_slider_shortcode( $atts )
	{
		$atts = shortcode_atts( array(
			'id' => '',
		), $atts, 'gpt_slider' );

		$slider_id = $atts['id'];

		$slider = get_post( $slider_id );

		if ( !$slider ) {
			return;
		}

		$slider_meta = get_post_meta( $slider_id, '_gpt_slider_repeater_values', true );

		if ( !$slider_meta ) {
			return;
		}

		// Unique ID
		$unique_id = uniqid( 'gpt-slider-' );

		// get featured image url
		$featured_image_url = get_the_post_thumbnail_url( $slider_id, 'full' );


		ob_start();
		?>
        <div class="swiper-container gpt-slider" id="<?php echo esc_attr( $unique_id ); ?>">
            <div class="swiper-button-prev"></div>
            <div class="swiper-wrapper">
				<?php foreach ( $slider_meta as $key => $value ) : ?>
                    <div class="swiper-slide">
                        <div class="gpt-slider">
                            <div class="slider-image">
                                <img src="<?php echo esc_url( wp_get_attachment_url( $value['image'] ) ); ?>" alt="<?php echo $value['title']; ?>">
                            </div>
                            <!-- /.slider-image -->
                            <div class="gpt-slider__content">
                                <h2 class="slide-title"><?php echo $value['title']; ?></h2>
                                <p class="slide-description"><?php echo $value['description']; ?></p>
								<?php if ( !empty( $value['button_label'] ) ) : ?>
                                    <a href="<?php echo $value['button_url']; ?>" class="slide-button"><?php echo $value['button_label']; ?></a>
								<?php endif; ?>
                            </div>
                            <!-- /.slide-content -->
                        </div>
                        <!-- /.gpt-slider -->
                    </div>
                    <!-- /.swiper-slide -->
				<?php endforeach; ?>
            </div>
            <!-- /.swiper-wrapper -->
            <div class="swiper-pagination"></div>


            <div class="swiper-button-next"></div>
        </div>
        <!-- /.swiper-container -->
		<?php

		return ob_get_clean();

	}

}