<?php
	include 'libHTML.php';
	$cHTML = new libHTML;
// redirect to the view.php file instead.
	$cHTML->setURIRedirect('http://'.$_SERVER['HTTP_HOST'].'/Sites/test/www/view.php');
?>
