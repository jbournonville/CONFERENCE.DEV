<?php
require 'inc/app.php';

if (isset($_POST['subscribe'])){
    Events::subscribeUser($db, intval($_POST['subscribe']), $auth->getId());
    App::redirect('configUserSessions', ['idEvent' => $_POST['subscribe']]);
}elseif (isset($_POST['session'])){
    App::redirect('configUserSessions', ['idEvent' => $_POST['session']]);
}

$events = Events::getAllNextEvents($db);

require 'inc/header.php';
?>

<div class="page-content">
    <h2>Réservation Conférences</h2>
</div>

<?php
foreach ($events as $event) {

    $idMainSession = Events::getMainSession($db, $event->id)
    ?>

    <div class="block-content zoom-hover">
        <h2><?= $event->event ?></h2>
        <p> Date : <?= App::dateFormat($event->eventDate) ?></p>
        <p> Durée : <?= $event->duration ?> jours</p>
        <p>Localisation : <?= $event->address ?></p>
        <hr>
        <h3>Description</h3>
        <p class="desc"><?= App::excerpt($event->description, 115) ?></p>

        <form action="" method="post">
            <div class="button-group">
                <?php

                if (!$event->bookingOpen) {
                    ?>
                    <button disabled class="button button-disabled button-medium">A venir</button>
                    <?php
                } else if (Events::isUserAlreadyBookedEvent($db, $event->id, $auth->getId())) {
                    ?>
                    <button type="submit" name="session" value="<?= $event->id ?>"
                            class="button button-std button-medium">Sessions
                    </button>
                    <?php
                } else {
                    ?>
                    <button type="submit" name="subscribe" value="<?= $event->id ?>"
                            class="button button-valid button-medium">S'inscrire
                    </button>
                    <?php
                }
                ?>
            </div>
        </form>
    </div>
    <?php
}
?>


<?php
require 'inc/footer.php';
?>
