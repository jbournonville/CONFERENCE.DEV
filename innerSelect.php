<?php
require 'inc/app.php';

if (!empty($_GET['idSlot'])) {
    $slot = Slots::getById($db, $_GET['idSlot']);
    $idUser = $auth->getId();
    ?>
    <option value=""></option>
    <?php
    $sessions = Sessions::getSessionsBySlots($db, $slot->id);
    foreach ($sessions as $session) {
        $module = Modules::getById($db, $session->idModule);
        ?>
        <option value="<?= $session->id ?>" <?php
        if (Sessions::isUserBookThisSession($db, $idUser, $session->id)) {
            echo 'selected';
        } else if (Modules::isUserAlreadyBookThisModule($db, $idUser, $module->id)) {
            echo 'disabled';
        }
        ?>><?= $module->module ?></option>
    <?php }
}?>