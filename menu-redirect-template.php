<?php
/**
 *
 * @author Trive Internet Marketing
 * @link http://thrivenetmarketing.com/
 * @package Thrive
 * @subpackage Customizations
 */

/*
Template Name: Menu Redirect Page
*/


remove_action( 'genesis_loop', 'genesis_do_loop' );

remove_action( 'genesis_after_content', 'genesis_get_sidebar' );



	//echo date("H", current_time('timestamp')); 
	$currentHour = date("H", current_time('timestamp'));

	$amMenu = get_field( "am_menu", 9978 ); 
	$pmMenu = get_field( "pm_menu", 9978 );

	//if it's after 4am but before 4pm
	if ($currentHour >= 4 && $currentHour < 16 && $amMenu ) {
    	//echo "AM menu: " . $amMenu;
    	wp_redirect( $amMenu, 303 ); exit; 
	}
	elseif($pmMenu) {
		//echo "PM Menu: " .  $pmMenu;
    	wp_redirect( $pmMenu, 303 ); exit; 
	}
	else {
		wp_redirect( home_url(), 303 ); exit; 
	}

//* Remove edit link
add_filter( 'genesis_edit_post_link' , '__return_false' );
//genesis();