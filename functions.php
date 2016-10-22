<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Set Localization (do not remove)
load_child_theme_textdomain( 'frc', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'frc' ) );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', __( 'Frontenac Racquet Club', 'frc' ) );
define( 'CHILD_THEME_URL', 'http://racquetpress.com' );
define( 'CHILD_THEME_VERSION', '1.0' );

//* Add HTML5 markup structure
add_theme_support( 'html5' );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Add custom body class to pros category
add_filter( 'body_class', 'frc_body_class' );
function frc_body_class( $classes ) {
	
	if ( is_category( 'Pros' ) )
		$classes[] = 'pros';
		return $classes;
		
}

//enqueue FRC typography

add_action('wp_enqueue_scripts' , 'frc_enqueue_scripts');

function frc_enqueue_scripts() {

wp_register_style('frc-vagrounded' , get_stylesheet_directory_uri() . '/type/vagrounded.css' , array(), '2');
wp_register_style('frc-fontawesome' , get_stylesheet_directory_uri() . '/type/font-awesome.css' , array(), '2');
//wp_register_style('frc-320andup', get_stylesheet_directory_uri() . '/assets-320andup/css/320andup.css', array(), '2');

	wp_enqueue_style( 'frc-vagrounded' );
	wp_enqueue_style( 'frc-fontawesome' );
	//wp_enqueue_style( 'frc-320andup' );
}

/** Add new image sizes */
add_image_size( 'header', 1600, 9999, false );
add_image_size( 'pros2', 330, 230, TRUE );
add_image_size( 'pros', 275, 343, TRUE );
add_image_size( 'medium', 275, 343, false );
add_image_size( 'classic', 298, 999, false );
add_image_size( 'featured', 600, 999, FALSE );
add_image_size( 'classic-post', 640, 0, TRUE );
add_image_size( 'prothumbs', 125, 156, TRUE );
add_image_size( 'thumbs2', 125, 125, TRUE );
add_image_size( 'homemid', 200, 300, false );
add_image_size( 'homemid-crop', 240, 120, TRUE );
add_image_size( 'homeleft', 180, 120, TRUE );
add_image_size( 'homeleft-tall', 180, 999, false );

/** Add support for custom header */
add_theme_support( 'genesis-custom-header', array(
	'width' => 1140,
	'height' => 94
) );

//* Add support for custom background
add_theme_support( 'custom-background', array( 'wp-head-callback' => '__return_false' ) );

//* Add support for structural wraps
add_theme_support( 'genesis-structural-wraps', array(
	'header',
	'site-tagline',
	'nav',
	'subnav',
	'home-featured',
	'site-inner',
	'footer-widgets',
	'footer'
) );

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

//* Unregister layout settings
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

//* Unregister secondary sidebar 
unregister_sidebar( 'sidebar-alt' );

//* Remove site description
remove_action( 'genesis_site_description', 'genesis_seo_site_description' );

//* Reposition the primary navigation menu
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_after_header', 'genesis_do_nav', 15 );

//* Reposition the secondary navigation menu
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_footer', 'genesis_do_subnav', 7 );

//* Reduce the secondary navigation menu to one level depth
add_filter( 'wp_nav_menu_args', 'frc_secondary_menu_args' );
function frc_secondary_menu_args( $args ){

	if( 'secondary' != $args['theme_location'] )
	return $args;

	$args['depth'] = 1;
	return $args;

}

/** Customize search form input box text */
add_filter( 'genesis_search_text', 'custom_search_text' );
function custom_search_text($text) {
    return esc_attr('Search.');
}

add_filter('genesis_pre_get_option_site_layout', 'custom_home_layout');
function custom_home_layout($opt) {
    if ( is_home() )
    $opt = 'content-sidebar';
    return $opt;
}

// add categories to body classes
function fr_body_class_add_categories( $classes ) {
 
	// Get the categories that are assigned to this post
	$categories = get_the_category();
 
	// Loop over each category in the $categories array
	foreach( $categories as $current_category ) {
 
		// Add the current category's slug to the $body_classes array
		$classes[] = $current_category->slug;
 
	}
 
	// Return the $body_classes array
	return $classes;
}
add_filter( 'body_class', 'fr_body_class_add_categories' );


/**
* Show Featured Image above Post Titles regardless of Content Archives settings
*
* Scope: Posts page (index)
* @author Sridhar Katakam
* @link   http://sridharkatakam.com/display-featured-images-post-titles-posts-page-genesis/
*/
add_action( 'genesis_before_entry', 'mbf_postimg_above_title' );

function mbf_postimg_above_title() {

remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );

add_action( 'genesis_entry_header', 'mbf_postimg', 9 );
}

function mbf_postimg() {
echo '<a href="' . get_permalink() . '">' . genesis_get_image( array(  

    'size' => 'featured' ,
    'size' => 'homemid' , 
    'size' => 'classic' ,
    
    ) ) . '</a>';
}

/** Customize the post info function */
add_filter( 'genesis_post_info', 'post_info_filter' );
function post_info_filter($post_info) {
if (!is_page()) {
    $post_info = '[post_edit]';
    return $post_info;
}}

//* Modify breadcrumb arguments.
add_filter( 'genesis_breadcrumb_args', 'frc_breadcrumb_args' );
function frc_breadcrumb_args( $args ) {
    $args['home']                    = 'Home';
    $args['sep']                     = ' / ';
    $args['list_sep']                = ', '; // Genesis 1.5 and later
    $args['prefix']                  = '<div class="breadcrumb">';
    $args['suffix']                  = '</div>';
    $args['heirarchial_attachments'] = true; // Genesis 1.5 and later
    $args['heirarchial_categories']  = true; // Genesis 1.5 and later
    $args['display']                 = true;
    $args['labels']['prefix']        = 'You are here: ';
    $args['labels']['author']        = '';
    $args['labels']['category']      = ''; // Genesis 1.6 and later
    $args['labels']['tag']           = '';
    $args['labels']['date']          = '';
    $args['labels']['search']        = 'Search. ';
    $args['labels']['tax']           = '';
    $args['labels']['post_type']     = '';
    $args['labels']['404']           = 'Oops! Not here. '; // Genesis 1.5 and later
    return $args;
}

//* Hook entry-footer widget area after entry content
add_action( 'genesis_entry_footer', 'frc_after_post_widget' );
	function frc_after_post_widget() {
	if ( is_singular( 'post' ) )
		genesis_widget_area( 'after-post', array(
			'before' => '<div class="after-post widget-area">',
			'after' => '</div>',
	) );
}

/** Remove the post meta function */
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );

 add_action( 'genesis_entry_footer', 'frc_meetpros', 9 );
  function frc_meetpros() {
  if ( is_singular ( ) && in_category ( 'Pros') )
    genesis_widget_area( 'meetpros', array(
      'before' => '<div class="meetpros">',
      'after' => '</div>',
  ) );
}
 //** Modify the size of the Gravatar in the author box */
add_filter( 'genesis_author_box_gravatar_size', 'frc_author_box_gravatar_size' );
function frc_author_box_gravatar_size( $size ) {
    return '96';
}

/** Modify the speak your mind text */
add_filter( 'genesis_comment_form_args', 'custom_comment_form_args' );
function custom_comment_form_args($args) {
    $args['title_reply'] = 'Your thoughts?';
    return $args;
}

/** Customize the return to top of page text */
add_filter('genesis_footer_backtotop_text', 'custom_footer_backtotop_text');
function custom_footer_backtotop_text($backtotop) {
    $backtotop = '[footer_backtotop text="Top"]';
    return $backtotop;
}

//* Modify the size of the Gravatar in the author box
add_filter( 'genesis_author_box_gravatar_size', 'frc_author_box_gravatar' );
function frc_author_box_gravatar( $size ) {

	return 144;

}

//* Modify the size of the Gravatar in the entry comments
add_filter( 'genesis_comment_list_args', 'frc_comments_gravatar' );
function frc_comments_gravatar( $args ) {

	$args['avatar_size'] = 96;
	return $args;
}

 //Big bgd images on pro bios.
 
	/**
	 * Get featured image markup.
	 *
	 * Only when the single Post or Page has a featured image, and only when
	 * showing the first page when the Page or Post is divided into multiple
	 * pages using next page quicktag.
	 *
	 * @author Sridhar Katakam
	 * @author Gary Jones
	 * @link   http://sridharkatakam.com/display-featured-image-header-minimum-pro/
	 *
	 * @return  string|bool Image markup if image could be determined
	 */
	
	 
	function frc_get_featured_image() {
		// Uncomment the lines below if you want to limit to *just* featured
		// images, and not fallback to first-attached images.
		 if ( ! has_post_thumbnail() ) {
		 	return;
		 }
		if (!in_category('pros') ) {
			return;
		}
		

			
		// If post has no featured image, it will try to fall back to
		// first-attached image if the first conditional in this function
		// is commented out.
		
		return genesis_get_image('class=alignleft');
	}

	add_action('genesis_after_header', 'frc_featured_image', 9);
	
	/**
	 * Display Featured image after header.
	 *
	 * Only on the first page when the Page or Post is divided into multiple
	 * using next page quicktag.
	 *
	 * Scope: static Pages and single Posts.
	 *
	 * @author Sridhar Katakam
	 * @author Gary Jones
	 * @link   http://sridharkatakam.com/link-to-your-tutorial
	 */
	function frc_featured_image() {
	if( has_post_thumbnail() && ( in_category('pros')  ) && 0 === get_query_var( 'page' ) ) {

		// Get the URL of featured image
		$image = genesis_get_image( 'format=url' );

		// Get the alt text of featured image
		$thumb_id = get_post_thumbnail_id( get_the_ID() );
		$alt = get_post_meta( $thumb_id, '_wp_attachment_image_alt', true );

		// If no alt text is present for featured image, set it to Post/Page title
		if ( '' == $alt ) {
			$alt = the_title_attribute( 'echo=0' );
		}

		// Show featured image
		printf(
			'<div class="big-bgd-featured-img"><img src="%s" alt="%s" class="alignleft" /></div>',
			esc_url( $image ),
			$alt
		);
		
		//Remove small copy of featured image from the entry text.
	remove_action('genesis_entry_header', 'genesis_do_post_image', 4);
	
		}

	}
	
	
	
// Register widget areas
genesis_register_sidebar( array(
	'id'          => 'site-tagline-right',
	'name'        => __( 'Site Tagline Right', 'frc' ),
	'description' => __( 'This is the site tagline right section.', 'frc' ),
) );
genesis_register_sidebar( array(
	'id'				=> 'home-featured-left',
	'name'			=> __( 'Home Featured Left', 'frc' ),
	'description'	=> __( 'This is the featured left area on the homepage.', 'frc' ),
	'before_title'  => '<h3 class="widgettitle">',
	'after_title'   => "</h3>\n",
) );

genesis_register_sidebar( array(
	'id'				=> 'home-featured-middle',
	'name'			=> __( 'Home Featured Middle', 'frc' ),
	'description'	=> __( 'This is the featured middle area on the homepage.', 'frc' ),
	'before_title'  => '<h1 class="widgettitle">',
	'after_title'   => "</h1>\n",
) );

genesis_register_sidebar( array(
	'id'				=> 'home-featured-right',
	'name'			=> __( 'Home Featured Right', 'frc' ),
	'description'	=> __( 'This is the featured right area on the homepage.', 'frc' ),
) );

genesis_register_sidebar( array(
	'id'				=> 'pros',
	'name'			=> __( 'pros', 'frc' ),
	'description'	=> __( 'This is the pros page.', 'frc' ),
) );

genesis_register_sidebar( array(
   'id'            => 'meetpros',
   'name'          => __( 'meetpros', 'frc' ),
   'description'   => __( 'This widget area can go after the post.', 'frc' ),
) );

genesis_register_sidebar( array(
	'id'				=> 'classic',
	'name'			=> __( 'classic', 'frc' ),
	'description'	=> __( 'This is the classic page.', 'frc' ),
) );

//* Register after post widget area
genesis_register_sidebar( array(
	'id'            => 'after-post',
	'name'          => __( 'After Post', 'frc' ),
	'description'   => __( 'This is where the social icons go.', 'frc' ),
) );