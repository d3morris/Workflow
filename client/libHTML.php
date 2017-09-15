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

namespace Apps\Lib\HTML

class libHTML {
	private $uname='';
	private $authDateTime='';
	public $crumbs = array('','','');
	private $daysInMonth =  array(31,28,31,30,31,30,31,31,30,31,30,31);
	private $pViewType='';

	/**
	 * Function to write the html
	 *
	 * @param None
	 * @return None
	 *
	*/
	public function parseURI() {
		//sanitize url.. just to be certain
		$sRtn='';
		$pCheck=False;
		$pVal1='';
		$pVal2='';
		$pVal3='';
		$pType='';
		$yr = 0;
		$sURI=strtolower($_SERVER['REQUEST_URI']);
		$reset = 'http://'.$_SERVER['HTTP_HOST'].'/wrkflow/view.php';

		preg_match("/([a-z]{1,})\.php\?(20[0-9]{2})\/([0-9]{2,4})\/([0-9]{2})/", $sURI, $pMatches);

		for($i=0;$i<count($pMatches); $i++){
			switch ($i) {
				case 0:
					$pCheck=False;
					break;
				case 1:	//page name check
					if ($pMatches == "view") {
						$pCheck=True;
					} else {
						$this->setURIRedirect($reset);
					}

					break;
				case 2:	//year format, check for leap year
					if ($pCheck) {
						$yr = intval([$pMatches[2]]);
						if (($yr>2015) && ($yr<2050)) {
							$pVal1=$pMatches[2];
							$pType='yr';
							if ((($yr%4==0) && ($yr%100!=0)) || (($yr%4==0) && ($yr%400==0))){
								$this->dofm[1] =29;
							}
						}
					}
					break;
				case 3: //month[0-9]{2}/week[0][0-9]{2}/day[0][0-9]{3} format
					if($pCheck){
						$x=intval($pMatches[3]);
						switch (strlen($pMatches[3])) {
							case 2:
								if(($x>=1) && ($x<=12)){
									$pVal2 = x;
									$pType='mo';
								} else {
									$this->setURIRedirect($reset);
								}
								break;
							case 3:
								if(($x>=1) && ($x<=52)) {
									$pVal2 = x;
									$pType='wk';
								} else {
									$this->setURIRedirect($reset);
								}
								break;
							case 4:
								if(($x>=1) && ($x<=array_sum($this->dofm))) {
									$pVal2 = x;
									$pType='dy';
								} else {
									$this->setURIRedirect($reset);
								}
								break;
							default:
								$this->setURIRedirect('http://www.google.com');
								break;
						}
					}
					break;
				case 4:	//day of month ..  [0-9]{2}, iff case 3 is month format
					if ($pCheck) {
						if(strlen($pVal2)==2) {
							if (intval($pMatches[4]) > $this->dofm[intval($pVal2)-1]) {
								$pMatches[4] = strval($this->dofm[intval($pVal2)-1]);
								$pVal3 = intval($pMatches[4]);
							} else {
								$pVal3=intval($pMatches[4]);
							}
							$pType='dy';
						} else {
							$this->setURIRedirect($reset);
						}
					}
					break;
				default:
					$this->setURIRedirect('http://www.google.com');
					break;
			}
		}

		if ($pCheck) {
			$this->setCrumbTrail($pVal1,$pVal2,$pVal3);
			$this->setViewType($pType);
		} else {
			$this->setURIRedirect($reset);
		}

		return $pType;
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
		$srvRURI = $_SERVER['REQUEST_URI'];
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
		$this->$pViewType = $pView;
	}

	/**
	 * Function to write the html
	*/
	public function setCrumbTrail($pgYear='', $pgMonth='',$pgDay=''){
		if ($pgYear==''){
			$this->crumbs[0] = '';
			$this->crumbs[1] = '';
			$this->crumbs[2] = '';
		} else {
			$this->crumbs[0] = $pgYear;
			if ($pgMonth==''){
				$this->crumbs[1]='';
				$this->crumbs[2]='';
			} else {
				$this->crumbs[1]= $pgYear;
				$this->crumbs[2]= $pgDay;
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
			$sRtn = 'Year-';
			if($this->crumbs[1]==''){
				return $sRtn . 'no other crumbs';
			} else {
				return $sRtn . (strlen($this->crumbs[1])==2?'Month-':'Week-'). ($this->crumbs[2]==''?'no other crumbs':'Day-');
			}
		}
		return $sRtn;
	}
}
