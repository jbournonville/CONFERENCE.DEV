<?php
require 'inc/app.php';

$idUser = $auth->getId();

if (isset($_POST['save'])) {

    Sessions::deleteUserSessions($db, $idUser);

    foreach ($_POST as $key => $value) {
        if (!empty($value)) {
            if (substr($key, 0, 7) == "slotId_") {
                Sessions::createUserSession($db, $idUser, $value);
            }
        }
    }
    $sess = Session::getInstance();
    $sess->setFlashMessage('Vos réservations sont enregistrées', 'success');
}

$event = Events::getById($db, $_GET['idEvent']);
$days = Events::getEventDays($db, $event->id);


require 'inc/header.php';
?>

<div class="page-content">

    <form action="" method="post">
        <div class="slot">
            <div class="fixed-top-left">
                <button class="button button-valid zoom-hover" name="save">Enregistrer</button>
            </div>

            <h2><?= $event->event ?> - Reservation des sessions</h2>
            <?php
            foreach ($days as $day) {
                $slots = Slots::getSlotsByDay($db, $day, $event->id);
                ?>
                <div class="day<?= App::dateFormat($day) ?>">
                    <hr>
                    <h3 class="centered"><?= App::dateFormat($day) ?></h3>
                    <?php
                    foreach ($slots as $slot) {

                        $selectName = "slotId_" . $slot->id;

                        ?>
                        <div class="block-content session session_<?= $selectName ?>" <?php
                        if (Slots::isMainSlot($db, $slot->id)) {
                            echo "style='display: none'";
                        }
                        ?>>
                            <h4 class="centered"><?= App::hourFormat($slot->slot) ?></h4>

                            <?php
                            if ($auth->isSpeaker() && Sessions::isSpeakerBusy($db, $idUser, $slot->id)) {
                                $session = Sessions::getSpeakerSlotSession($db, $slot->id, $idUser);
                                $module = Modules::getById($db, $session->idModule);
                                $hall = Halls::getById($db, $session->idHall);
                                ?>
                                <H3 style="text-align: center">Vous êtes conférencier</H3>
                                <p>Module : <?= $module->module ?></p>
                                <p>Description : <?= $module->description ?></p>
                                <p>Localisation : Salle <?= $hall->hall ?> | Batiment <?= $hall->building ?> </p>
                                <?php

                            } else {
                                ?>
                                <label>Session</label>
                                <select title="sessionSelection" class="sessionSelection" name="<?= $selectName ?>"
                                        onchange="updateUserSessions(this)">
                                    <option value=""></option>
                                    <?php
                                    $sessions = Sessions::getSessionsBySlots($db, $slot->id);
                                    foreach ($sessions as $session) {
                                        $module = Modules::getById($db, $session->idModule);
                                        ?>
                                        <option value="<?= $session->id ?>" <?php
                                        if(Sessions::isSessionFull($db, $session->id)){
                                            echo 'disabled';
                                        }else if (Sessions::isUserBookThisSession($db, $idUser, $session->id)) {
                                            echo 'selected';
                                        } else if (Modules::isUserAlreadyBookThisModule($db, $idUser, $module->id)) {
                                            echo 'disabled';
                                        }
                                        ?>><?= $module->module ?><?php
                                            if(Sessions::isSessionFull($db, $session->id)){
                                                echo ' - COMPLET';
                                            }
                                        ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>

                                <div class="desc">
                                    <?php
                                    $session = Sessions::getUserSlotSession($db, $slot->id, $idUser);
                                    if ($session) {
                                        $module = Modules::getById($db, $session->idModule);
                                        $hall = Halls::getById($db, $session->idHall);
                                        ?>
                                        <label for="">Description</label>
                                        <p><?= $module->description ?></p>
                                        <label for="">Salle</label>
                                        <p>Salle <?= $hall->hall ?> | Batiment <?= $hall->building ?> </p>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }
            ?>
        </div>
    </form>
</div>

<?php
require 'inc/footer.php'
?>

<script src="js/app.js"></script>



