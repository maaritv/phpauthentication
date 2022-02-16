<?php

//10 minutes
session_set_cookie_params([
  'lifetime' => 600,
  'secure' => true,
  'httponly' => true,
  'samesite' => 'strict'
]);

?>