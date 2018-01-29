<?php

namespace aitchref;

if (file_exists(__DIR__.'/vendor/autoload.php')) {
    require __DIR__.'/vendor/autoload.php';
}

call_user_func( function () {
    $aitch = AitchRef::instance();

    if (is_admin()) {
        new Admin($aitch);
    }
});
