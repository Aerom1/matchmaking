<?php
session_start();

require_once "Auth.php";
require_once "Util.php";

$auth = new Auth();
$db_handle = new DBController();
$util = new Util();

require_once "authCookieSessionValidate.php";

if ($isLoggedIn) {
    $util->redirect("..\..\index.php");
}

if (! empty($_POST["login"])) {
    $isAuthenticated = false;
    
    $username = $_POST["member_name"];
    $password = $_POST["member_password"];
    
    $user = $auth->getMemberByUsername($username);
    if (password_verify($password, $user[0]["member_password"])) {
        $isAuthenticated = true;
    }
    
    if ($isAuthenticated) {
        $_SESSION["member_id"] = $user[0]["member_id"];
        
        // Set Auth Cookies if 'Remember Me' checked
        if (! empty($_POST["remember"])) {
            setcookie("member_login", $username, $cookie_expiration_time);
            
            $random_password = $util->getToken(16);
            setcookie("random_password", $random_password, $cookie_expiration_time);
            
            $random_selector = $util->getToken(32);
            setcookie("random_selector", $random_selector, $cookie_expiration_time);
            
            $random_password_hash = password_hash($random_password, PASSWORD_DEFAULT);
            $random_selector_hash = password_hash($random_selector, PASSWORD_DEFAULT);
            
            $expiry_date = date("Y-m-d H:i:s", $cookie_expiration_time);
            
            // mark existing token as expired
            $userToken = $auth->getTokenByUsername($username, 0);
            if (! empty($userToken[0]["id"])) {
                $auth->markAsExpired($userToken[0]["id"]);
            }
            // Insert new token
            $auth->insertToken($username, $random_password_hash, $random_selector_hash, $expiry_date);
        } else {
            $util->clearAuthCookie();
        }
        $util->redirect("..\..\index.php");
    } else {
        $message = "Identification invalide";
    }
}
?>






<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

    <title>Login</title>

<!--         
    <style>
        body {
            font-family: Arial;
        }

        #frmLogin {
            padding: 20px 40px 40px 40px;
            background: #d7eeff;
            border: #acd4f1 1px solid;
            color: #333;
            border-radius: 2px;
            width: 300px;
        }

        .field-group {
            margin-top: 15px;
        }

        .input-field {
            padding: 12px 10px;
            width: 100%;
            border: #A3C3E7 1px solid;
            border-radius: 2px;
            margin-top: 5px
        }

        .form-submit-button {
            background: #3a96d6;
            border: 0;
            padding: 10px 0px;
            border-radius: 2px;
            color: #FFF;
            text-transform: uppercase;
            width: 100%;
        }

        .error-message {
            text-align: center;
            color: #FF0000;
        }
    </style> -->



</head>
<body>



<!-- BOOTSTRAP -->
<!-- SOURCE DU FORMULAIRE : -->
<!-- https://mdbootstrap.com/docs/standard/extended/registration/#! -->

    <section class="vh-100" style="background-color: #eee;">
        <div class="container h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-lg-12 col-xl-11">
                <div class="card text-black" style="border-radius: 25px;">
                <div class="card-body p-md-5">
                    <div class="row justify-content-center">
                    <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">

                        <p class="text-center h1 fw-bold mx-1 mx-md-4 my-2">Bienvenue</p>

                        <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                            <button type="button" class="btn btn-success btn-lg" onclick="ouvrirIndex()">Accès visiteur</button>
                        </div>
                        
                        <form class="card mx-1 mx-md-4" action="" method="post" style="padding:10px; background-color:aliceblue; border-color:cadetblue">

                            <p class="text-center h4 fw-bold mx-1 mx-md-4 mb-4">S'identifier</p>

                            <div class="error-message" style="text-align: center; color: #FF0000;"><?php if(isset($message)) { echo $message; } ?></div>

                            <div class="d-flex flex-row align-items-center mb-3">
                                <i class="fas fa-lock fa-lg me-3 fa-fw"></i>

                                <!-- NOM -->
                                <div class="form-outline flex-fill mx-2">
                                    <!-- <input type="text" id="formNom" class="form-control" placeholder="Nom"/> -->
                                    <input name="member_name" type="text"
                                        placeholder="Nom"    
                                        class="form-control"
                                        value="<?php if(isset($_COOKIE["member_login"])) { echo $_COOKIE["member_login"]; } ?>"                                        
                                        >
                                </div>

                                <!-- PASSWORD -->
                                <div class="form-outline flex-fill mx-2">
                                    <!-- <input type="password" id="formMdp" class="form-control" placeholder="Mot de passe"/> -->
                                    <input name="member_password" type="password"
                                        placeholder="Mot de passe"
                                        class="form-control"
                                        value="<?php if(isset($_COOKIE["member_password"])) { echo $_COOKIE["member_password"]; } ?>"
                                        >
                                </div>

                            </div>

                            <!-- <div class="form-check d-flex justify-content-center mb-4">
                                <input class="form-check-input me-2" type="checkbox" value="" id="form2Example3c" />
                                <label class="form-check-label" for="form2Example3">
                                Un jour je paierai une bière à Romain 🤗
                                </label>
                            </div> -->

                            <div class="d-flex flex-row align-items-center mb-3">
                                <div class="text-center form-outline flex-fill mx-2">
                                    <input type="checkbox" name="remember" id="remember"
                                        <?php if(isset($_COOKIE["member_login"])) { ?> checked
                                        <?php } ?> /> <label for="remember">Remember me</label>
                                </div>
                            </div>
                            
                            <div class="">
                                <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                                    <input type="submit" name="login" value="Accès Admin"
                                        class="btn btn-primary btn-lg"></span>
                                </div>
                            </div>

                            <!-- <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                                <button type="submit" name="login" class="btn btn-primary btn-lg">Accès Admin</button>
                            </div> -->
                        </form>

                    </div>
                    <div class="p-5 col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2">

                        <img src="..\..\img\pictures\img_equipe3.jpg"
                        class="img-fluid" alt="Sample image">

                    </div>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>
    </section>

    <script>
        function ouvrirIndex() {
            window.open(window.location.origin, "_self")
        }


    </script>
</body>
</html>