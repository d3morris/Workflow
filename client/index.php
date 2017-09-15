<?php
	include 'libHTML.php';
	include 'libConnect.php';
	$cHTML = new libHTML;
	$pdo = new mySqlConnect();
// redirect to the view.php file instead.
	$cHTML->setURIRedirect('http://'.$_SERVER['HTTP_HOST'].'/Sites/test/www/view.php');
?>
