<?php

namespace aitchref;

class AitchRef
{
    protected $settings = [];
    protected $server_url = '';
    
    protected static $instance;

    protected function __construct()
    {
        $this->settings = get_option( 'aitch_ref_settings' );
        if (empty($this->settings)) {
            $this->load_legacy_settings();
        }

        // do this to get best match first
        $this->settings['urls'] = array_reverse($this->settings['urls']);
        
        $this->server_url = defined( 'AITCH_REF_SERVER_URL' ) ? AITCH_REF_SERVER_URL : (is_ssl() ? 'https://'.$_SERVER['HTTP_HOST'] : 'http://'.$_SERVER['HTTP_HOST']);
        
        add_action( 'plugins_loaded', [$this, 'setup'] );
    }

    /**
    *
    *   @param string
    *   @return array
    */
    public function get_setting($which)
    {
        if (!isset($this->settings[$which])) {
            switch ($which) {
                case 'filters_absolute':
                    $setting = ['admin_url', 'bloginfo', 'bloginfo_url', 'content_url', 'get_permalink', 'get_the_author_user_url',
                            'home_url', 'login_url','option_home', 'option_siteurl',
                            'page_link', 'plugins_url', 'post_link',
                            'siteurl', 'site_url', 'stylesheet_uri',
                            'template_directory_uri', 'upload_dir',
                            'wp_get_attachment_url',
                            // @TODO get this to work
                            'acf/helpers/get_dir'];
                    break;

                case 'filters_relative':
                    $setting = ['get_pagenum_link', 'option_url',
                            'pre_post_link', 'script_loader_src',
                            'style_loader_src', 'term_link', 'the_content',
                            'url', 'wp_list_pages'];
                    break;

                default:
                    $setting = [];
                    break;
            }
        } else {
            $setting = $this->settings[$which];
        }

        return $setting;
    }

    /**
    *
    */
    public static function instance()
    {
        if (!self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
    *
    */
    public function load_legacy_settings()
    {
        $settings = [
            'urls' => json_decode(get_option('aitchref_urls')),
            'filters_absolute' => json_decode(get_option('aitchref_filters_absolute')),
            'filters_relative' => json_decode(get_option('aitchref_filters_relative'))
        ];

        _deprecated_file( 'aitchref_urls, aitchref_filters_absolute, aitchref_filters_relative settings', '0.9.8', 'aitch_ref_settings', 'Please re-save Aitch Ref! settings.' );

        $this->settings = $settings;
    }

    /**
    *
    */
    public function setup()
    {
        // these can return back urls starting with /
        $relative = apply_filters( 'aitch-ref-relative', $this->get_setting('filters_relative') );
        foreach ($relative as $filter) {
            add_filter( $filter, [$this, 'site_url_relative'] );
        }
    
        // these need to return back with leading http://
        $absolute = apply_filters( 'aitch-ref-absolute', $this->get_setting('filters_absolute') );
        foreach ($absolute as $filter) {
            add_filter( $filter, [$this, 'site_url_absolute'] );
        }
    }

    /**
    *   add_filter callback
    *   @param mixed
    *   @return mixed
    */
    public function site_url_relative($url)
    {
        if (is_array($url)) {
            // this is to fix an issue in 'upload_dir' filter,
            // $url[error] needs to be a boolean but str_replace casts to string
            $url2 = str_replace( $this->get_setting('urls'), '', array_filter($url) );
            $url2 = array_merge( $url, $url2 );
        } else {
            $url2 = str_replace( $this->get_setting('urls'), '', $url );
        }
        
        return $url2;
    }
    
    /**
    *   add_filter callback
    *   @param mixed
    *   @return mixed
    */
    public function site_url_absolute($url)
    {
        if (is_array($url)) {
            // this is to fix a bug in 'upload_dir' filter,
            // $url[error] needs to be a boolean but str_replace casts to string
            $url2 = str_replace( $this->get_setting('urls'), $this->server_url, array_filter($url) );
            $url2 = array_merge( $url, $url2 );
        } else {
            $url2 = str_replace( $this->get_setting('urls'), $this->server_url, $url );
        }
       
        return $url2;
    }
}
