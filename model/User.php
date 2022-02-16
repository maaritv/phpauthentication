<?php

class User {

    public $id;
    public $name;
    public $username;
    public $password;



   /**
      Tarkistetaan pakolliset kentät. Siinä vaiheessa kun 
      kirjaa lisätään, sillä ei vielä ole id-kenttää.
   */

    public static function checkUser($first_name, $last_name, $username, $password){
        if ($first_name==null || $last_name==null){
            return false;
        }
        if ($password=="" || $username=""){
            return false;
        }
        return true;
    }

    
}

?>