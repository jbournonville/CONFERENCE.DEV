<?php
require 'inc/app.php';

if (!$auth->profilIsCompleted()) {
    Session::getInstance()->setFlashMessage('Veuillez compléter votre profile');
    App::redirect("profile");
}

$event = Events::getNextEvent($db);

require 'inc/header.php';
?>
    <div class="page-content">
        <h2>Bienvenue <?php echo $auth->getLongName() ?></h2>
        <h3>Prochaine conférence : <?= $event->event ?></h3>
        <p>Date : <?= App::dateFormat($event->eventDate) ?>, Durée : <?= $event->duration ?> jours</p>
        <p>Localisation : <?= $event->address ?></p>
        <hr>
        <h3>Votre programme</h3>

        <?php
        $sessions = Sessions::getUserSessions($db, $auth->getId());

        foreach ($sessions as $session){

            $module = Modules::getById($db, $session->idModule);
            $hall = Halls::getById($db, $session->idHall);
            $slot = Slots::getById($db, $session->idSlot);
            if ($session->type == 'auditor'){
                ?>
                    <h4>Auditeur</h4>
                <?php
            }else{
                ?>
                <h4>Conférencier</h4>
                <?php
            }
            ?>
                <p>Le <?= App::dateHourFormat($slot->slot) ?></p>
                <p>Module : <?= $module->module ?></p>
                <p>Localisation : Salle <?= $hall->hall ?> | Batiment <?= $hall->building ?></p>
            <?php
        }
        ?>

    </div>

<?php
require 'inc/footer.php';
?>