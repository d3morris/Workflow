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
	public $crumbs=array('','','');
	private $daysInMonth=array(31,28,31,30,31,30,31,31,30,31,30,31);
	private $pViewType='';

	/**
	 * Function to determine which calendar information to display and the bread crumbs to set
	 *
	 * @param None
	 * @return array containing redirect URL, year,month,week,day values
	 *
	 *
	*/
	public function parseURI() {
		$pCheck=False;
		$x=0;
		$aYMWD=array('',NULL,NULL,NULL,NULL);
		$sURI=strtolower($_SERVER['REQUEST_URI']);
		$reset='http://'.$_SERVER['HTTP_HOST'].'/wrkflow/view.php';

		preg_match("/([a-z]{1,})\.php\?(20[1-5][0-9])\/([0-9]{2,4})\/([0-9]{1,2})/", $sURI, $pMatches);

		for($i=0;$i<count($pMatches);$i++){
			$currentMatch=$pMatches[$i];
			switch ($i) {
				case 0:
					$pCheck=False;
					break;
				case 1:	//page name check
					if ($currentMatch=="view") {
						$pCheck=True;
					} else {
						$pCheck=False;
					}
					break;
				case 2:
/**
 *Check year format.  Only accepting values upto 2050 inclusive.
 * Check to see if year is a leap year, adjust February day total if it is.
*/
					if ($pCheck) {
						$yr=intval($currentMatch);
						if (($yr>2015) && ($yr<2050)) {
							$aYMWD[1]=$currentMatch;
							if ((($yr%4==0) && ($yr%100!=0)) || (($yr%4==0) && ($yr%400==0))){
								$this->dofm[1]=29;
							}
						}
					}
					break;
				case 3: //month[0-9]{2}/week[0][0-9]{2}/day[0][0-9]{3} format
				/**
				 * Months are 2 digit strings [01-12]
				 * Weeks are 3 digit [001-052]
				 * Days are 4 Digits [0001-0366]
				*/
					if($pCheck){
						$x=intval($currentMatch);
						switch (strlen($currentMatch)) {
							case 2:
								if(($x>=1) && ($x<=12)){
									$aYMWD[2]=x;
								} else {
									$pCheck=False;
								}
								break;
							case 3:
								if(($x>=1) && ($x<=52)) {
									$aYMWD[3]=x;
								} else {
									$pCheck=False;
								}
								break;
							case 4:
								if(($x>=1) && ($x<=array_sum($this->dofm))) {
									$aYMWD[4]=x;
								} else {
									$pCheck=False;
								}
								break;
							default:
							// Nothing like we expected. Log and move on
							// need a log thingie
								$pCheck=False;
								break;
						}
					break;
				case 4:
/**
 * Week or day of month. [0-9]{2}.
 * Check that a month was found ( aYMWD[2]>2)
 * Check that there are 5 sundays for that month for the week format.
 * if not 5 sundays, make it 1st wk of next month .. watch end of year ..
*/
					if ($pCheck) {
						if (($aYMWD[2]>0) & (int($currentMatch)>0 )) {
							switch (strlen($currentMatch)) {
								case 1:
									$aYMWD[3] = int($currentMatch);
									break;
								case 2:
									$aYMWD[4] = int($currentMatch);
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
				// Nothing like we expected. Log and move on
				// need a log thingie
					$pCheck=False;
					break;
			}

			if (strlen($aYMWD[0])>0) {
				break;
			}
		}

		if (!$pCheck) {
			$aYMWD[0]=$reset;
			$aYMWD[1]=NULL;
			$aYMWD[2]=NULL;
			$aYMWD[3]=NULL;
		}

		return $aYMWD;
	}

	/**
	 * Function to write the html
	 *
	 * @param None
	 * @return None
	 *
	*/
	public function drawHead() {
		printf('<html><head><link rel="stylesheet" href="style.css"/>');
		printf('<title>Calendar tracking</title></head><body>');
	}

	/**
	 * Function to write the html
	 *
	 * @param None
	 * @return None
	 *
	*/
	public function drawBodyHeader(){
		$srvRURI=$_SERVER['REQUEST_URI'];
		printf('<header>');
		printf('<div id="pgTitle">Calendar:'. $srvRURI .'</div>');
		printf('<div id="pgCrumbs">'. $this->getCrumbTrail() .'</div>');
		printf('</header>');
	}

	/**
	 * Function to write the html
	 *
	 * @param None
	 * @return None
	 *
	*/
	public function drawBodyNav(){
		printf('<nav>');
		printf('<div id="nav_header">Navigation</div>');
		printf('<div id="nav_item"><a href="" title="link">Year View</a></div>');
		printf('</nav>');
	}

	/**
	 * Function to write the html
	 *
	 * @param None
	 * @return None
	 *
	*/
	public function drawBodyMain($argsMain){
		printf('<main>');
		printf('<div id="main_header">Some Title</div>');
//		printf('<div id="main_display_'.$this->getViewType().'">'.$this->getViewType());
		printf('<div>');
		testParseURI();
		printf('</div>');
		printf('<p>'.$argsMain.'</p>');
		printf('<p>http://'.$_SERVER['HTTP_HOST'].'/wrkflow/view.php</p>');
		printf('</main>');
	}

	/**
	 * Function to write the closing body, and html tags
	 *
	 * @param None
	 * @return None
	 *
	*/
	public function writeEOD(){
		printf('<script src="app.js"/>');
		printf('</body></html>');
	}

	/**
	 * Function to write the html
	*/
	public function setURIRedirect($sURI) {
		ob_start();
		header('Location: '.$sURI);
		ob_end_flush();
		die();
	}

	/**
	 * Function to write the html
	*/
	public function getViewType(){
		return ($this->$pViewType);
	}

	/**
	 * Function to write the html
	*/
	public function setViewType($pView) {
		$this->$pViewType=$pView;
	}

	/**
	 * Function to write the html
	*/
	public function setCrumbTrail($pgYear='', $pgMonth='',$pgDay=''){
		if ($pgYear==''){
			$this->crumbs[0]='';
			$this->crumbs[1]='';
			$this->crumbs[2]='';
		} else {
			$this->crumbs[0]=$pgYear;
			if ($pgMonth==''){
				$this->crumbs[1]='';
				$this->crumbs[2]='';
			} else {
				$this->crumbs[1]=$pgYear;
				$this->crumbs[2]=$pgDay;
			}
		}
	}

	/**
	 * Function to write the html
	*/
	public function getCrumbTrail(){
		$sRtn ='something';
		if($this->crumbs[0]==''){
			return 'no crumbs';
		} else {
			$sRtn='Year-';
			if($this->crumbs[1]==''){
				return $sRtn . 'no other crumbs';
			} else {
				return $sRtn . (strlen($this->crumbs[1])==2?'Month-':'Week-'). ($this->crumbs[2]==''?'no other crumbs':'Day-');
			}
		}
		return $sRtn;
	}
}
