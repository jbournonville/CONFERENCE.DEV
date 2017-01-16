<?php
require 'inc/app.php';

App::needAdmin($auth);

if(isset($_POST['delete'])){
    Halls::delete($_POST['delete'], $db);
}

$halls = Halls::getAll($db);

require 'inc/header.php';
?>

<div class="page-content">
    <div class="table">
        <h2>Membres</h2>
        <table>

            <thead>
            <tr>
                <th>Hall</th>
                <th>Batiment</th>
                <th>Capacité</th>
                <th>Image</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($halls as $hall) {
                ?>
                <tr>
                    <td><?= $hall->hall ?></td>
                    <td><?= $hall->building ?></td>
                    <td><?= $hall->capacity ?></td>
                    <td><?php
                        if (!empty($hall->picture)){
                            ?>
                            <img src="<?= $hall->picture ?>" alt="<?= $hall->hall ?>">
                            <?php
                        }
                    ?></td>
                    <td class="button-col">
                        <form action="configHall.php" method="get">
                            <button type="submit" class="button-rounded button-std" name="hallId"
                                    value="<?= $hall->id ?>"><i
                                    class="fa fa-pencil fa-lg" aria-hidden="true"></i></button>
                            <button type="submit" class="button-rounded button-abort" name="delete" formaction=""
                                    formmethod="post" value="<?= $hall->id ?>"><i
                                    class="fa fa-trash fa-lg" aria-hidden="true"></i></button>
                        </form>
                    </td>
                </tr>

                <?php
            }
            ?>

            <tr>
                <td class="add-button">
                    <a href="">
                        Ajouter <i class="fa fa-plus fa-lg" aria-hidden="true"></i>
                    </a>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<?php
require 'inc/footer.php'
?>