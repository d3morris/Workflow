<?php
/*
Grab url .. split off /yyyy/xxx/yy portion of url.
yyyy - Year
xxx - either mm - Month
or www 0+ 2 digit week number [001-052]
remove yy if week number format
yy - day of Month
*/

	include 'libHTML.php';
	include 'libConnect.php';
	include 'fnTest.php';

	$cHTML = new libHTML;
//	$libFn->parseURI();
//	$libFn->setCrumbTrail('2017');
	$cHTML->drawHead();
	$cHTML->drawBodyHeader();
	$cHTML->drawBodyNav();
	$cHTML->drawBodyMain(uriTest());
	$cHTML->drawEOD();
?>
