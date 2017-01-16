<?php
require 'inc/app.php';

App::needAdmin($auth);

if (isset($_POST['modify'])) {
    $params = [
        'hall' => $_POST['hall'],
        'building' => $_POST['building'],
        'capacity' => intval($_POST['capacity']),
        'id' => intval($_POST['id'])
    ];

    Halls::updateData($params, $db);
    if (!empty($_FILES["pictureToUpload"]["name"])){
        Halls::uplaodPicture($_FILES["pictureToUpload"],$_POST['id'], $db );
    }
    $sess = Session::getInstance();
    $sess->setFlashMessage('La conférence à bien été mise à jour', 'success');
    $hall = Halls::getById($db, $_POST['id']);
    $modification = true;
} else if (isset($_POST['create'])) {
    $params = [
        'hall' => $_POST['hall'],
        'building' => $_POST['building'],
        'picture' => $_POST['picture'],
        'capacity' => intval($_POST['capacity'])
    ];

    Halls::create($params, $db);
    $sess = Session::getInstance();
    $sess->setFlashMessage('La conférence à bien été ajoutée', 'success');
    $id = $db->lastInsertId();
    if (!empty($_FILES["pictureToUpload"]["name"])){
        Halls::uplaodPicture($_FILES["pictureToUpload"]["name"],$id, $db);
    }
    $hall = Halls::getById($db, $id);
    $modification = true;
} else if (isset($_REQUEST['hallId'])) {
    $hall = Halls::getById($db, $_REQUEST['hallId']);
    $modification = true;
} else {
    $modification = false;
}


$halls = Halls::getAll($db);

require 'inc/header.php';
?>

<div class="page-content">
    <div class="form">
        <h2> <?php
            if ($modification) {
                echo 'Modification salle';
            } else {
                echo 'Ajout nouvelle salle';
            }
            ?> </h2>
        <form action="" name="event" method="post" enctype="multipart/form-data">
            <div class="item">
                <label>Nom de la salle</label>
                <input required type="text" name="hall" placeholder="Conférence" value="<?php if ($modification) {
                    echo $hall->hall;
                } ?>">
            </div>
            <div class="item">
                <label>Localisation de la salle (batiment)</label>
                <input title="building" list="hallList" name="building" value="<?= $hall->building ?>">
                <datalist id="hallList">
                    <?php
                    $buildings = Halls::getBuildings($db);
                    foreach ($buildings as $building) {
                        echo "<option value=$building->building>";
                    }
                    ?>
                </datalist>
            </div>
            <div class="item">
                <label>Capacité</label>
                <input required type="number" name="capacity" placeholder="1-600"
                       value="<?php if ($modification) {
                           echo $hall->capacity;
                       } ?>" min="1" max="600">
            </div>

            <div class="item" id="hall-picture">
                <?php
                if (Halls::havePicture($db, $hall->id)) {
                    ?>
                    <div style="text-align: center">
                        <img name="picture" src="<?= $hall->picture ?>" alt="<?= $hall->picture ?>">
                        <input type="file" name="pictureToUpload">
                    </div>

                    <?php
                } else {
                    ?>
                    <input type="file" name="pictureToUpload">
                    <?php
                }
                ?>
            </div>

            <?php
            if ($modification) {
                ?>
                <input type="hidden" name="id" value="<?= $hall->id ?>">
                <div class="item button-centered">
                    <button type="submit" name="modify" class="button button-std">Mettre à jour</button>
                </div>
                <?php
            } else {
                ?>
                <div class="item button-centered">
                    <button type="submit" class="button button-valid">Créer</button>
                </div>
                <?php
            }
            ?>
        </form>
    </div>

    <?php
    require 'inc/footer.php'
    ?>
    <script src="js/app.js"></script>
