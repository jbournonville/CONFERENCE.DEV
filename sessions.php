<?php
require 'inc/app.php';

App::needAdmin($auth);

$idEvent = $_GET['eventId'];
$event = Events::getById($db, $idEvent);
$days = Events::getEventDays($db, $idEvent);
$nbSpeakers = Events::getNbSpeakers($db, $idEvent);

if (isset($_POST['modify'])) {

    $params[] = intval($_POST['idModule']);
    $params[] = intval($_POST['idHall']);
    if (isset($_POST['mainSession'])) {
        $params[] = TRUE;
    } else {
        $params[] = FALSE;
    }
    $params[] = intval($_POST['modify']);


    Sessions::updateData($db, $params);

    Sessions::deleteSessionSpeakers($db, intval($_POST['modify']));

    foreach ($_POST as $key => $value) {
        if (substr($key, 0, 9) == "idSpeaker") {
            if (!empty($value)) {
                Sessions::createSessionSpeaker($db, intval($value), intval($_POST['modify']));
            }
        }
    }

    $sess = Session::getInstance();
    $sess->setFlashMessage('Session enregistrée', 'success');

} elseif (isset($_POST['create'])) {
    $params = [
        intval($_POST['idModule']),
        intval($_POST['idHall']),
        intval($_POST['create'])
    ];

    Sessions::create($db, $params);

    $newSessionId = $db->lastInsertId();

    foreach ($_POST as $key => $value) {
        if (substr($key, 0, 9) == "idSpeaker") {
            if (!empty($value)) {
                Sessions::createSessionSpeaker($db, intval($value), $newSessionId);
            }
        }
    }

    $sess = Session::getInstance();
    $sess->setFlashMessage('Session créée', 'success');

} elseif (isset($_POST['delete'])) {

    Sessions::delete(intval($_POST['delete']), $db);
}

if (!Events::getMainSession($db, $event->id)){
    $sess = Session::getInstance();
    $sess->setFlashMessage('Attention veuillez session principale', 'fail');
}

$sessions = Sessions::getAll($db);
//var_dump($days, $sessions);
require 'inc/header.php';
?>

<div class="page-content">
    <h2>Sessions - <?= $event->event ?></h2>
</div>
<?php
foreach ($days as $day) {
    $slots = Slots::getSlotsByDay($db, $day, $event->id)
    ?>
    <div class="body-content">
        <h3><?= App::dateFormat($day) ?></h3>
        <?php
        foreach ($slots as $slot) {
            if (Slots::getSlotDay($db, $slot->id) == $day) {
                ?>
                <h4><?= App::hourFormat($slot->slot) ?></h4>
                <hr class="engraved-hr">
                <div class="hScroll idSlot_<?= $slot->id ?>">
                    <?php
                    foreach ($sessions as $session) {
                        if ($session->idSlot == $slot->id) {
                            ?>
                            <div class="block-content">
                                <form action="" method="post">
                                    <label for="idModule">Module</label>
                                    <select name="idModule" id="idModule" class="selectModule" onchange="updateModuleSessions(this)" required>
                                        <?php
                                        $modules = Modules::getAll($db);
                                        foreach ($modules as $module) {
                                            ?>
                                            <option value="<?= $module->id ?>" <?php
                                            if ($module->id == $session->idModule) {
                                                echo ' selected';
                                            } else if (Sessions::isModuleAlreadyUse($db, $module->id, $slot->id)) {
                                                echo 'disabled';
                                            }
                                            ?>><?= $module->module ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <label for="idHall">Salle</label>
                                    <select name="idHall" id="idHall" class="selectHall" onchange="updateHallSessions(this)" required>
                                        <?php
                                        $halls = Halls::getAll($db);
                                        foreach ($halls as $hall) {
                                            ?>
                                            <option value="<?= $hall->id ?>" <?php

                                            if ($hall->id == $session->idHall) {
                                                echo ' selected';
                                            } else if (Sessions::isModuleAlreadyUse($db, $hall->id, $slot->id)) {
                                                echo 'disabled';
                                            }
                                            ?>><?= $hall->hall ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>

                                    <div class="checkbox">
                                        <label for="mainSession">
                                            Session Principale
                                            <input type="checkbox" name="mainSession" id="mainSession"<?php
                                            if ($session->mainSession == TRUE) {
                                                echo ' checked';
                                            } elseif (Sessions::isMainSessionAlreadyDefine($db, $event->id)) {
                                                echo ' disabled';
                                            }
                                            ?>>
                                        </label>
                                    </div>


                                    <hr>
                                    <div class="speakers vScroll">
                                        <label for="">Conférenciers</label>
                                        <?php
                                        $speakers = Sessions::getAllSpeaker($db);
                                        $speakersSession = Sessions::speakerSession($db, $session->id);

                                        for ($i = 0; $i < $nbSpeakers; $i++) {
                                            ?>
                                            <select class="selectSpeaker" onchange="updateSpeakersSessions(this)" name="idSpeakers<?= $i ?>" id="" <?php
                                            if ($i == 0) {
                                                echo 'required';
                                            }
                                            ?>>
                                                <option value="">--------</option>
                                                <?php
                                                foreach ($speakers as $speaker) {
                                                    $speakerName = Sessions::getSpeakerName($db, $speaker->id);
                                                    echo "<option value='$speaker->id'";

                                                    if (isset($speakersSession[$i]) && $speakersSession[$i]->idSpeaker == $speaker->id) {
                                                        echo " selected";
                                                    } elseif (Sessions::isSpeakerAlreadyUse($db, $speaker->id, $slot->id)) {
                                                        echo " disabled";
                                                    }
                                                    echo ">$speakerName</option>";
                                                }
                                                ?>

                                            </select>
                                            <?php
                                        }
                                        ?>
                                    </div>

                                    <div class="button-group">
                                        <button name="modify" type="submit" value="<?= $session->id ?>"
                                                class="button button-std button-rounded"><i
                                                    class="fa fa-save fa-lg"
                                                    aria-hidden="true"></i>
                                        </button>
                                        <button name="delete" type="submit" value="<?= $session->id ?>"
                                                class="button button-abort button-rounded"><i
                                                    class="fa fa-trash fa-lg"
                                                    aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <?php
                        }
                    }
                    ?>
                    <div class="newBlock">
                        <div id="newBlockSession<?= $slot->id ?>"  <?php
                        if (Slots::isMainSlot($db, $slot->id)){
                            echo 'hidden';
                        }
                        ?>>
                            <div class="block-content engraved"
                                 onclick="showNewSessionBlock(<?= $event->id ?>, <?= $slot->id ?>)" >
                                <a>
                                    <div>
                                        <p class="text-engraved"> Ajouter <i class="fa fa-plus fa-lg"
                                                                             aria-hidden="true"></i></p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        }

        ?>

    </div>
    <?php

    ?>
    <?php
}
?>


<?php
require 'inc/footer.php';
?>
<script src="js/app.js"></script>
