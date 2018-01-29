<?php

namespace aitchref;

/**
*   render a page into wherever
*   (only used in admin screen)
*   @param string
*   @param object|array
*   @return
*/
function render($filename, $vars = [])
{
    $template = __DIR__.'/views/'.$filename.'.php';
    if (file_exists($template)) {
        extract( (array) $vars, EXTR_SKIP );
        include $template;
    }
}

/**
*
*   @return string
*/
function version()
{
    $data = get_plugin_data( __DIR__.'/_plugin.php' );
    return $data['Version'];
}
