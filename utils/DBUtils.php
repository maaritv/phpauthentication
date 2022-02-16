<?php


class DBUtils {

function connectToDatabase(){
  $myPDO = new PDO('sqlite:userdatabasefile');
  return $myPDO;
} 



}

?>