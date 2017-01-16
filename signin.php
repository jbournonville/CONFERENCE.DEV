<?php
require 'inc/autoloader.php';

$db = App::dbConnection();
$session = Session::getInstance();

if (!empty($_POST)) {
    if (!empty($_POST['username'] && $_POST['password'])) {
        $req = $db->query("SELECT * FROM users WHERE username = ? OR email = ?", [$_POST['username'], $_POST['username']]);
        $user = $req->fetch();
        if ($user && password_verify($_POST['password'], $user->password)) {
            $session->setParams('user', intval($user->id));
            $session->setParams('user', intval($user->id));
            App::redirect('index');
        } else {
            $session->setFlashMessage("Login ou mot de passe incorrecte", 'fail');
        }
    }else{
        $session->setFlashMessage("Login ou mot de passe incorrecte", 'fail');
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
    if ($session->hasFlashMessage()) {
        foreach ($session->getFlashMessage() as $type => $message) {
            echo "<div class='alert alert-$type'>$message</div>";
        }
    }
    ?>
</div>

<div class="login-page">
    <div class="login-panel">
        <form action="" method="post">
            <p>Connexion</p>
            <input type="text" class="login-input" placeholder="Login ou email" name="username">
            <input type="password" class="password-input" placeholder="Password" name="password">
            <input type="submit" class="button button-submit" value="Log In">
        </form>
        <button class="button button-std" id="singup" onclick="window.location.href='signup.php'">S'enregistrer</button>
        <br>
        <a class="body-link" target="_blank" href="https://github.com/jbournonville/CONFERENCE.DEV">GitHub Repository <i class="fa fa-github" aria-hidden="true"></i></a>
    </div>

    <div class="info">
        <button type="button" class="button button-valid" onclick="showpopup()"><i class="fa fa-info" aria-hidden="true"></i> Aide</button>
    </div>

    <div id="popup">
        <div class="popup-container">
            <p>Pour vous connecter utilisez les identifiants prédéfinis (login -> mot de passe):<br>
                -   usr -> usr  (compte utilisateur standard)<br>
                -   spk -> spk  (compte conférencier)<br>
                -   adm -> adm  (compte administrateur)</p>
            <button type="button" class="button" onclick="hidepopup()">OK<i class="fa fa-thumbs-up fa-2x" aria-hidden="true"></i></button>
        </div>
    </div>
</div>

<script>
    function showpopup(){
        document.getElementById('popup').className = "is-visible";
    }

    function hidepopup(){
        document.getElementById('popup').className = "";
    }
</script>
</body>
</html>