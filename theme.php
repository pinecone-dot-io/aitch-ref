<?php

/**
*   helper for AitchRef to use directly in templates
*   @param string the url
*   @param bool to use absolute or not
*   @return string
*/
function aitch($url, $absolute = false)
{
    $aitch = AitchRef::instance();

    if ($absolute) {
        return $aitch->site_url_absolute( $url );
    } else {
        return $aitch->site_url_relative( $url );
    }
}
