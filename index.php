<?php

namespace aitchref;

if (!function_exists('aitchref\version')) {
    require __DIR__.'/autoload.php';
}

call_user_func( function () {
    $aitch = new AitchRef;

    if (is_admin()) {
        new Admin($aitch);
    }
});
