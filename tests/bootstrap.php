<?php

$_tests_dir = getenv('WP_TESTS_DIR');
if ( !$_tests_dir )
	$_tests_dir = '/tmp/wordpress-tests-lib';

require_once $_tests_dir . '/includes/functions.php';

function _manually_load_plugin() {
	update_option( 'aitchref_urls', json_encode(['http://example.org']) );
	$_SERVER['HTTP_HOST'] = 'aitch-ref.com';
	
	require dirname( __FILE__ ) . '/../_plugin.php';
}
tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

require $_tests_dir . '/includes/bootstrap.php';

