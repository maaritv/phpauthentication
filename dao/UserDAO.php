<?php


## Liitä luokka mukaan kerran, jos samaa tarvitaan useassa 
## modulissa, kuten yleensä on asia.

require_once('utils/DBUtils.php');
require_once('model/User.php');
require_once ('views/header.php');
require_once('authentication/authentication.php');
require_once('factories/UserFactory.php');
require_once('authentication/secret_constants.php');


my_error_logging_principles();

class UserDAO {

    
    function __construct() {
        #print "USERDAO constructor\n";
        $dbutils=new DBUtils();
        $this->userFactory = new UserFactory();
        $this->dbconnection=$dbutils->connectToDatabase();
    }

    public $dbconnection;
    public $userFactory;



function addUser($user){
    try { 
        $key=getPrivateKeyForUserData($user);
        $pwd=password_hash($user->password, PASSWORD_ARGON2I);
        $sql = 'INSERT INTO USERS (first_name, last_name, username, password) VALUES(:first_name, :last_name, :username, :password)';
        $sth = $this->dbconnection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        
        $first_name=encrypt($user->first_name, $key);
        $last_name=encrypt($user->last_name, $key);
        
        $sth->bindParam('first_name', $first_name, PDO::PARAM_STR, 200);
        $sth->bindParam('last_name', $last_name, PDO::PARAM_STR, 200);
        $sth->bindParam('username', $user->username, PDO::PARAM_STR, 20);
        $sth->bindParam('password', $pwd, PDO::PARAM_STR, 200);
        
        $result = $sth->execute();
        return $result;
    }
    catch (PDOException $e){
        error_log($e->getMessage());
        throw (new Exception("Error when adding a user!"));
    }
}


/**
   Kun kirjoihin liitetään lainauksia, pitää kirjaan liittyvät 
   lainaukset poistaa ennen kuin kirja voidaan poistaa. Muuten 
   kirjan poisto epäonnistuu lapsitietuiden vuoksi.
**/

function deleteUser($id){
    try { 
        $sql = 'DELETE FROM USERS  
        WHERE id = :id';
        $sth = $this->dbconnection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':id' => $id));
    }
    catch (PDOException $e){
        error_log($e->getMessage());
        throw (new Exception("Error when deleting a user!"));
    }
}
/**
  Return list of User -objects. You need to convert every row to 
  object using the constructor of the User-class, which converts 
  array to object.
**/

function getUsers(){
    try {
        $sql = 'SELECT * FROM USERS';  
        $sth = $this->dbconnection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute();
        $user_rows = $sth->fetchAll();
        $users = [];
        foreach ($user_rows as $user_row) {
          //echo print_r($user_row);
          array_push($users, $this->userFactory->createUserFromArray($user_row));
        }
        return $users;
    }
    catch (PDOException $exception) {
        error_log($exception->getMessage());
        throw (new Exception("Error when getting users!"));
    }
}

function getUserByUserName($username){
    try { 
        $sql = 'SELECT * FROM USERS  
        WHERE username = :username';
        $sth = $this->dbconnection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':username' => $username));
        $user_row = $sth->fetch();
        if ($user_row==null){
            //echo "user was null";
            return null;
        }
        else {
            //echo print_r($user_row);
            return $this->userFactory->createUserFromArray($user_row);
        }
    }
    catch (PDOException $e){
        error_log($e->getMessage());
        throw (new Exception("Error when getting user by id!"));
    }
}


function getUserById($id){
    try { 
        $sql = 'SELECT * FROM USERS  
        WHERE id = :id';
        $sth = $this->dbconnection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':id' => $id));
        $user_row = $sth->fetch();
        if ($user_row==null){
            //echo "user was null";
            return null;
        }
        //Kun rivejä on vain yksi, muunnetaan se kirja-objektiksi
        //ennen palautusta.
        else {
            ##echo print_r($user_row);
            return $this->userFactory->createUserFromArray($user_row);
        }
    }
    catch (PDOException $e){
        error_log($e->getMessage());
        throw (new Exception("Error when getting user by id!"));
    }
}

function createUsersTable(){
    try {
         $dbutils=new DBUtils();
         $db=$dbutils->connectToDatabase();
         $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $sql = "CREATE TABLE USERS (
             id INTEGER PRIMARY KEY AUTOINCREMENT,
             first_name VARCHAR(200) NOT NULL,
             last_name VARCHAR(200) NOT NULL,
             username VARCHAR(200) NOT NULL UNIQUE,
             password TEXT);";
        $db->exec($sql);
        
    }
    catch (Exception $exception){
        //älä liitä mukaan varsinaista virhe tekstiä $exception->getMessage()
        //koska se voi sisältää liikaa tietoa tietokannan rakenteesta 
        //joka ei kuulu loppukäyttäjälle. 
       error_log($exception->getMessage());
       throw (new Exception('Creating database failed. '.$exception->getMessage()));
    }
}
}
?>