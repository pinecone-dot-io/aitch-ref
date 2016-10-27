<?php

namespace aitchref;

if( is_admin() )
	require __DIR__.'/admin.php';

require __DIR__.'/lib/class-aitchref.php';
require __DIR__.'/functions.php';
require __DIR__.'/theme.php';

AitchRef::setup();

