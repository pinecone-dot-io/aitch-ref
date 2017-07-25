<?php

namespace aitchref;

/**
*   get and set messages
*   @param string
*   @return array
*/
function message($string = null)
{
    static $messages = null ;
    
    if (is_null($messages)) {
        $messages = array();
    }
        
    if (!is_null($string)) {
        $messages[] = $string;
    }
        
    return $messages;
}

/**
*   callback for add_options_page() to render options page in admin
*   @return
*/
function options_general()
{
    if (isset($_POST['_wpnonce']) && wp_verify_nonce( $_POST['_wpnonce'], 'aitch-ref-admin' )) {
        update_options( $_POST['aitchref'] );
    }

    wp_enqueue_style( 'aitch-ref', plugins_url( 'public/admin/options-general.css', __FILE__ ),
                       array(), '' );

    $vars = array(
        'filters_absolute' => implode( ', ', get_filters_options('absolute') ),
        'filters_relative' => implode( ', ', get_filters_options('relative') ),
        'messages' => implode( "\n", message() ),
        'urls' => get_urls_option()
    );
    
    render( 'admin/options-general', $vars );
}

/**
*   render a page into wherever
*   (only used in admin screen)
*   @param string
*   @param object|array
*   @return
*/
function render($filename, $vars = array())
{
    $template = __DIR__.'/views/'.$filename.'.php';
    if (file_exists($template)) {
        extract( (array) $vars, EXTR_SKIP );
        include $template;
    }
}

/**
*
*/
function update_filters($str, $which = 'absolute')
{
    $option_name = sprintf( 'aitchref_filters_%s', $which );
    $value = explode( ',', $str );
    $value = array_map( 'trim', $value );
    sort( $value );

    update_option( $option_name, json_encode($value) );
}

/**
*
*   @param array
*   @return
*/
function update_options($post_data)
{
    update_filters( $post_data['filters_absolute'], 'absolute' );
    update_filters( $post_data['filters_relative'], 'relative' );

    update_urls( $post_data['urls'] );
}
    
/**
*   takes user input of urls, splits by space or new line, saves to array
*   @param string
*   @return
*/
function update_urls($str)
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
    
    $urls = json_encode( $urls );
    update_option( 'aitchref_urls', $urls );
    
    message( '<div class="updated fade"><p>aitch-ref! updated</p></div>' );
}
