<?php


class UserFactory {

     public function createUserFromArray($array_user) {
        $user = new User();
        if (isset($array_user['id']))
            $user->id=$array_user['id'];
        if (isset($array_user['username']))
            $user->username=$array_user['username'];
        if (isset($array_user['password']))
            $user->password=$array_user['password'];
        $key=getPrivateKeyForUserData($user);
        if (isset($array_user['first_name']))    
            $user->first_name=decrypt($array_user['first_name'], $key);
        if (isset($array_user['last_name']))    
            $user->last_name=decrypt($array_user['last_name'], $key);
        return $user;
    }
      
    public function createUser($first_name, $last_name, $username, $password, $id=null) {
            $user = new User();
            $user->id = $id;
            $user->first_name = $first_name;
            $user->last_name = $last_name;
            $user->username = $username;
            $user->password= $password;
            return $user;
    }
}
