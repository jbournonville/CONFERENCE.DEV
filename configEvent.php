<?php
require 'inc/app.php';

App::needAdmin($auth);

//var_dump($_POST);

if (isset($_POST['modify'])) {
    $params = [
        'event' => $_POST['event'],
        'address' => $_POST['address'],
        'description' => $_POST['description'],
        'eventDate' => $_POST['eventDate'],
        'duration' => intval($_POST['duration']),
        'nbSessionsPerDay' => intval($_POST['nbSessionsPerDay']),
        'nbSpeakerMaxByModule' => intval($_POST['nbSpeakerMaxByModule']),
        'bookingOpen' => boolval($_POST['bookingOpen']),
        'id' => intval($_POST['id']),
    ];


    Events::updateData($params, $db);
    $sess = Session::getInstance();
    $sess->setFlashMessage('La conférence à bien été mise à jour', 'success');
    $event = Events::getById($db, $_POST['id']);

    if (isset($_POST['slotModification'])) {
        foreach ($_POST as $key => $value) {
            if (!empty($value)) {
                if (substr($key, 0, 5) == "slot_") {
                    $slot = explode("_", $key);
                    if ($slot[1] == "id") {
                        $idSlot = $slot[2];
                        $slot = $slot[3] . " " . $value;
                        Slots::updateSlot($db, $idSlot, $slot);
                    } else {
                        $slot = $slot[1] . " " . $value;
                        Slots::createSlot($db, $event->id, $slot);
                        $sess->setFlashMessage('Le slot à bien été ajouté', 'success');
                    }
                }
            }
        }
    }
    $modification = true;
} else if (isset($_POST['deleteSlot'])) {
    Slots::delete($_POST['deleteSlot'], $db);
    $event = Events::getById($db, $_POST['id']);
    $modification = true;
} else if (isset($_POST['create'])) {
    $params = [
        'event' => $_POST['event'],
        'address' => $_POST['address'],
        'description' => $_POST['description'],
        'eventDate' => $_POST['eventDate'],
        'duration' => intval($_POST['duration']),
        'nbSessionsPerDay' => intval($_POST['nbSessionsPerDay']),
        'nbSpeakerMaxByModule' => intval($_POST['nbSpeakerMaxByModule']),
    ];

    Events::create($params, $db);
    $sess = Session::getInstance();
    $sess->setFlashMessage('La conférence à bien été ajoutée', 'success');
    $id = $db->lastInsertId();
    $event = Events::getById($db, $id);
    $modification = true;
} else if (isset($_REQUEST['eventId'])) {
    $event = Events::getById($db, $_REQUEST['eventId']);
    $modification = true;
} else {
    $modification = false;
}

if (!Events::isConfigurationValid($db, $event->id)) {
    $sess = Session::getInstance();
    $sess->setFlashMessage('Configuration incomplète, vérifiez les créneaux et la session principale', 'fail');
}

require 'inc/header.php';

?>
<div class="page-content">
    <div class="form">
        <h2> <?php
            if ($modification) {
                echo 'Modification conférence';
            } else {
                echo 'Création nouvelle conférence';
            }
            ?> </h2>
        <form action="" name="event" method="post">
            <div class="item">
                <label>Nom de la conférence</label>
                <input required type="text" name="event" placeholder="Conférence" value="<?php if ($modification) {
                    echo $event->event;
                } ?>">
            </div>
            <div class="item">
                <label>Date</label>
                <input required type="date" name="eventDate" placeholder="Date" value="<?php if ($modification) {
                    echo $event->eventDate;
                } ?>">
            </div>
            <div class="item">
                <label>Localisation</label>
                <input required type="text" name="address" placeholder="Adresse" value="<?php if ($modification) {
                    echo $event->address;
                } ?>">
            </div>
            <div class="item">
                <label>Description</label>
                <textarea name="description" placeholder="Description"><?php if ($modification) {
                        echo $event->description;
                    } ?></textarea>
            </div>
            <div class="item">
                <label>Durée</label>
                <input required type="number" name="duration" placeholder="1-31" value="<?php if ($modification) {
                    echo $event->duration;
                } ?>"
                       min="1" max="31"> jours
            </div>
            <div class="item">
                <label>Nombre de sessions par jours</label>
                <input required type="number" name="nbSessionsPerDay" placeholder="1-10"
                       value="<?php if ($modification) {
                           echo $event->nbSlotsPerDay;
                       } ?>" min="1" max="10">
            </div>
            <div class="item">
                <label>Nombre de conférenciers par module</label>
                <input required type="number" name="nbSpeakerMaxByModule" placeholder="1-5"
                       value="<?php if ($modification) {
                           echo $event->nbSpeakerMaxByModule;
                       } ?>" min="1" max="5">
            </div>

            <div class="item">
                <label for="bookingOpen">Réservations ouvertes</label>
                <select name="bookingOpen" id="bookingOpen" <?php
                if (!Events::isConfigurationValid($db, $event->id)) {
                    echo 'disabled';
                }
                ?>>
                    <option value="1" <?php
                    if ($event->bookingOpen) {
                        echo 'selected';
                    }
                    ?>>OUI
                    </option>
                    <option value="0" <?php
                    if (!$event->bookingOpen) {
                        echo 'selected';
                    }
                    ?>>NON
                    </option>
                </select>
            </div>

            <div id="slots">
                <?php
                if ($modification) {
                    ?>
                    <div class="item button-centered">
                        <button type="button" class="button button-valid " onclick="showSlots(<?= $event->id ?>)">
                            Configurer les créneaux
                        </button>
                    </div>
                    <?php
                } ?>

            </div>

            <?php
            if ($modification) {
                ?>
                <input type="hidden" name="id" value="<?= $event->id ?>">
                <div class="item button-centered">
                    <button type="submit" name="modify" class="button button-std ">Mettre à jour</button>
                </div>
                <?php
            } else {
                ?>
                <div class="item">
                    <button type="submit" class="button button-valid">Créer</button>
                </div>
                <?php
            }
            ?>
        </form>


    </div>
</div>

<?php
require 'inc/footer.php';
?>
<script src="js/app.js"></script>
