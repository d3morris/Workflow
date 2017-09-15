<?php

namespace App\Lib\Test

function uriTest() {
  $pRet="";
  $sURI = strtolower($_SERVER['REQUEST_URI']);
  $pregPattern = "http://[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}/([a-z]{1,}).php/(20[0-9]{2})/([0-9]{2,3})";
  preg_match("/([a-z]{1,})\.php\?(20[0-9]{2})\/([0-9]{2,3})/", $sURI, $pMatches);
  for ($i=0;$i<count($pMatches);$i++){
    $pRet .= $i.": ".$pMatches[$i]."-".strlen($pMatches[$i])."- //  ";
  }
  return ($pRet);
}

function testParseURI() {
  //sanitize url.. just to be certain
  $sRtn='';
  $pCheck=False;
  $pVal1='';
  $pVal2='';
  $pVal3='';
  $pViewType='';
  $yr = 0;
  $sURI=strtolower($_SERVER['REQUEST_URI']);
  $reset = 'http://'.$_SERVER['HTTP_HOST'].'/wrkflow/view.php';

  preg_match("/([a-z]{1,})\.php\?(20[0-9]{2})\/([0-9]{2,4})\/([0-9]{2})/", $sURI, $pMatches);

  printf('<p>Parsing URL:'. $sURI.'</p>');
  printf(<p>count($pMatches).' regex matches</p>');

  for($i=0;$i<count($pMatches); $i++){
    switch ($i) {
      case 0:
        $pCheck=False;
        break;
      case 1:	//page name check
        if ($pMatches == "view") {
          $pCheck=True;
        } else {
          //redirect to view
//					$this->setURIRedirect($reset);
        }

        break;
      case 2:	//year format, check for leap year
        if ($pCheck) {
          $yr = intval([$pMatches[2]]);
          if (($yr>2015) && ($yr<2050)) {
            $pVal1=$pMatches[2];
            $pViewType='yr';
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
                $pViewType='mo';
              } else {
    //						$this->setURIRedirect($reset);
              }
              break;
            case 3:
              if(($x>=1) && ($x<=52)) {
                $pVal2 = x;
                $pViewType='wk';
              } else {
  //							$this->setURIRedirect($reset);
              }
              break;
            case 4:
              if(($x>=1) && ($x<=array_sum($this->dofm))) {
                $pVal2 = x;
                $pViewType='dy';
              } else {
    //						$this->setURIRedirect($reset);
              }
              break;
            default:
              //redirect to view
  //						$this->setURIRedirect('http://www.google.com');
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
            $pViewType='dy';
          } else {
//						$this->setURIRedirect($reset);
          }
        }
        break;

      default:
      //redirect to view
//				$this->setURIRedirect('http://www.google.com');
        break;
    }
    printf('<p>'.$i.': '. $pMatches[i].' : '.$pCheck.' : '.$pVal1.','.$pVal2.','.$pVal3.','.$pViewType.'</p>');
  }

  if ($pCheck) {
//    $this->setCrumbTrail($pVal1,$pVal2,$pVal3);
  } else {
//    $this->setURIRedirect($reset);
  }

  return $pViewType;
}

?>
