<?php
/**
  *  Plugin Name: MBM Cover As Featured Image
  *  Plugin URI: http://bookmanager.mooberrydreams.com/
  *  Description: Sets book cover to be the wordpress featured image for book
  *  Author: Mooberry Dreams
  *  Author URI: http://www.mooberrydreams.com/
  *  Donate Link: https://www.paypal.me/mooberrydreams/
  *	 Version: 1.1
  *	 Text Domain: mbm-cover-as-featured-image
  *	 Domain Path: languages
  *
  *	 Copyright 2019  Mooberry Dreams  (email : bookmanager@mooberrydreams.com)
  *
  *  This program is free software; you can redistribute it and/or modify
  *  it under the terms of the GNU General Public License, version 2, as 
  *  published by the Free Software Foundation.
  *
  *  This program is distributed in the hope that it will be useful,
  *  but WITHOUT ANY WARRANTY; without even the implied warranty of
  *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  *  GNU General Public License for more details.
  *
  *  You should have received a copy of the GNU General Public License
  *  along with this program; if not, write to the Free Software
  *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
  *
  */
  
 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

define('MBDBCAFI_PLUGIN_DIR', plugin_dir_path( __FILE__ )); 
	define('MBDBCAFI_PLUGIN_VERSION_KEY', 'mbdbcafi_version');
	define('MBDBCAFI_PLUGIN_VERSION', '1.1');
	
	
	
// Plugin Folder URL
if ( ! defined( 'MBDBCAFI_PLUGIN_URL' ) ) {
	define( 'MBDBCAFI_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

// Plugin Root File
if ( ! defined( 'MBDBCAFI_PLUGIN_FILE' ) ) {
	define( 'MBDBCAFI_PLUGIN_FILE', __FILE__ );
} 

require 'includes/plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/christiespeich/mbm-cover-as-featured-image/',
	__FILE__,
	'mbm-cover-as-featured-image'
);
$myUpdateChecker->getVcsApi()->enableReleaseAssets();

require_once dirname( __FILE__ ) . '/includes/helper_functions_updates.php';


// set priority to 40 to ensure it runs after MBM's plugins_loaded
add_action( 'plugins_loaded', 'mbdbcafi_plugins_loaded', 40 );
function mbdbcafi_plugins_loaded() {

//	load_plugin_textdomain( 'mbm-cover-as-featured-image', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );


}


add_filter( 'post_thumbnail_html', 'mbdbcafi_use_cover_as_post_thumbnail', 10, 2 );
function mbdbcafi_use_cover_as_post_thumbnail( $html, $post_id ) {
	// if MBM isn't installed, do nothing
	if ( !defined('MBDB_PLUGIN_VERSION')) {
		return $html;
	}
	if ( get_post_type( $post_id ) == 'mbdb_book' && empty( $html ) ) {
		$book = new Mooberry_Book_Manager_Book( $post_id );
		if ( $book->has_cover() ) {
			$html = '<img src="' . $book->cover . '" alt="" />';
		}
	}
	return $html;
}

add_filter('has_post_thumbnail', 'mbdbcafi_has_post_thumbnail', 10, 2 );
function mbdbcafi_has_post_thumbnail( $has_thumbnail, $post) {
	if ( ! defined( 'MBDB_PLUGIN_VERSION' ) ) {
		return $has_thumbnail;
	}
	if ( get_post_type( $post ) == 'mbdb_book' ) {
		if ( is_object( $post ) ) {
			$post_id = $post->ID;
		} else {
			$post_id = $post;
		}
		$book          = new Mooberry_Book_Manager_Book( $post_id );
		$has_thumbnail = $book->has_cover();
	}

	return $has_thumbnail;

}

