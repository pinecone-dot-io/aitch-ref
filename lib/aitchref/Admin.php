<?php

namespace aitchref;

class Admin
{
    public function __construct()
    {
        add_action( 'admin_menu', [$this,'admin_menu'] );
        add_filter( 'plugin_action_links_aitch-ref/_plugin.php', [$this, 'admin_plugins'] );
    }

    /**
    *   show link to admin options in 'settings' sidebar
    *
    */
    public function admin_menu()
    {
        add_options_page( 'aitch ref! Settings', 'aitch ref!', 'manage_options', 'aitch-ref', __NAMESPACE__.'\options_general' );
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
}
