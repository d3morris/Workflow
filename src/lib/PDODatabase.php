<?php
/**
 * @author: Duane Morris duanem027@gmail.com
 * @version 0.1
 *
 * Database connection class for php sessions
 *
 * Personal style is camelCase variable declaration.
 *
 * This code is part of a personal project that I'm working on to
 * keep track of things I'm doing/have done.
 *
 * The goal is creating a Responsive website that allows for drag/drop of
 * events, and updates the backend accordingly.
 *
 * I am currently looking at a framework to harness to make life easier in
 * developing this project, but haven't made a decision yet.
 *
 * Planned tables include
 * users (userId, userName, userAlias, userLockoutCount, userStatus, dateCreated, dateUpdated, dateLastLogin)
 * usersconfig - configuration information based on userid
 * groups (groupid, groupname, dateCreated, dateUpdated)
 * groupMembership(groupId, userId, status, dateAdded, dateUpdated)
 * events (eventId, creatorId, eventName, eventAuthorList, eventReaderList, eventDTStart, eventDTEnd, eventCreatedDate, eventLastUpdated)
 *
*/


declare(strict_types=1);

namespace Apps\Lib\PDODatabase

class baseConnect {
    private $configFile = "..\workflow.ini";
    private $userAlias="";
    private $userId=NULL;
    private $dbType="";
    static $pdoConnection="";
    private $pdoOptions;
    private $defaultPdoOptions = [
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES   => false,
    ];


/**
 * Class constructor.
 *
 * @param optional list of pdo options to use instead of default specified
 * @return None
 *
 * Load specified configuration file, that contains information on
 * the default data connections to use for this application.
 *
 * If the program can't load the configuration file, it redirects to an error page.
 *
*/
    public function __construct($configOpt){
        try {
            if (file_exists($this->$configFile)){
                $config = parse_ini_file("..\workflow.ini", true);
                if($configOpt==NULL){
                    $this->$pdoOptions = $this->$defaultPdoOptions;
                }else {
                    //verify $config_opt are valid
                }
            }
        }catch(Exception $e) {
            echo $e->getMessage();
            // change messaging to an admin alert.
            // cause some sort of error screen to be displayed.
        }
    }


/**
 *
 * Destructor for connections
 *
*/
    public function __destruct(){
        if($this->$pdoConnection != NULL){
            $this->$pdoConnection= NULL;
        }
    }


/**
 * Connect to data store
 *
 * @param None
 * @return database connection
 *
 * This function uses the information in the config file loaded
 * when the class was initiated to create the connection to the
 * database.
 *
*/
    private function connect(NULL):NULL {
      if ($this->$pdoConnection=="") {
        try {
            $dsn = "$dbType:host=$host;dbname=$db;charset=$charset";

            $pdoConnection = new PDO($dsn, $user, $pass, $this->$pdoOptions);

        }catch (PDOException $e){
            //log error - Admin notice
            // redirect to a generic error screen...  do not display error dump
        }
        return $pdoConnection;
      }
    }


/**
 * Disconnect from database
 * remove user information and null connection
 * have
*/
    public function disconnect(NULL):boolean {
        $pdoConnection = NULL
    }


/**
 * Login to system
 * @param userName Username specified from login screen
 * @param userPass password specified from login screen
 * @return boolean True/False
 * @todo Implement a lockout system, giving a user 3 attempts to get the right password.
 *
 * This validates a username/password combination against the users
 * table.  After a successful attempt, the lastLoginDate is updated,
 * the loginAttempts would be reset to 0.
*/
    public function loginUser(string $userName,string $userPass):boolean {
        $validAttempt = False;
        $loginUpdate = 'UPDATE users SET lastLoginDate = ?,lockoutLevel = ? WHERE id = ?';
        $loginSql = 'SELECT userId, userAlias FROM users where username = ?, userpass=?';
        try {
            $loginStmt = $this->$pdoConnection->prepare($loginSql);
            $loginStmt->execute([$userName,$userPass]);
            $userVal = loginStmt->fetch();
            $this->$userId = $userVal[0];
            $this->$userAlias = $userVal[1];

            $loginUpdate = $pdoConnection->prepare($loginUpdate)->execute([date("Y-m-d H:i:s"),0,$this->$userId]);

            $validAttempt = True;
        }catch(Exception $e) {
            // figure out the exception that occured and then handle it
        }

        return $validAttempt;
    }
}


/**
* Connection class for MySql data sources
*
*/
class mySqlConn extends baseConnect {
        public function __construct(){
                $this->$dbType= "mysql";
        }

        public function __destruct(){

        }
}


/**
* Connection class for Postgres data sources
*
*/
class pgSqlConn extends baseConnect {
        public function __construct(){
                $this->$dbType= "pgsql";
        }

        public function __destruct(){

        }
}

?>
