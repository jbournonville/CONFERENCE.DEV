<?php
$session = Session::getInstance();
?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="css/<?= $auth->getThemeChoice() ?>">
    </head>
<body>

<header>
    <div class="header">
        <div class="site-name">
            <a href="/index.php"><h1>Gestion <br>de <br>conference</h1></a>
        </div>

        <div class="navigation">
            <nav>
                <ul class="menu">
                    <li class="menu-item"><a href="/booking.php">Réservations</a></li>
                    <li class="menu-item"><a href="/configUser.php?userId=<?= $auth->getId() ?>">Mon Profil</a></li>
                    <?php
                    if ($auth->isAdmin()) {
                        echo "<li class='menu-item'>Administration
                                    <ul class='dropdown-menu'>
                                        <li><a href='/conferences.php'>Gestion Conférences</a></li>
                                        <li><a href='/members.php'>Gestion Membres</a></li>
                                        <li><a href='/modules.php'>Gestion des modules</a></li>
                                        <li><a href='/halls.php'>Gestion des salles</a></li>
                                        <li><a href='/stats.php'>Statistiques</a></li>
                                    </ul>
                                </li>";
                    }
                    ?>
                    <li class="menu-item"><a href="/logout.php">Se déconnecter</a></li>
                </ul>
            </nav>
        </div>
    </div>
</header>

<?php
if (Session::getInstance()->hasFlashMessage()) {
    foreach (Session::getInstance()->getFlashMessage() as $type => $message) {
        echo "<div class='alert alert-$type'>$message</div>";
    }
}
?>