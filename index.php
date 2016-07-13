<?php

namespace aitchref;

if( is_admin() )
	require __DIR__.'/admin.php';

require __DIR__.'/lib/functions.php';
require __DIR__.'/wpmu.php';

/**
*
*	@param string absolute | relative
*	@return array
*/
function get_filters_options( $which = 'absolute' ){
	$urls = get_option( sprintf('aitchref_filters_%s', $which) );

	if( $urls === FALSE ){
		switch( $which ){
			case 'absolute':
				$urls = array( 'admin_url', 'bloginfo', 'bloginfo_url', 'get_permalink', 'home_url', 'login_url',
							   'option_home', 'option_siteurl', 'page_link', 'post_link',
							   'siteurl', 'site_url', 'stylesheet_uri', 
							   'template_directory_uri', 'upload_dir', 'wp_get_attachment_url',
							   // @TODO get this to work
							   'acf/helpers/get_dir' );
				break;

			case 'relative':
				$urls = array( 'content_url', 'get_pagenum_link', 'option_url', 
							   'plugins_url', 'pre_post_link', 'script_loader_src',
							   'style_loader_src', 'term_link', 'the_content',
							   'url', 'wp_list_pages' );
				break;

			default:
				$urls = array();
				break;
		}
		
	}

	return $urls;
}

/**
*	db interaction
*	@param bool
*	@return string | array
*/
function get_urls_option( $as_array = FALSE ){
	$urls = get_option( 'aitchref_urls' );
	
	// backwards compat, now storing this option as a json encoded string cuz im a maverick
	if( !is_array($urls) )
		$urls = (array) json_decode( $urls );
	
	if( !$as_array )
		$urls = implode( "\n", $urls );
	
	return $urls;
}

/**
*
*/
function setup(){
	// these can return back urls starting with /
	$relative = apply_filters( 'aitch-ref-relative', get_filters_options('relative') );
	foreach( $relative as $filter )
		add_filter( $filter, __NAMESPACE__.'\AitchRef::site_url_relative' );
	
	// these need to return back with leading http://
	$absolute = apply_filters( 'aitch-ref-absolute', get_filters_options('absolute') );
	foreach( $absolute as $filter )
		add_filter( $filter, __NAMESPACE__.'\AitchRef::site_url_absolute' );
}
add_action( 'plugins_loaded', __NAMESPACE__.'\setup' );

class AitchRef{
	// these will be overwritten in setup()
	private static $baseurl = 'http://';						// is_ssl()
	private static $blog_id = 1;								// multiuser support
	
	private static $possible = array();							// a list of the possible base urls that 
																// can be replaced
	/*
	*	runs once when plugin has loaded, sets up vars and adds filters/actions
	*	@return NULL
	*/
	public static function setup(){
		global $blog_id;
		self::$blog_id = $blog_id;

		// do this to get best match first
		self::$possible = array_reverse( get_urls_option(TRUE) );

		self::$baseurl = is_ssl() ? 'https://'.$_SERVER['HTTP_HOST'] : 'http://'.$_SERVER['HTTP_HOST'];
	}
	
	/**
	*	add_filter callback
	*	@param mixed
	*	@return mixed
	*/
	public static function site_url_relative( $url ){
		if( is_array($url) ){
			// this is to fix an issue in 'upload_dir' filter, 
			// $url[error] needs to be a boolean but str_replace casts to string
			$url2 = str_replace( self::$possible, '', array_filter($url) );
			$url2 = array_merge( $url, $url2 );
		} else {
			$url2 = str_replace( self::$possible, '', $url );
		}
			
		return $url2;		
	}
	
	/**
	*	add_filter callback
	*	@param mixed
	*	@return mixed
	*/
	public static function site_url_absolute( $url ){
		if( is_array($url) ){
			// this is to fix a bug in 'upload_dir' filter, 
			// $url[error] needs to be a boolean but str_replace casts to string
			$url2 = str_replace( self::$possible, self::$baseurl, array_filter($url) );
			$url2 = array_merge( $url, $url2 );
		} else {
			$url2 = str_replace( self::$possible, self::$baseurl, $url );
		}
		
		return $url2;
	}
}

AitchRef::setup();



