<?php

namespace aitchref;

if( is_admin() )
	require __DIR__.'/admin.php';

require __DIR__.'/lib/class-aitchref.php';
require __DIR__.'/functions.php';
require __DIR__.'/theme.php';

AitchRef::setup();

/**
*	db interaction
*	@param bool
*	@return string | array
*/
function get_urls( $as_array = FALSE ){
	$urls = get_option( 'aitchref_urls' );
	
	// backwards compat, now storing this option as a json encoded string cuz im a maverick
	if( !is_array($urls) )
		$urls = (array) json_decode( $urls );
	
	if( !$as_array )
		$urls = implode( "\n", $urls );
	
	return $urls;
}

// MU wrappers

/**
*
*	@param
*	@return
*/
function delete_option( $key ){
	global $blog_id;
	return is_multisite() ? \delete_blog_option( $blog_id, $key ) : \delete_option( $key );
}

/**
*
*	@param
*	@return
*/
function get_option( $key ){
	global $blog_id;
	return is_multisite() ? \get_blog_option( $blog_id, $key ) : \get_option( $key );
}

/**
*
*	@param
*	@param
*	@return
*/
function update_option( $key, $val ){
	global $blog_id;
	return is_multisite() ? \update_blog_option( $blog_id, $key, $val ) : \update_option( $key, $val );
}