<?php
/**
 * WPMovieLibrary Legacy functions.
 * 
 * Deal with old WordPress/WPMovieLibrary versions.
 * 
 * @since     1.3
 * 
 * @package   WPMovieLibrary
 * @author    Charlie MERLAND <charlie.merland@gmail.com>
 * @license   GPL-3.0
 * @link      http://www.caercam.org/
 * @copyright 2014 CaerCam.org
 */

if ( ! defined( 'ABSPATH' ) )
	exit;


/**
 * Simple function to check WordPress version. This is mainly
 * used for styling as WP3.8 introduced a brand new dashboard
 * look n feel.
 *
 * @since    1.0
 *
 * @return   boolean    Older/newer than WordPress 3.8?
 */
function wpmoly_modern_wp() {
	return version_compare( get_bloginfo( 'version' ), '3.8', '>=' );
}

/**
 * Simple function to check for deprecated movie metadata. Prior to version 1.3
 * metadata are stored in a unique meta field and must be converted to be used
 * in latest versions.
 *
 * @since    2.0
 *
 * @return   boolean    Deprecated meta?
 */
function wpmoly_has_deprecated_meta( $post_id = null ) {

	if ( ! is_null( $post_id ) )
		return WPMOLY_Legacy::has_deprecated_meta( $post_id );

	$deprecated = get_option( 'wpmoly_has_deprecated_meta', true );
	$deprecated = ( 'false' === $deprecated ? false : true );

	return $deprecated;
}