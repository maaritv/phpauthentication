<?php
session_start();


function setBrowserUnauthenticated()
{
    session_unset();
    session_destroy();
    setCookieParams();
    session_start();
}


function isSessionAuthenticated(){
    if (isset($_SESSION['loggedin'])){
        return true;
    }
    return false;
}

function authenticate($username, $password)
{
    $userDAO = new UserDAO();
    $user = $userDAO->getUserByUserName($username);
    if ($user != null) {
        $auth_result = password_verify($password, $user->password);
        if ($auth_result == true) {
            $_SESSION['loggedin'] = true;
            $_SESSION['loggedtime'] = time();
            $_SESSION['username'] = $username;
            return $user;
        }
    }
    session_destroy();
    return null;
}

function checkPasswordStrength($password)
{
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number    = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);

    if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
        return false;
    } else {
        return true;
    }
}

function setCookieParams()
{
    session_set_cookie_params([
        'lifetime' => 600,
        'secure' => true,
        'httponly' => true,
        'samesite' => 'strict'
    ]);
}

function getAuthenticatedUsernameFromBrowser()
{
    if (isset($_SESSION['username'])) {
        return $_SESSION['username'];
    }
    return null;
}

function encrypt($cleartext, $key)
{
    $cipher = "aes-256-ctr";
    if (in_array($cipher, openssl_get_cipher_methods())) {
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = substr(md5($key), 16);
        $tag = getTag();
        $ciphertext = openssl_encrypt($cleartext, $cipher, $key, $options = 0, $iv);
        return $ciphertext;
    }
}

function decrypt($encrypted, $key)
{
    $cipher = "aes-256-ctr";
    if (in_array($cipher, openssl_get_cipher_methods())) {
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = substr(md5($key), 16);
        $tag = getTag();
        $clear_text = openssl_decrypt($encrypted, $cipher, $key, $options = 0, $iv);
        return $clear_text;
    }
}

/**
 * Käytä tätä versioita, kun käytät sovellusta PHP-sandboksissa.
 */

function redirect_to_sandbox($path)
{
    $redirect_to_str = "https://".$_SERVER['HTTP_HOST']."/".$path;
    $meta="<meta http-equiv=\"refresh\" content=\"0;url=".$redirect_to_str."\" />";
?>
    <html>
<head>
<?php echo $meta ?>
<title>Moving</title>
</head>
<body>
Moving...
</body>
</html>
<?php
    die();
}

/*
 Käytä tätä versioita, kun käytät sovellusta paikallisella XAMP-koneella.
*/

function redirect_to($path)
{
    header("Location: " . $path);
    die();
}

?>