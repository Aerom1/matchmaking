<?php 

	
session_start(); // Start the session to store variable between pages

require_once "auth/authCookieSessionValidate.php";
// SOURCE : https://phppot.com/php/secure-remember-me-for-login-using-php-session-and-cookies/ 

if (!$isLoggedIn) {
    $tableauRetour = array(
        'success' => false,
        'result' => "🔒 Vous n'êtes pas connecté",
        'id' => 0
    );
    $errmsg = json_encode($tableauRetour, JSON_UNESCAPED_UNICODE);
    // return;
    exit($errmsg);
}

?>