<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Setup Theme
include_once( get_stylesheet_directory() . '/lib/theme-defaults.php' );

//* Subpage Header Code
require_once('subpage-header.php');

//* Set Localization (do not remove)
load_child_theme_textdomain( 'parallax', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'parallax' ) );

//* Add Image upload to WordPress Theme Customizer
add_action( 'customize_register', 'parallax_customizer' );
function parallax_customizer(){
	require_once( get_stylesheet_directory() . '/lib/customize.php' );
}

//* Include Section Image CSS
include_once( get_stylesheet_directory() . '/lib/output.php' );

global $blogurl;
$blogurl = get_stylesheet_directory_uri();

//* Enqueue scripts and styles
add_action( 'wp_enqueue_scripts', 'parallax_enqueue_scripts_styles' );
function parallax_enqueue_scripts_styles() {
	// Styles
	wp_enqueue_style( 'dashicons' );
	wp_enqueue_style( 'custom', get_stylesheet_directory_uri() . '/css/allstyles.css', array() );
	wp_enqueue_style( 'Fonts', get_stylesheet_directory_uri() . '/fonts/fonts.css', array() );
	
	//wp_enqueue_style( 'googlefonts', '//fonts.googleapis.com/css?family=PT+Sans:400,700', array() );	
	//wp_enqueue_style( 'custom-font-awesome', get_stylesheet_directory_uri() . '/css/customfontawesome.min.css', array() );

	// Scripts
	wp_enqueue_script( 'scripts-js', get_stylesheet_directory_uri() . '/js/scripts.js', array( 'jquery' ), '1.0.0' );

	// Examples & Optionals:
	//wp_enqueue_script( 'owlcarousel', get_stylesheet_directory_uri() . '/js/owl-carousel/owl.carousel.min.js', array('jquery') );
	//wp_enqueue_style( 'ptsans', 'https://fonts.googleapis.com/css?family=PT+Sans:400,700', array() );
	//wp_enqueue_style( 'ptsans-caption', 'https://fonts.googleapis.com/css?family=PT+Sans+Caption:400,700', array() );
	//wp_enqueue_style( 'owlcarouselcss', get_stylesheet_directory_uri() . '/js/owl-carousel/owl.carousel.css', array() );
	//wp_enqueue_style( 'fancyBoxcss', get_stylesheet_directory_uri() . '/js/fancy/jquery.fancybox.css', array() );
	
}

// Removes Query Strings from scripts and styles
function remove_script_version( $src ){
  if ( strpos( $src, 'uploads/bb-plugin' ) !== false || strpos( $src, 'uploads/bb-theme' ) !== false ) {
    return $src;
  }
  else {
    $parts = explode( '?ver', $src );
    return $parts[0];
  }
}
add_filter( 'script_loader_src', 'remove_script_version', 15, 1 );
add_filter( 'style_loader_src', 'remove_script_version', 15, 1 );


//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Reposition the primary navigation menu
//remove_action( 'genesis_after_header', 'genesis_do_nav' );
//add_action( 'genesis_header', 'genesis_do_nav', 12 );

// Add Search to Primary Nav
//add_filter( 'genesis_header', 'genesis_search_primary_nav_menu', 10 );
function genesis_search_primary_nav_menu( $menu ){
    locate_template( array( 'searchform-header.php' ), true );
}

//* Add support for structural wraps
add_theme_support( 'genesis-structural-wraps', array(
	'header',
	'nav',
	'subnav',
	'breadcrumb',
	'footer-widgets',
	'footer',
) );

// Add Read More Link to Excerpts
add_filter('excerpt_more', 'get_read_more_link');
add_filter( 'the_content_more_link', 'get_read_more_link' );
function get_read_more_link() {
   return '...&nbsp;<a class="readmore" href="' . get_permalink() . '">Read&nbsp;More &raquo;</a>';
}

/* Add Beaver Builder Editable Footers to the Genesis Footer hook
add_action( 'genesis_before_footer', 'global_footer', 4 );
function global_footer(){
	echo do_shortcode('[fl_builder_insert_layout slug="global-footer"]');
}
*/
//* Add support for 4-column footer widgets
add_theme_support( 'genesis-footer-widgets', 4 );

//* Customize the entry meta in the entry header (requires HTML5 theme support)
add_filter( 'genesis_post_info', 'sp_post_info_filter' );
function sp_post_info_filter($post_info) {
	$post_info = '[post_date] [post_comments] [post_edit]';
	return $post_info;
}

//* Custom Breadcrumb Hook 
function breadcrumb_hook() {
	do_action('breadcrumb_hook');
}

//* Remove breadcrumbs and reposition them
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
add_action( 'breadcrumb_hook', 'genesis_do_breadcrumbs', 12 );

// Modify Breadcrumbs Args
add_filter( 'genesis_breadcrumb_args', 'malcolm_breadcrumb_args' );
function malcolm_breadcrumb_args( $args ) {
	$args['prefix'] = '<div class="breadcrumbs"><div class="wrap">';
	$args['suffix'] = '</div></div>';
	$args['sep'] = ' <span class="bread-sep">></span> ';
	$args['heirarchial_attachments'] = true;
	$args['heirarchial_categories'] = true;
	$args['display'] = true;
	$args['labels']['prefix'] = '';
    return $args;
}

// Widget - Latest News on home page
genesis_register_sidebar( array(
	'id'			=> 'home-latest-news',
	'name'			=> __( 'Latest News on Home Page', 'thrive' ),
	'description'	=> __( 'This is latest news home page widget', 'thrive' ),
) );

// Blog Widgets
genesis_register_sidebar( array(
	'id'			=> 'blog-sidebar',
	'name'			=> __( 'Blog Widgets', 'thrive' ),
	'description'	=> __( 'This is latest news widget', 'thrive' ),
) );

// Add Header Links Widget to Header
//add_action( 'genesis_before', 'header_widget', 1 );
	function header_widget() {
	if (is_active_sidebar( 'header-links' ) ) {
 	genesis_widget_area( 'header-links', array(
		'before' => '<div class="header-links">',
		'after'  => '</div>',
	) );
}}

// Unregister unused sidebar
//unregister_sidebar( 'header-right' );

// Previous / Next Post Navigation Filter For Genesis Pagination
add_filter( 'genesis_prev_link_text', 'gt_review_prev_link_text' );
function gt_review_prev_link_text() {
        $prevlink = '&laquo;';
        return $prevlink;
}
add_filter( 'genesis_next_link_text', 'gt_review_next_link_text' );
function gt_review_next_link_text() {
        $nextlink = '&raquo;';
        return $nextlink;
}



/* Subpage Header Backgrounds - Utilizes: Featured Images & Advanced Custom Fields Repeater Fields */

// AFC Repeater Setup - NOTE: Set Image Return Value to ID
// Row Field Name:
$rows = '';
$rows = get_field('subpage_header_backgrounds', 5);
// Counts the rows and selects a random row
if (is_countable($rows) && count($rows) > 0) {
	$row_count = count($rows);
}
$i = rand(0, $row_count - 1);
// Set Image size to be returned
$image_size = 'subpage-header';
// Get Image ID from the random row
$image_id = $rows[ $i ]['background_image'];
// Use Image ID to get Image Array
$image_array = wp_get_attachment_image_src($image_id, $image_size);
// Set "Default BG" to first value of the Image Array. $image_array[0] = URL;
$default_bg = $image_array[0]; 


// Custom function for getting background images
function custom_background_image($postID = "") {
	// Variables
	global $default_bg;
	global $postID;
	global $blog_slug;
	
	$currentID = get_the_ID();
	$blogID = get_option( 'page_for_posts');
	$parentID = wp_get_post_parent_id( $currentID );

	// is_home detects if you're on the blog page- must be set in admin area
	if( is_home() ) {
		$currentID = $blogID;
	} 
	// Else if post page, set ID to BlogID.
	elseif( is_home() || is_single() || is_archive() || is_search() ) {
		$currentID = $blogID;
	}

	// Try to get custom background based on current page/post
	$currentBackground = wp_get_attachment_image_src(get_post_thumbnail_id($currentID), 'subpage-header');
	//Current page/post has no custom background loaded
	if(!$currentBackground) {
		// Find blog ID
		$blog_page = get_page_by_path($blog_slug, OBJECT, 'page');
		if ($blog_page) {
			$blogID = $blogID;
			$currentID = $blogID;
		}
		// Else if post page, set ID to BlogID.
		elseif(is_single() || is_archive()) {
			$currentID = $blogID; 
		}

		// Current page has a parent
		if($parentID) {
			// Try to get parents custom background
			$parent_background = wp_get_attachment_image_src(get_post_thumbnail_id($parentID), 'subpage-header');
			// Set parent background if it exists
			if($parent_background) {
				$background_image = $parent_background[0];
			}
			// Set default background
			else {
				$background_image = $default_bg;
			}
		}
		// NO parent or no parent background: set default bg.
		else {
			$background_image = $default_bg;
		}
	}
	// Current Page has a custom background: use that
	else {
		$background_image = $currentBackground[0];
	}
	return $background_image;
}

/* Changing the Copyright text */
function genesischild_footer_creds_text () {
	global $blogurl;
 	echo '<div class="clearboth copy-line">
 			<div class="copyright first">
 				<p><span id="copy">Copyright &copy; '. date("Y") .' - All rights reserved</span> <span class="format-pipe">&#124;</span>  
	 			<a href="/sitemap/">Site Map</a>  <span>&#124;</span>  
	 			<a href="/privacy-policy/">Privacy Policy</a>  
	 			</p>
 			</div>
 			<div class="credits">
 				<span>Site by</span>
 				<a target="_blank" href="https://thriveagency.com/">
 					<img class="svg" src="'.  $blogurl . '/images/thrive-logo.png" alt="Web Design by Thrive Internet Marketing">
 				</a>
 			</div>
 		  </div>';
}
add_filter( 'genesis_footer_creds_text', 'genesischild_footer_creds_text' );


//* Reposition the primary navigation menu
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_after_header', 'genesis_do_nav', 12 );

// Add Additional Image Sizes
add_image_size( 'genesis-post-thumbnail', 163, 108, true );
add_image_size( 'subpage-header', 1600, 400, true );
add_image_size( 'news-thumb', 260, 150, false );
add_image_size( 'news-full', 800, 300, false );
add_image_size( 'sidebar-thumb', 200, 150, false );
add_image_size( 'mailchimp', 564, 9999, false );
add_image_size( 'amp', 600, 9999, false  );


// Gravity Forms confirmation anchor on all forms
add_filter( 'gform_confirmation_anchor', '__return_true' );


// Button Shortcode
// Usage: [button url="https://www.google.com"] Button Shortcode [/button]
function button_shortcode($atts, $content = null) {
  extract( shortcode_atts( array(
	  'url' => '#',
	  'target' => '_self',
	  'onclick' => '',

  ), $atts ) 
);
return '<a target="' . $target . '" href="' . $url . '" class="button" onClick="' . $onclick . '"><span>' . do_shortcode($content) . '</span></a>';
}
add_shortcode('button', 'button_shortcode');

// Link Shortcode
// Usage: [link url=”tel:1-817-447-9194″ onClick=”onClick=”ga(‘send’, ‘event’, { eventCategory: ‘Click to Call’, eventAction: ‘Clicked Phone Number’, eventLabel: ‘Header Number’});”]
function link_shortcode($atts, $content = null) {
  extract( shortcode_atts( array(
	  'url' => '#',
	  'target' => '_self',
	  'onclick' => '',
  ), $atts ) 
);
return '<a target="' . $target . '" href="' . $url . '" onClick="' . $onclick . '">' . do_shortcode($content) . '</a>';
}
add_shortcode('link', 'link_shortcode');

//* Declare WooCommerce support
add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
    add_theme_support( 'woocommerce' );
}

// Advance Custom field for Scheme Markups will be output under wphead tag
add_action('wp_head', 'add_scripts_to_wphead');
function add_scripts_to_wphead() {
	if( get_field('custom_javascript') ):	
		echo get_field('custom_javascript', 5);
	endif;
}

// Run shortcodes in Text Widgets
add_filter('widget_text', 'do_shortcode');


//Removing unused Default Wordpress Emoji Script - Performance Enhancer
function disable_emoji_dequeue_script() {
    wp_dequeue_script( 'emoji' );
}
add_action( 'wp_print_scripts', 'disable_emoji_dequeue_script', 100 );
remove_action( 'wp_head', 'print_emoji_detection_script', 7 ); 
remove_action( 'wp_print_styles', 'print_emoji_styles' );

// Removes Emoji Scripts 
add_action('init', 'remheadlink');
function remheadlink() {
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'wp_generator');
	remove_action('wp_head', 'index_rel_link');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'feed_links', 2);
	remove_action('wp_head', 'feed_links_extra', 3);
	remove_action('wp_head', 'parent_post_rel_link', 10, 0);
	remove_action('wp_head', 'start_post_rel_link', 10, 0);
	remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
	remove_action('wp_head', 'wp_shortlink_header', 10, 0);
	remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
}

// Add "nav-primary" class to Main Menu as this gets removed when we reposition the menu inside header/widget area
add_filter( 'genesis_attr_nav-header', 'thrive_custom_nav_id' );
function thrive_custom_nav_id( $attributes ) {
 	$attributes['class'] = 'nav-primary';
 	return $attributes;
}

//****** AMP Customizations ******/

//* Enqueue "stylesheet" for AMP */
add_action('amp_init','amp_css', 11);
function amp_css() { 
	require_once('css/amp.php');
}

//* Add Featured Images to AMP content
add_action( 'pre_amp_render_post', 'amp_add_custom_actions' );
function amp_add_custom_actions() {
    add_filter( 'the_content', 'amp_add_featured_image' );
}

function amp_add_featured_image( $content ) {
    if ( has_post_thumbnail() ) {
        // Just add the raw <img /> tag; our sanitizer will take care of it later.
        $image = sprintf( '<p class="featured-image">%s</p>', get_the_post_thumbnail(get_the_ID(), 'amp') );
        $content = $image . $content;
    }
    return $content;
}

// Add Fav Icon to AMP Pages
add_action('amp_post_template_head','amp_favicon');
function amp_favicon() { ?>
	<link rel="icon" href="<?php echo get_site_icon_url(); ?>" />
<?php } 

// Add Banner below content of AMP Pages
add_action('ampforwp_after_post_content','amp_custom_banner_extension_insert_banner');
function amp_custom_banner_extension_insert_banner() { ?>
	<div class="amp-custom-banner-after-post">
		<h2>CUSTOM AMP BANNER TEXT HERE IF NEEDED</h2>
		<a class="ampforwp-comment-button" href="/contact-us/">
			CONTACT US
		</a>
	</div>
<?php } 

//Sets the number of revisions for all post types
add_filter( 'wp_revisions_to_keep', 'revisions_count', 10, 2 );
function revisions_count( $num, $post ) {
	$num = 3;
    return $num;
}

// Enable Featured Images in RSS Feed and apply Custom image size so it doesn't generate large images in emails
function featuredtoRSS($content) {
global $post;
if ( has_post_thumbnail( $post->ID ) ){
$content = '<div>' . get_the_post_thumbnail( $post->ID, 'mailchimp', array( 'style' => 'margin-bottom: 15px;' ) ) . '</div>' . $content;
}
return $content;
}
 
add_filter('the_excerpt_rss', 'featuredtoRSS');
add_filter('the_content_feed', 'featuredtoRSS');

add_filter( 'genesis_pre_get_sitemap', 'thrive_genesis_pre_get_sitemap', 10 );
/**
 * Modifies the sitemap html to include a limit to the amount of pages, categories, authors, etc, that will be displayed.
 * @return string sitemap html
 */
function thrive_genesis_pre_get_sitemap() {

	$heading = 'h2';

	$sitemap  = sprintf( '<%2$s>%1$s</%2$s>', __( 'Pages:', 'genesis' ), $heading );
	$sitemap .= sprintf( '<ul>%s</ul>', wp_list_pages( array(
		'title_li' => null,
		'echo' => false,
		'depth' => 1,
		'sort_column' => 'post_title',
	)));

	$sitemap .= sprintf( '<%2$s>%1$s</%2$s>', __( 'Categories:', 'genesis' ), $heading );
	$sitemap .= sprintf( '<ul>%s</ul>', wp_list_categories( array(
		'sort_column' => 'name',
		'title_li' => null,
		'echo' => false,
		'depth' => 1,
	)));

	$users = get_users( array(
		'number' => 10,
		'who' => 'authors',
		'has_published_posts' => true,
	));

	ob_start();
	foreach ( $users as $user ) {
		$author_url = get_author_posts_url( $user->ID );
		?>
		<li>
			<a href="<?php echo esc_url( $author_url ); ?>"><?php echo esc_html( $user->display_name ); ?></a>
		</li>
		<?php
	}
	$user_li_html = ob_get_clean();

	$sitemap .= sprintf( '<%2$s>%1$s</%2$s>', __( 'Authors:', 'genesis' ), $heading );
	$sitemap .= sprintf( '<ul>%s</ul>', $user_li_html );

	$sitemap .= sprintf( '<%2$s>%1$s</%2$s>', __( 'Monthly:', 'genesis' ), $heading );
	$sitemap .= sprintf( '<ul>%s</ul>', wp_get_archives( array(
		'type' => 'monthly',
		'echo' => false,
		'limit' => 12,
	)));

	$sitemap .= sprintf( '<%2$s>%1$s</%2$s>', __( 'Recent Posts:', 'genesis' ), $heading );
	$sitemap .= sprintf( '<ul>%s</ul>', wp_get_archives( array(
		'type' => 'postbypost',
		'limit' => 10,
		'echo' => false,
	)));


	return $sitemap;
}

function single_platform_scripts() {
	if( is_page_template( 'sp_menu_template.php' ) ) {
		wp_enqueue_script( 'single-platform-script-hacks', get_stylesheet_directory_uri() . '/js/single-platform-script-hacks.js', array( 'jquery'), '1.0' );
	}
}
add_action( 'wp_enqueue_scripts', 'single_platform_scripts' );

function thrive_open_table_scripts() {
	wp_enqueue_script( 'thrive-open-table-scripts', get_stylesheet_directory_uri() . '/js/open-table-scripts.js', array( 'jquery-ui-datepicker' ), '1.0' );
}
add_action( 'wp_enqueue_scripts', 'thrive_open_table_scripts' );

function paytronix_email_validation( $validation_result ) {
    $form = $validation_result["form"];
    $email = $_POST["input_2"];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $validation_result["is_valid"] = false;
        $form["fields"][2]["failed_validation"] = true;
        $form["fields"][2]["validation_message"] = 'Please enter a valid email address';
    }
    $validation_result["form"] = $form;
    return $validation_result;
}
add_filter( 'gform_field_validation_1', 'paytronix_email_validation', 10, 1 );

function paytronix_validate_phone( $result, $value, $form, $field ) {
    $pattern = "/^(\+44\s?7\d{3}|\(?07\d{3}\)|\(?01\d{3}\)?)\s?\d{3}\s?\d{3}$/";
    if ( $field->type == 'phone' && $field->phoneFormat != 'standard' && ! preg_match( $pattern, $value ) ) {
        $result['is_valid'] = false;
        $result['message']  = 'Please enter a valid phone number';
    }
    return $result;
}
add_filter( 'gform_field_validation_1', 'paytronix_validate_phone', 10, 4 );

function array_filter_recursive( $input ) {
	foreach ( $input as &$value ) {
		if ( is_array( $value ) ) {
			$value = array_filter_recursive( $value );
		}
	}
	return array_filter( $input );
}

function paytronix_after_submission( $entry, $form ) {
    if( isset( $_POST['input_9_1'] ) ) {
        $paytronix_api = "abcd";
        $paytronix_client_id = "abcd";
        $paytronix_secret = "abcd";
        $paytronix_merchant_id = "abcd";
        $paytronix_card_template_code = "abcd";
        $user_data = array(
            "authentication"        => "anonymous",
            "merchantId"            => abcd,
            "cardTemplateCode"      => abcd,
            "activationStoreCode"   => "corp",
            "enforceUniqueFields"   => ["email"],
            "setUserFields"         => array(
                "style"                 => "typed",
                "firstName"             => [$_POST['input_1_3']],
                "lastName"              => [$_POST['input_1_6']],
                "email"                 => [$_POST['input_2']],
                "phone"                 => [$_POST['input_3']],
                "optin"                 => true
            ),
            "setAccountFields"      => array(
				"style"                 => "typed",
				"favoriteStore" 		=> array(
					array(
						"code" 		=> "701"
					)            
				)
			)
        );
		$clean_array = array_filter_recursive( $user_data );
        $data_string = json_encode( $clean_array );
        $paytronix_curl = curl_init( $paytronix_api );

        curl_setopt_array( $paytronix_curl, array(
            CURLOPT_URL             => $paytronix_api,
            CURLOPT_USERPWD         => $paytronix_client_id . ":" . $paytronix_secret,
            CURLOPT_POST            => true,
            CURLOPT_CUSTOMREQUEST   => "POST",
            CURLOPT_POSTFIELDS      => $data_string,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_ENCODING        => "",
            CURLOPT_MAXREDIRS       => 2,
            CURLOPT_TIMEOUT         => 90,
            CURLOPT_HTTP_VERSION    => CURLOPT_HTTP_VERSION_1_1,
            CURLOPT_FRESH_CONNECT   => true,
            CURLOPT_HTTPHEADER      => array(
                'Cache-Control: no-cache',
                'Accept: application/json',
                'Content-Type: application/json'
            )
        ) );
        curl_exec( $paytronix_curl );
		
		if( curl_errno( $paytronix_curl ) ) {
			$resultStatus = curl_getinfo($paytronix_curl, CURLINFO_HTTP_CODE);
			$url = get_site_url();
			$to = "chris@thriveagency.com";
			$subject = "Something went wrong with Paytronix on site " . bloginfo( 'name' );
			$message = "Couldn\'t send request: " . curl_error( $paytronix_curl );
			$message .= var_dump( curl_exec( $paytronix_curl ) );
			$message .= "Request failed: HTTP status code: " . $resultStatus;
			$headers[] = "Content-Type: text/html; charset=UTF-8";
			$headers[] = "From: " . bloginfo( 'name' ) . " <no-reply@" . get_site_url().">";
			wp_mail( $to, $subject, $message, $headers );
			wp_safe_redirect( $url );
			exit;
		}
        curl_close( $paytronix_curl );
    }
}
add_action( 'gform_after_submission_1', 'paytronix_after_submission', 10, 2 );

/* Paytronix Reserve Network functionality */
function reserve_paytronix_email_validation( $validation_result ) {
    $form = $validation_result["form"];
    $email = $_POST["input_2"];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $validation_result["is_valid"] = false;
        $form["fields"][2]["failed_validation"] = true;
        $form["fields"][2]["validation_message"] = 'Please enter a valid email address';
    }
    $validation_result["form"] = $form;
    return $validation_result;
}
add_filter( 'gform_field_validation_8', 'reserve_paytronix_email_validation', 10, 1 );

function reserve_paytronix_validate_phone( $result, $value, $form, $field ) {
    $pattern = "/^(\+44\s?7\d{3}|\(?07\d{3}\)|\(?01\d{3}\)?)\s?\d{3}\s?\d{3}$/";
    if ( $field->type == 'phone' && $field->phoneFormat != 'standard' && ! preg_match( $pattern, $value ) ) {
        $result['is_valid'] = false;
        $result['message']  = 'Please enter a valid phone number';
    }
    return $result;
}
add_filter( 'gform_field_validation_8', 'reserve_paytronix_validate_phone', 10, 4 );

function reserve_paytronix_after_submission( $entry, $form ) {
    if( isset( $_POST['input_9_1'] ) ) {
        $paytronix_api = "abcd";
        $paytronix_client_id = "abcd";
        $paytronix_secret = "abcd";
        $paytronix_merchant_id = "abcd";
        $paytronix_card_template_code = "abcd";
        $user_data = array(
            "authentication"        => "anonymous",
            "merchantId"            => abcd,
            "cardTemplateCode"      => abcd,
            "activationStoreCode"   => "corp",
            "enforceUniqueFields"   => ["email"],
            "setUserFields"         => array(
                "style"                 => "typed",
                "firstName"             => [$_POST['input_1_3']],
                "lastName"              => [$_POST['input_1_6']],
                "email"                 => [$_POST['input_2']],
                "phone"                 => [$_POST['input_3']],
                "optin"                 => true
            ),
            "setAccountFields"      => array(
				"style"                 => "typed",
				"favoriteStore" 		=> array(
					array(
						"code" 		=> "701"
					)            
				)
			)
        );
		$clean_array = array_filter_recursive( $user_data );
        $data_string = json_encode( $clean_array );
        $paytronix_curl = curl_init( $paytronix_api );

        curl_setopt_array( $paytronix_curl, array(
            CURLOPT_URL             => $paytronix_api,
            CURLOPT_USERPWD         => $paytronix_client_id . ":" . $paytronix_secret,
            CURLOPT_POST            => true,
            CURLOPT_CUSTOMREQUEST   => "POST",
            CURLOPT_POSTFIELDS      => $data_string,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_ENCODING        => "",
            CURLOPT_MAXREDIRS       => 2,
            CURLOPT_TIMEOUT         => 90,
            CURLOPT_HTTP_VERSION    => CURLOPT_HTTP_VERSION_1_1,
            CURLOPT_FRESH_CONNECT   => true,
            CURLOPT_HTTPHEADER      => array(
                'Cache-Control: no-cache',
                'Accept: application/json',
                'Content-Type: application/json'
            )
        ) );
        curl_exec( $paytronix_curl );
		
		if( curl_errno( $paytronix_curl ) ) {
			$resultStatus = curl_getinfo($paytronix_curl, CURLINFO_HTTP_CODE);
			$url = get_site_url();
			$to = "chris@thriveagency.com";
			$subject = "Something went wrong with Paytronix on site " . bloginfo( 'name' );
			$message = "Couldn\'t send request: " . curl_error( $paytronix_curl );
			$message .= var_dump( curl_exec( $paytronix_curl ) );
			$message .= "Request failed: HTTP status code: " . $resultStatus;
			$headers[] = "Content-Type: text/html; charset=UTF-8";
			$headers[] = "From: " . bloginfo( 'name' ) . " <no-reply@" . get_site_url().">";
			wp_mail( $to, $subject, $message, $headers );
			wp_safe_redirect( $url );
			exit;
		}
        curl_close( $paytronix_curl );
    }
}
add_action( 'gform_after_submission_8', 'reserve_paytronix_after_submission', 10, 2 );

function extra_footer_scripts() { ?>
	<script type='text/javascript'>
		var oliviaChatData = oliviaChatData || [];
		var oliviaChatBaseUrl =  'https://olivia.paradox.ai';
		oliviaChatData.push(['setKey', 'abcd']);
		oliviaChatData.push(['start']);
		(function() {
						var apply = document.createElement('script');
						apply.type = 'text/javascript';
						apply.async = true;
						apply.src = 'https://dokumfe7mps0i.cloudfront.net/static/site/js/widget-client.js';
						var s = document.getElementsByTagName('script')[0];
						s.parentNode.insertBefore(apply, s);
		})();
	</script>
<?php }
add_action( 'wp_footer', 'extra_footer_scripts' );

function add_userway_script() {
    ?>
    <script src="https://cdn.userway.org/widget.js" data-account="JkV3EIEKGI"></script>
    <?php
}
add_action('wp_head', 'add_userway_script');