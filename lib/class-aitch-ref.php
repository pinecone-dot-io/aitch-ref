<?php

namespace aitchref;

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