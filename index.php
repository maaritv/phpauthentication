<?php
ini_set('display_errors', '0');

session_set_cookie_params([
  'lifetime' => 100,
  'secure' => true,
  'httponly' => true,
  'samesite' => 'strict'
]);
session_start();
ini_set('session.save_path', 'sessiondata');
//phpinfo();

## Liitä luokka mukaan kerran, jos samaa tarvitaan useassa 
## modulissa, kuten yleensä on asia.
require_once('./dao/UserDAO.php');
require_once('./model/User.php');
require_once('./components/UserComponents.php');
require_once('views/header.php');
require_once('utils/SanitizationService.php');
require_once('factories/UserFactory.php');
require_once('authentication/authentication.php');
require_once('authentication/secret_constants.php');

### PDO esimerkkiprojekti


my_error_logging_principles();


$userDAO = new UserDAO();
$purifier = new SanitizationService();

$userFactory = new UserFactory();


## Kun sivua kutsutaan ensimmäisen kerran, luodaan tarvittavat 
## taulut. Näitä ei saa olla mukana tuotantokoodissa, vaan tietokanta
##  luodaan erikseen. Eli taululuonti ei ole osa sovellusta!!!

//$userDAO->createUsersTable();
##$userFixDAO->createUserFixTable();

$status_text = "";
$error_text = "";

if (isset($_GET["logout"])) {
  setBrowserUnauthenticated();
} else if (isset($_POST["login"])) {
  try {
    $p_user_username = $purifier->sanitizeHtml($_POST['username']);
    $p_user_password = $purifier->sanitizeHtml($_POST['password']);
    $user = authenticate($p_user_username, $p_user_password);
    if ($user != null) {
      redirect_to("users.php");
    } else {
      $error_text = "Sisäänkirjautuminen epäonnistui";
    }
  } catch (Exception $e) {
    error_log($e->getMessage());
    $error_text = "Sisäänkirjautuminen epäonnistui";
  }
} else {
  $username = getAuthenticatedUsernameFromBrowser();
  if ($username!=null){
    redirect_to("users.php");
  }
}

?>
<html>

<head>
  <meta charset="utf-8">
  <title>Library</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link href='https://fonts.googleapis.com/css?family=Lato:300,400,700' rel='stylesheet' type='text/css'>
</head>

<body>
  <div class="container">
    <?php
    print_status_message($status_text, "ok");
    print_status_message($error_text, "error");

    $usersComponents = new UserComponents();

    ?>

    <h1 class="display-3">Kirjaudu sisään</h1>

    <?php

    $loginComponent = $usersComponents->getLoginComponent();
    echo $loginComponent;
    ?>
  </div>

</body>

</html>