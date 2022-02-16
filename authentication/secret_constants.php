<?php

function getPrivateKeyForUserData($user){
   return getPrivateKey($user->username);
}

function getPrivateKeyForBrowser(){
    $addition = $_SERVER['HTTP_X_FORWARDED_FOR'];
    return getPrivateKey($addition);
}

function getPrivateKey($addition){
    return "90fsu0usfjji9ybcq3r7cpqrbpqb7c9".$addition."7rycn7nf89bb90bt9pnqrc9ub4b0r9cbp";
}

function getCipher(){
    return "";
}

function getTag(){
    return "jfskfskfsj";
}

?>