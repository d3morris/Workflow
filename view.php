<?php
/**
 * @author: Duane Morris duanem027@gmail.com
 * @version 0.1
 *
 * This page takes the url specified and displays
 * the appropriate view specified by the url.
 *
 * Bread crumbs are changed according to view selected
 *
 * Default view : current year - shows months for current year
 *
 * /20[1-3][0-9] : specific year
 * Displays the months in a grid pattern
 * Full screen is 4x3 grid, smaller screen
 * 3x4 or 2x6 ... need to test
 *
 * /20[1-3][0-9]/[0-1][0-9] : Month of year.
 * Displays the days in a typical calendar layout
 * Sun -> Sat as titles in top row, days of month arranged
 * below.  Any day with activity shown in a different font..
 *
 * /20[1-3][0-9]/[0-1][0-9]/[0-3][0-9] : Day of month
 * Displays that day and all the events for that day.
 *
 * /20[1-3][0-9]/[0-1][0-9]/[1-5] : week of month
 * check if month has 5 Sundays .. if not, show week 1 of following month
 *
 * /20[1-3][0-9]/[0][0-5][0-9] : Week of year
 *
 * /20[1-3][0-9]/[0][0-3][0-9][0-9] : Day of year
*/

declare(strict_types=1);
require_once __DIR__.'/vendor/autoload.php';

use Apps\Lib\HTML;
use Apps\Lib\PDODatabase;
use Apps\Lib\User;


$oHTML = new \Apps\Lib\HTML\libHTML();
$oUser = new \Apps\Lib\User\userInfo();
$oDb = new \Apps\Lib\PDODatabase\mySqlConn();
//	$libFn->parseURI(strtolower($_SERVER['REQUEST_URI']));
//	$libFn->setCrumbTrail('2017');
$oHTML->drawHead();
$oHTML->drawBodyHeader($_SERVER['REQUEST_URI'];);
$oHTML->drawBodyNav();
$oHTML->drawBodyMain(uriTest(),$_SERVER['HTTP_HOST']);
$oHTML->drawEOD();
?>
