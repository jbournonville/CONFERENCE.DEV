<?php
require 'inc/app.php';

if (!empty($_GET['idSession'])){
    $session = Sessions::getById($db, $_GET['idSession']);
    $module = Modules::getById($db, $session->idModule);
    $hall = Halls::getById($db, $session->idHall);
    ?>
    <label for="">Description</label>
    <p><?= $module->description ?></p>
    <label for="">Salle</label>
    <p>Salle <?= $hall->hall ?> | Batiment <?= $hall->building  ?> </p>
    <?php
}
?>
