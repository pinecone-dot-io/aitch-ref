<?php
/*
Plugin Name:	aitch-ref!
Plugin URI:		http://wordpress.org/extend/plugins/aitch-ref/
Description:	href junk. Requires PHP >= 5.4 and Wordpress >= 3.0
Version:		0.9.8
Author:			postpostmodern, pinecone-dot-website
Author URI:		http://rack.and.pinecone.website/
*/

register_activation_hook( __FILE__, create_function("", '$ver = "5.4"; if( version_compare(phpversion(), $ver, "<") ) die( "This plugin requires PHP version $ver or greater be installed." );') );

require __DIR__.'/index.php';
