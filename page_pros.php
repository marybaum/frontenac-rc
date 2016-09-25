<?php
/*
Template Name: pros
*/


/**Remove standard loop area
remove_action('genesis_loop', 'genesis_do_loop');*/

/** Add the pros widget area */
add_action( 'genesis_loop', 'frc_pros_widget' );
function frc_pros_widget() {
	dynamic_sidebar( 'pros' );
}




genesis();