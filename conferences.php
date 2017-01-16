<?php
require 'inc/app.php';

App::needAdmin($auth);

if (isset($_POST['delete'])) {
    Events::delete($_POST['delete'], $db);
    $sess = Session::getInstance();
    $sess->setFlashMessage('La conférence à bien été supprimée', 'std');
}

$events = Events::getAll($db);


require 'inc/header.php';

?>
    <div class="page-content">
        <h2>Conférences</h2>
    </div>
<?php
foreach ($events as $event) {
    if($event->eventDate < date("Y-m-d")){
        $passed = true;
    }else{
        $passed = false;
    }
    ?>
    <div class="block-content zoom-hover <?php if($passed){echo "passed";} ?>">
        <h2><?= $event->event ?><?php if($passed){echo " - TERMINÉ";}?></h2>
        <p> Date : <?= App::dateFormat($event->eventDate) ?></p>
        <p> Durée : <?= $event->duration ?> jours</p>
        <p>Localisation : <?= $event->address ?></p>
        <hr>
        <h3>Description</h3>
        <p class="desc"><?= App::excerpt($event->description, 115) ?></p>
        <div class="button-group">
            <?php
                if(!$passed) {
                    ?>
                    <form action="configEvent.php" method="get">

                        <button type="submit" class="button button-small button-std" name="eventId"
                                value="<?= $event->id ?>">
                            Modifier
                        </button>
                        <button type="submit" class="button button-small button-submit" name="eventId"
                                value="<?= $event->id ?>" formaction="sessions.php"
                                formmethod="get">
                            Sessions
                        </button>
                        <button type="submit" name="delete" value="<?= $event->id ?>"
                                class="button button-small button-abort" formaction="" formmethod="post">
                            Supprimer
                        </button>
                    </form>
                    <?php
                }
                    ?>
        </div>


    </div>
    <?php
} ?>

    <div class="block-content engraved">
        <a href="configEvent.php">
            <div>
                <p class="text-engraved"> Ajouter <i class="fa fa-plus fa-lg" aria-hidden="true"></i></p>
            </div>
        </a>
    </div>

<?php
require 'inc/footer.php';
?>