<?php

namespace aitchref;

class AitchRef
{
    protected $settings = [];
    
    public function __construct()
    {
        $this->settings = get_option( 'aitch_ref_settings' );
    }

    /**
    *
    *   @param string
    *   @return array
    */
    public function get_setting($which)
    {
        return $this->settings[$which];
    }

    /**
    *   add_filter callback
    *   @param mixed
    *   @return mixed
    */
    public static function site_url_relative($url)
    {
        _deprecated_function( $function, $version, __NAMESPACE__.'\site_url_relative()' );

        /*
		if( is_array($url) ){
			// this is to fix an issue in 'upload_dir' filter, 
			// $url[error] needs to be a boolean but str_replace casts to string
			$url2 = str_replace( self::$possible, '', array_filter($url) );
			$url2 = array_merge( $url, $url2 );
		} else {
			$url2 = str_replace( self::$possible, '', $url );
		}
		*/
            
        return site_url_relative( $url );
    }
    
    /**
    *   add_filter callback
    *   @param mixed
    *   @return mixed
    */
    public static function site_url_absolute($url)
    {
        _deprecated_function( $function, $version, __NAMESPACE__.'\site_url_absolute()');
        /*
		if( is_array($url) ){
			// this is to fix a bug in 'upload_dir' filter, 
			// $url[error] needs to be a boolean but str_replace casts to string
			$url2 = str_replace( self::$possible, self::$baseurl, array_filter($url) );
			$url2 = array_merge( $url, $url2 );
		} else {
			$url2 = str_replace( self::$possible, self::$baseurl, $url );
		}
		*/
        
        return site_url_absolute( $url );
    }
}
