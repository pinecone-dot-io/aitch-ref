<?php

class SampleTest extends WP_UnitTestCase {
	private $post = NULL;
	
	/*
	*
	*/
	public function setUp(){
		parent::setUp();
		
		$post_id = $this->factory->post->create( [
			'post_title' => 'Title',
			'post_content' => 'Post Content' 
		 ] );
		$this->post = get_post( $post_id );
	}
	
	/*
	*
	*/
	public function test_post_exists() {
		$this->assertTrue( $this->post instanceof WP_Post, '$this->post not a WP_Post' );
	}
	
	/*
	*
	*/
	public function test_settings(){
		$options = aitchref\get_urls( TRUE );
		$this->assertTrue( count($options) == 1, '$options should have 1 url '.print_r($options, TRUE) );
	}
	
	/*
	*
	*/
	public function test_post_permalink(){
		$permalink = get_permalink( $this->post->ID );
		
		$this->assertContains( 'aitch-ref.com', $permalink, "$permalink should contain aitch-ref.com" );
		$this->assertNotContains( 'example.org', $permalink, "$permalink should not contain example.org" );
	}
}

