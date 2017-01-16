<?php
require 'inc/app.php';

App::needAdmin($auth);

if (isset($_POST['delete'])) {
    Modules::delete($_POST['delete'], $db);
    $sess = Session::getInstance();
    $sess->setFlashMessage('La module à bien été supprimée', 'std');
}

$modules = Modules::getAll($db);

require 'inc/header.php';
?>

<div class="page-content">
    <h2>Modules</h2>
</div>

<?php
foreach ($modules as $module) {
    ?>

    <div class="block-content block-small zoom-hover">
        <h2><?= $module->module ?></h2>
        <hr>
        <h3>Description</h3>
        <p class="desc"><?= App::excerpt($module->description, 115) ?></p>
        <div class="button-group">
            <form action="configModule.php" method="get">
                <button type="submit" name="moduleId" value="<?= $module->id ?>" class="button button-small button-std">
                    Modifier
                </button>
                <button type="submit" name="delete" value="<?= $module->id ?>"
                        class="button button-small button-abort" formaction="" formmethod="post">Supprimer
                </button>
            </form>
        </div>
    </div>


    <?php
}
?>

<div class="block-content block-small engraved">
    <a href="configModule.php">
        <div>
            <p class="text-engraved"> Ajouter <i class="fa fa-plus fa-lg" aria-hidden="true"></i></p>
        </div>
    </a>
</div>

<?php
require 'inc/footer.php';
?>