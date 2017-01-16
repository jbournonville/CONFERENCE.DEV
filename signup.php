<?php
require 'inc/autoloader.php';

$errors = array();

$db = App::dbConnection();
$session = Session::getInstance();

if (!empty($_POST)) {

    if(!preg_match('/^[a-zA-Z0-9]+/',$_POST['username'])){
        $errors['username'] = "Votre pseudo n'est pas valide";
    } else{
        $req = $db->query("SELECT * FROM users WHERE username = ?", [$_POST['username']]);
        if($req->fetch()){
            $errors['username'] = "Ce login est déjà enregistré";
        }
    }

    if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        $errors['email'] = "L'email n'est pas valide";
    } else{
        $req = $db->query("SELECT * FROM users WHERE email = ?", [$_POST['email']]);
        if($req->fetch()){
            $errors['email'] = "Cet email est déjà enregistré pour un autre compte";
        }
    }

    if (empty($_POST['password'])){
        $errors['password'] = "Vous devez entrer un mot de passe";
    }else{
        if ($_POST['password']!=$_POST['password_confirmation']){
            $errors['password'] = "Vous n'avez pas entré des mots de passe identiques";
        }
    }

    if (empty($errors)) {
        $session->setFlashMessage("Votre profil est créé, vous pouvez vous connecter", 'success');
        App::signUpUser($_POST['username'], $_POST['email'], $_POST['password'],$db);
        App::redirect('signin');
    }else{
        $session->setFlashMessage(implode( "<br>",$errors), 'fail');
    }

}



?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gestion de conférence</title>

    <link rel="stylesheet" href="css/main1.css">
</head>
<body>

<div class="alert-login">
    <?php
    App::showFlashMessage($session);
    ?>
</div>
<div class="login-page">
    <div class="login-panel">
        <form action="" method="post">
            <p>S'enregistrer</p>
            <input type="text" class="login-input" placeholder="Login" name="username">
            <input type="email" class="login-input email-input" placeholder="Email" name="email">
            <input type="password" class="password-input" placeholder="Mot de passe" name="password">
            <input type="password" class="password-input" placeholder="Confirmation Mot de passe"
                   name="password_confirmation">
            <input type="submit" class="button button-submit" value="Sign Up">
        </form>
        <a href="signin.php" class="body-link">J'ai déjà un compte</a>
    </div>
</div>
</body>
</html>