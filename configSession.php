<?php
require 'inc/app.php';
$idEvent = $_GET['eventId'];
$idSlot = $_GET['slotId'];
$modules = Modules::getAll($db);
$halls = Halls::getAll($db);
$nbSpeakers = Events::getNbSpeakers($db, $idEvent);
?>
<div class="block-content">
    <form action="" method="post">
        <label for="idModule">Module</label>
        <select name="idModule" id="" required>
            <option value="" disabled selected>------</option>
            <?php
            foreach ($modules as $module) {
                ?>
                <option value="<?= $module->id ?>" <?php
                if (Sessions::isModuleAlreadyUse($db, $module->id, $idSlot)) {
                    echo 'disabled';
                }
                ?>>
                    <?= $module->module ?>
                </option>
                <?php
            }
            ?>
        </select>
        <label for="idHall">Salle</label>
        <select name="idHall" id="" required>
            <option value="" disabled selected>------</option>
            <?php
            foreach ($halls as $hall) {
                ?>
                <option value="<?= $hall->id ?>" <?php
                if (Sessions::isModuleAlreadyUse($db, $hall->id, $idSlot)) {
                    echo 'disabled';
                }
                ?>>
                    <?= $hall->hall ?>
                </option>
                <?php
            }
            ?>
        </select>
        <hr>
        <div class="speakers vScroll">
            <label for="">Conférenciers</label>
            <?php
            $speakers = Sessions::getAllSpeaker($db);

            for ($i = 0; $i < $nbSpeakers; $i++) {
                ?>
                <select title="idSpeakers" name="idSpeakers<?= $i ?>" <?php
                if ($i == 0) {
                    echo 'required';
                }
                ?>>
                    <option value="">--------</option>
                    <?php
                    foreach ($speakers as $speaker) {
                        $speakerName = Sessions::getSpeakerName($db, $speaker->id);
                        echo "<option value='$speaker->id'";
                        if (Sessions::isSpeakerAlreadyUse($db, $speaker->id, $idSlot)) {
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
            <button name="create" type="submit"
                    class="button button-valid button-rounded" value="<?= $idSlot ?>"><i
                        class="fa fa-save fa-lg"
                        aria-hidden="true"></i>
            </button>
        </div>
    </form>
</div>