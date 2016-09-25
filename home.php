<?php


add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
add_filter( 'body_class', 'minimum_add_body_class' );

		function minimum_add_body_class( $classes ) {
   			$classes[] = 'minimum';
  			return $classes;
		}
remove_action( 'genesis_header', 'genesis_header_markup_open', 5 );
remove_action( 'genesis_header', 'genesis_do_header' );
remove_action( 'genesis_header', 'genesis_header_markup_close', 15 );
remove_action( 'genesis_before_header', 'genesis_do_nav' );
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
remove_action( 'genesis_after_header', 'minimum_page_title' );
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
remove_action( 'genesis_after_post_content', 'genesis_post_meta' );
remove_action( 'genesis_loop', 'genesis_do_loop' );

add_action( 'genesis_meta', 'frc_home_genesis_meta' );

/**
 * Add widget support for homepage.
 *
 */
function frc_home_genesis_meta() {

	if ( is_active_sidebar( 'home-featured-left' )
	|| is_active_sidebar( 'home-featured-middle' )
	|| is_active_sidebar( 'home-featured-right' ) ) {

		add_action( 'genesis_after_header', 'frc_home_loop_helper' );

	}
}

/**
 * Show widget content for home featured sections.
 *
 */
function frc_home_loop_helper() {

	if ( is_active_sidebar( 'home-featured-left' ) || is_active_sidebar( 'home-featured-middle' ) || is_active_sidebar( 'home-featured-right' ) ) {

			echo '<div class="home-featured">';

			echo '<div class="home-featured-left">';
			dynamic_sidebar( 'home-featured-left' );
			echo '</div><!-- end .home-featured-left -->';

			echo '<div class="home-featured-middle">';
			dynamic_sidebar( 'home-featured-middle' );
			echo '</div><!-- end .home-featured-middle -->';

			echo '<div class="home-featured-right">';
			dynamic_sidebar( 'home-featured-right' );
			echo '</div><!-- end .home-featured-right -->';

			echo '</div><!-- end .wrap --></div><!-- end #home-featured -->';

		}
}





genesis();