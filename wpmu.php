<?php

namespace aitchref;

// Multi-User / Multi-Site wrappers

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