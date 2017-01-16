<?php
require 'inc/app.php';

$days = Events::getEventDays($db, $_GET['eventId']);
$nbSlots = Events::getNbSlots($db, $_GET['eventId']);

echo "<input type='hidden' name='slotModification'>";

foreach ($days as $day) {
    ?>
    <div class="item">
        <h3><?= App::dateFormat($day) ?></h3>
        <?php
        $slots = Slots::getSlotsByDay($db, $day, $_GET['eventId']);
        for ($i = 0; $i < $nbSlots; $i++) {

            if (isset($slots[$i])) {
                $slotName = "slot_id_" . $slots[$i]->id . "_" . $day;
                ?>
                <input title="slot" class="slot-input" type="time" value="<?= App::hourFormat($slots[$i]->slot) ?>" name="<?= $slotName ?>">
                <button type="submit" class="button-rounded button-abort" name="deleteSlot"
                        value="<?= $slots[$i]->id ?>"><i class="fa fa-trash fa-lg" aria-hidden="true"></i>
                </button>
                <?php
            } else {
                $slotName = "slot_" . $day . "_" . ($i);
                ?>
                <input title="slot" type="time" name="<?= $slotName ?>">
                <?php
            }
        } ?>
    </div>
    <?php

}
?>
