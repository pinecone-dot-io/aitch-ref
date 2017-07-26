<?php 

namespace aitchref;

class AitchRef{
	// these will be overwritten in setup()
	protected static $baseurl = 'http://';						// is_ssl()
	protected static $blog_id = 1;								// multiuser support
	
	protected static $possible = array();						// a list of the possible base urls that 
																// can be replaced
	 
	/**
	*	runs once when plugin has loaded, sets up vars and adds filters/actions
	*	@return NULL
	*/
	public static function setup(){
		global $blog_id;
		self::$blog_id = $blog_id;

		// do this to get best match first
		self::$possible = array_reverse( self::get_urls(TRUE) );

		self::$baseurl = is_ssl() ? 'https://'.$_SERVER['HTTP_HOST'] : 'http://'.$_SERVER['HTTP_HOST'];

		// these can return back urls starting with /
		$relative = array( 
			'acf/load_value', 'content_url', 'get_pagenum_link', 'option_url', 
			'plugins_url', 'pre_post_link', 'script_loader_src',
			'style_loader_src', 'term_link', 'the_content',
			'url', 'wp_list_pages' 
		);
		$relative = apply_filters( 'aitch-ref-relative', $relative );
					   
		foreach( $relative as $filter )
			add_filter( $filter, __NAMESPACE__.'\AitchRef::site_url' );
		
		// these need to return back with leading http://
		$absolute = array( 
			'acf/helpers/get_dir', 'admin_url', 'bloginfo', 'bloginfo_url', 'get_permalink', 'home_url', 'login_url',
			'option_home', 'option_siteurl', 'page_link', 'post_link',
			'siteurl', 'site_url', 'stylesheet_uri', 
			'template_directory_uri', 'upload_dir', 'wp_get_attachment_url'
		);
		$absolute = apply_filters( 'aitch-ref-absolute', $absolute );

		foreach( $absolute as $filter )
			add_filter( $filter, __NAMESPACE__.'\AitchRef::site_url_absolute' );
	}

	/**
	*	db interaction
	*	@param bool
	*	@return string | array
	*/
	public static function get_urls( $as_array = FALSE ){
		$urls = get_option( 'aitchref_urls' );
		
		// backwards compat, now storing this option as a json encoded string cuz im a maverick
		if( !is_array($urls) )
			$urls = (array) json_decode( $urls );
		
		if( !$as_array )
			$urls = implode( "\n", $urls );
		
		return $urls;
	}
	
	/**
	*	add_filter callback
	*	@param mixed
	*	@return mixed
	*/
	public static function site_url( $url ){
		if( is_array($url) ){
			return array_map( array(__NAMESPACE__.'\AitchRef', 'site_url'), $url );
		} elseif( is_string($url) ){
			$url = str_replace( self::$possible, '', $url );
		}
		
		return $url;		
	}
	
	/**
	*	add_filter callback
	*	@param mixed
	*	@return mixed
	*/
	public static function site_url_absolute( $url ){
		if( is_array($url) ){
			return array_map( array(__NAMESPACE__.'\AitchRef', 'site_url_absolute'), $url );
		} elseif( is_string($url) ){
			$url = str_replace( self::$possible, self::$baseurl, $url );
		}
		
		return $url;
	}
}