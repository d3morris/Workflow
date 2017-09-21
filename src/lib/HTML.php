<?php
/**
 * @author: Duane Morris duanem027@gmail.com
 * @version 0.1
 *
 * Functions for handling HTML pages
 *
 *
 *
*/

declare(strict_types=1);
namespace Apps\Lib\HTML

class libHTML {
	private $uname='';
	private $authDateTime='';
	public $aCrumbs=array('',NULL,NULL,NULL,NULL);
	private $daysInMonth=array(31,28,31,30,31,30,31,31,30,31,30,31);

	function __construct() {

	}

	function __destruct(){

	}

	/**
	 * Function to that updates the private variables that track the
	 * information on the view called.
	 *
	 * @param some string
	 * @return NULL
	 *
	 *
	*/
	public function parseURI(string $sURI):NULL {
		$pCheck=False;
		$x=0;
		$reset='http://'.$_SERVER['HTTP_HOST'].'/wrkflow/view.php';

		preg_match("/([a-z]{1,})\.php\?(20[1-5][0-9])\/([0-9]{2,4})\/([0-9]{1,2})/", $sURI, $pMatches);

		/**
		 *Check year format.  Only accepting values upto 2050 inclusive.
		 * Check to see if year is a leap year, adjust February day total if it is.
		*/

		/**
		 * Months are 2 digit strings [01-12]
		 * Weeks are 3 digit [001-052]
		 * Days are 4 Digits [0001-0366]
		*/

		/**
		 * Week or day of month. [0-9]{2}.
		 * Check that a month was found ( aYMWD[2]>2)
		 * Check that there are 5 sundays for that month for the week format.
		 * if not 5 sundays, make it 1st wk of next month .. watch end of year ..
		*/
		for($i=0;$i<count($pMatches);$i++){
			$currentMatch=$pMatches[$i];
			switch ($i) {
				case 0:
					$pCheck=False;
					break;
				case 1:
					if ($currentMatch=="view") {
						$pCheck=True;
					} else {
						$pCheck=False;
					}
					break;
				case 2:
					if ($pCheck) {
						$yr=intval($currentMatch);
						if (($yr>2015) && ($yr<2900)) {
							$this->$aCrumbs[1]=$yr;
							if ((($yr%4==0) && ($yr%100!=0)) || (($yr%4==0) && ($yr%400==0))){
								$this->dofm[1]=29;
							}
						}
					}
					break;
				case 3: //month[0-9]{2}/week[0][0-9]{2}/day[0][0-9]{3} format
					if($pCheck){
						$x=intval($currentMatch);
						switch (strlen($currentMatch)) {
							case 2:
								if(($x>=1) && ($x<=12)){
									$this->$aCrumbs[2]=$x;
								} else {
									$pCheck=False;
								}
								break;
							case 3:
								if(($x>=1) && ($x<=52)) {
									$this->$aCrumbs[3]=$x;
								} else {
									$pCheck=False;
								}
								break;
							case 4:
								if(($x>=1) && ($x<=array_sum($this->dofm))) {
									$this->$aCrumbs[4]=$x;
								} else {
									$pCheck=False;
								}
								break;
							default:
								$pCheck=False;
								break;
						}
					break;
				case 4:
					if ($pCheck) {
						if (($this->$aCrumbs[2]>0) & (int($currentMatch)>0 )) {
							switch (strlen($currentMatch)) {
								case 1:
									$this->$aCrumbs[3] = int($currentMatch);
									break;
								case 2:
									$this->$aCrumbs[4] = int($currentMatch);
									break;
								default:
									$pCheck=False;
									break;
							}
						} else {
							$pCheck=False;
						}
					} else {
						$pCheck=False;
					}
					break;
				default:
					$pCheck=False;
					break;
			}

			if (strlen($this->$aCrumbs[0])>0) {
				break;
			}
		}

		if (!$pCheck) {
			$this->$aCrumbs[0]=$reset;
			$this->$aCrumbs[1]=NULL;
			$this->$aCrumbs[2]=NULL;
			$this->$aCrumbs[3]=NULL;
			$this->$aCrumbs[4]=NULL;
		}

		return NULL;
	}

	/**
	 * Function to write the html
	 *
	 * @param NULL
	 * @return NULL
	 *
	*/
	public function drawHead(NULL):NULL {
		printf('<html><head><link rel="stylesheet" href="style.css"/>');
		printf('<title>Calendar tracking</title></head><body>');
	}

	/**
	 * Function to write the html
	 *
	 * @param some url
	 * @return NULL
	 *
	*/
	public function drawBodyHeader(string $srvRURI):NULL{
		printf('<header>');
		printf('<div id="pgTitle">Calendar:'. $srvRURI .'</div>');
		printf('<div id="pgCrumbs">'. $this->getCrumbTrail() .'</div>');
		printf('</header>');
	}

	/**
	 * Function to write the html
	 *
	 * @param NULL
	 * @return NULL
	 *
	*/
	public function drawBodyNav(NULL):NULL{
		printf('<nav>');
		printf('<div id="nav_header">Navigation</div>');
		printf('<div id="nav_item"><a href="" title="link">Year View</a></div>');
		printf('</nav>');
	}

	/**
	 * Function to write the html
	 *
	 * @param some textdomain
	 * @return NULL
	 *
	*/
	//		printf('<div id="main_display_'.$this->getViewType().'">'.$this->getViewType());
	public function drawBodyMain(string $argsMain, string $sCheck):NULL{
		printf('<main>');
		printf('<div id="main_header">Some Title</div>');
		printf('<div>');
		printf('</div>');
		printf('<p>'.$argsMain.'</p>');
		printf('<p>http://.$sCheck./wrkflow/view.php</p>');
		printf('</main>');
	}

	/**
	 * Function to write the closing body, and html tags
	 *
	 * @param NULL
	 * @return NULL
	 *
	*/
	public function writeEOD(NULL):NULL {
		printf('<script src="app.js"/>');
		printf('</body></html>');
	}

	/**
	 * Redirects the browser
	*/
	public function setURIRedirect(string $sURI):NULL {
		ob_start();
		header('Location: '.$sURI);
		ob_end_flush();
		die();
		return NULL;
	}

	/**
	 * Function to write the html
	*/
	public function getViewType(NULL):string {
		return ($this->$pViewType);
	}

	/**
	 * Function to format the stored dates from the url as breadcrumbs
	*/
	public function getCrumbTrail(NULL):string{
		$sHttpPrefix = '<div class="breadcrumbs"><a href="http://localhost/Sites/Workflow/view.php/";''
		$sFormattedHTML ='something';

		for($x=1;x<len($this->$aCrumbs);x++){
			if ($this->$aCrumbs[$x]>0) {
				$sPrevHttp .= $this->$aCrumbs[$x] .'/';
				$sFormattedHTML .= $sHttpPrefix.$sPrevHttp.$this->$aCrumbs[$x].'">.$this->$aCrumbs[$x].</a></div>';
			}
		}
		return $sFormattedHTML;
	}
}
