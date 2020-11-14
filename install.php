<?php

require('WpInstaller.php');

$wp = new WpInstaller();
echo $wp->install()
		->getResults();
