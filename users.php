<?php
ini_set('display_errors', '0');
include('cookiesettings.php');
session_start();
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

if (isSessionAuthenticated()!=1) {
  //print_r($_SESSION);
  redirect_to("index.php");
}

$userDAO = new UserDAO();
$purifier = new SanitizationService();

$userFactory = new UserFactory();


## Kun sivua kutsutaan ensimmäisen kerran, luodaan tarvittavat 
## taulut. Näitä ei saa olla mukana tuotantokoodissa, vaan tietokanta
##  luodaan erikseen. Eli taululuonti ei ole osa sovellusta!!!

##$userDAO->createUsersTable();
##$userFixDAO->createUserFixTable();

$status_text = "";
$error_text = "";

if (isset($_POST["action"])) {
  $action = $_POST["action"];

  if ($action == "addNewUser") {
    try {
      $p_first_name = $purifier->sanitizeHtml($_POST['first_name']);
      $p_last_name = $purifier->sanitizeHtml($_POST['last_name']);
      $p_user_username = $purifier->sanitizeHtml($_POST['username']);
      $p_user_password = $purifier->sanitizeHtml($_POST['password']);
      $password_ok = checkPasswordStrength($p_user_password);
      if ($password_ok==false) {
        $error_text = "Salasanan pitää sisältää numeroita, pieniä ja suuria kirjaimia sekä erikoismerkkejä";
      } else {
        $user_ok = User::checkUser($p_first_name, $p_last_name, $p_user_username, $p_user_password);
        if (!$user_ok) {
          $error_text = "Tarkista syötekentät";
        } else {
          $user = $userFactory->createUser($p_first_name, $p_last_name, $p_user_username, $p_user_password);
          $result = $userDAO->addUser($user);
          $status_text = "Käyttäjän lisäys onnistui";
        }
      }
    } catch (Exception $e) {
      error_log($e->getMessage());
      $error_text = "Käyttäjän lisäys epäonnistui";
    }
  } else if ($action == "deleteUser") {
    try {
      //Puhdista myös hidden-parametrina saadut kentät!
      $p_id = $purifier->sanitizeHtml($_POST['id']);
      //Tarkista myös hidden-parametrina saadut kentät!
      if (is_numeric($p_id)) {
        $result = $userDAO->deleteUser($p_id);
        $status_text = "Käyttäjä poistettiin";
      }
    } catch (Exception $e) {
      $error_text = "Käyttäjän poisto epäonnistui";
    }
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
    $new_user_button = $usersComponents->getNewUserButton();
    $logout_button = $usersComponents->getLogoutButton();
    $navigation = getNavigation();

    echo $navigation;
    echo $new_user_button;
    echo $logout_button;

    ?>

    <h1 class="display-3">Käyttäjät</h1>
    <h3><?php echo "Hei! " . $_SESSION['username'] ?> </h3>
    <?php

    $users = $userDAO->getUsers();
    $usersComponents->printUsersComponent($users);
    ?>
  </div>

</body>

</html>