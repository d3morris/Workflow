<?php
/**
 * @author: Duane Morris duanem027@gmail.com
 * @version 0.1
 *
 * User Class for workflow sessions
 *
*/

declare(strict_types=1);

namespace Apps\Lib\Users

class userInfo {
    private userName='';
    private userAlias='';
    private userLogin='';
    private userId='';
    private userRights='';

    public function __construct(){
    }

    public function __destruct(){
    }

    public function getUserName(){
        return($this->$userName);
    }

}

class groupInfo {
    public function __construct(){

    }

    public function __destruct(){

    }
}
?>
