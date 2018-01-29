<?php
/*
Plugin Name:	aitch-ref!
Plugin URI:		http://wordpress.org/extend/plugins/aitch-ref/
Description:	href junk. Requires PHP >= 5.4 and Wordpress >= 3.0
Version:		0.9.9
Author:			postpostmodern, pinecone-dot-website
Author URI:		http://rack.and.pinecone.website/
*/

if (version_compare(phpversion(), '5.4', "<")) {
    add_action('admin_notices', create_function("", 'function(){
        echo "<div class=\"notice notice-success is-dismissible\">
                <p>aitch-ref! requires PHP 5.4 or greater</p>
              </div>";
    };'));
} else {
    require __DIR__.'/index.php';
}