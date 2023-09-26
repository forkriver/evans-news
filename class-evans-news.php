<?php
/**
 * Core class file.
 *
 * @since 1.0.0
 *
 * @package evans-news
 */

/**
 * Core class.
 *
 * @since 1.0.0
 */
class Evans_News {

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_filter( 'the_content', array( $this, 'time_traveler' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'css' ) );
	}

	/**
	 * Adds a note if an article is older than {n}.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $content The content.
	 * @return string          The (possibly) filtered content.
	 */
	public function time_traveler( $content ) {
		if ( is_home() || is_single() ) {
			global $post;
			$age = time() - strtotime( $post->post_date_gmt );
			if ( $age > 4 * MONTH_IN_SECONDS ) {
				$pre_content = '<p class="time-traveler">' .
				 sprintf(
					esc_html__( 'This article is %s old, and might be out of date.', 'evans-news' ),
					self::fuzzy_age( $age )
				) .
				'</p>';
				$content = $pre_content . $content;
			}
		}
		return $content;
	}

	/**
	 * Loads the CSS for the plugin.
	 *
	 * @since 1.0.0
	 */
	public function css() {
		wp_enqueue_style( 'evans-news', plugins_url( 'styles/evans-news.css', __FILE__ ) );
	}

	private static function fuzzy_age( $age ) {
		if ( $age > YEAR_IN_SECONDS ) {
			$years = absint( $age / YEAR_IN_SECONDS );
			return $years . _n( ' year', ' years', $years, 'evans-news' );
		}
		if ( $age > MONTH_IN_SECONDS ) {
			$months = absint( $age / MONTH_IN_SECONDS );
			return $months . _n( ' month', ' months', $months, 'evans-news' );
		}
	}
}

new Evans_News();
