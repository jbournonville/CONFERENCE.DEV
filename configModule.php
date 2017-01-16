<?php
require 'inc/app.php';

App::needAdmin($auth);

if (isset($_REQUEST['modify'])) {

    Modules::updateData([
        $_POST['module'],
        $_POST['description'],
        $_POST['id']
    ], $db);

    $sess = Session::getInstance();
    $sess->setFlashMessage('Le module à bien été mise à jour', 'success');
    $module = Modules::getById($db, $_POST['id']);
    $modification = true;

} else if (isset($_REQUEST['create'])) {
    Modules::create([
        $_POST['module'],
        $_POST['description']
    ], $db);

    $sess = Session::getInstance();
    $sess->setFlashMessage('Le module à bien été ajouté', 'success');
    $id = $db->lastInsertId();
    $module = Modules::getById($db, $id);
    $modification = true;

} else if (isset($_GET['moduleId'])) {
    $module = Modules::getById($db, $_GET['moduleId']);
    $modification = true;
} else {
    $modification = false;
}

require 'inc/header.php';
?>

    <div class="page-content">
        <div class="form">
            <h2> <?php
                if ($modification) {
                    echo 'Modification module';
                } else {
                    echo 'Création nouveau module';
                }
                ?> </h2>

            <form action="" name="event" method="post">
                <div class="item">
                    <label>Nom du module</label>
                    <input required type="text" name="module" placeholder="Module" value="<?php if ($modification) {
                        echo $module->module;
                    } ?>">
                </div>
                <div class="item">
                    <label>Description</label>
                    <textarea name="description" placeholder="Description"><?php if ($modification) {
                            echo $module->description;
                        } ?></textarea>
                </div>
                <?php
                if ($modification) {
                    ?>
                    <input type="hidden" name="id" value="<?= $module->id ?>">
                    <div class="item button-centered">
                        <button type="submit" name="modify" class="button button-std">Mettre à jour le module</button>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="item button-centered">
                        <button type="submit" name="create" class="button button-valid">Créer le module</button>
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