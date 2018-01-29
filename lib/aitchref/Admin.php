<?php

namespace aitchref;

class Admin
{

    protected $aitch = null;

    public function __construct(AitchRef &$aitch)
    {
        add_action( 'admin_menu', [$this,'admin_menu'] );
        add_filter( 'plugin_action_links_aitch-ref/_plugin.php', [$this, 'admin_plugins'] );

        $this->aitch = $aitch;
    }

    /**
    *
    *   @param string html
    *   @return string html
    */
    public function admin_footer_text($original = '')
    {
        return render( 'admin/options-general_footer', [
            'version' => version()
        ] );
    }

    /**
    *   show link to admin options in 'settings' sidebar
    *
    */
    public function admin_menu()
    {
        add_options_page(
            'aitch ref! Settings',
            'aitch ref!',
            'manage_options',
            'aitch-ref',
            [$this, 'options_general']
        );

        add_settings_section(
            'aitch_ref_settings_section',
            '',    // subhead
            [$this,'description'],
            'aitch_ref_settings'
        );

        add_settings_field(
            'aitch_ref_settings-urls',
            'Site URLs',
            [$this, 'render_urls'],
            'aitch_ref_settings',
            'aitch_ref_settings_section'
        );

        add_settings_field(
            'aitch_ref_settings-absolute',
            'Absolute',
            [$this, 'render_filters_absolute'],
            'aitch_ref_settings',
            'aitch_ref_settings_section'
        );

        add_settings_field(
            'aitch_ref_settings-relative',
            'Relative',
            [$this, 'render_filters_relative'],
            'aitch_ref_settings',
            'aitch_ref_settings_section'
        );

        register_setting( 'aitch_ref_settings', 'aitch_ref_settings', [$this,'save_setting'] );
    }

    /**
    *   add 'settings' link in main plugins page
    *   attached to plugin_action_links_* action
    *   @param array
    *   @return array
    */
    public function admin_plugins($links)
    {
        $settings_link = '<a href="options-general.php?page=aitch-ref">Settings</a>';
        array_unshift( $links, $settings_link );

        return $links;
    }

    /**
    *
    *   @param array
    *   @return
    */
    public function description($args)
    {
        echo sprintf( '<pre>%s</pre>', version() );
    }

    /**
    *   callback for add_options_page() to render options page in admin
    *   @return
    */
    public function options_general()
    {
        wp_enqueue_style( 'aitch-ref', plugins_url( 'public/admin/options-general.css', dirname(__DIR__) ), [], '' );
        add_filter( 'admin_footer_text', [$this, 'admin_footer_text'] );

        render( 'admin/options-general');
    }

    /**
    *
    *   @return
    */
    public function render_urls()
    {
        $vars = [
            'urls' => implode( "\n", $this->aitch->get_setting('urls') )
        ];
        
        echo render( 'admin/options-general-urls', $vars );
    }

    /**
    *
    *   @return
    */
    public function render_filters_absolute()
    {
        $vars = [
            'filters_absolute' => implode( ', ', $this->aitch->get_setting('filters_absolute') )
        ];

        echo render( 'admin/options-general-absolute', $vars );
    }

    /**
    *
    *   @return
    */
    public function render_filters_relative()
    {
        $vars = [
            'filters_relative' => implode( ', ', $this->aitch->get_setting('filters_relative') )
        ];

        echo render( 'admin/options-general-relative', $vars );
    }

    /**
    *
    *   @param array
    *   @return array
    */
    public function save_setting($data)
    {
        $data['urls'] = $this->sanitize_urls( $data['urls'] );
        $data['filters_absolute'] = $this->sanitize_filters( $data['filters_absolute'] );
        $data['filters_relative'] = $this->sanitize_filters( $data['filters_relative'] );

        return $data;
    }

    /**
    *
    *   @param string
    *   @return array
    */
    protected function sanitize_filters($str)
    {
        $value = explode( ',', $str );
        $value = array_map( 'trim', $value );
        $value = array_unique( $value );

        sort( $value );
        return $value;
    }

    /**
    *
    *   @param string
    *   @return array
    */
    protected function sanitize_urls($str)
    {
        $urls = preg_split ("/\s+/", $str);
        $urls = array_map( 'trim', $urls );
        $urls = array_unique( $urls );
        sort( $urls );
    
        foreach ($urls as $k => $url) {
            // no trailing slash!
            if (strrpos($url, '/') == (strlen($url)-1)) {
                $urls[$k] = substr( $url, 0, -1 );
            }
        }
    
        return $urls;
    }
}
