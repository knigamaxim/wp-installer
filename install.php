<?php

require('classes/WpInstaller.php');

$wp = new WpInstaller();
echo $wp->install()
		->getResults();