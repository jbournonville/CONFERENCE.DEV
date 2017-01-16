<?php
require 'inc/app.php';

App::needAdmin($auth);

if (isset($_POST['delete'])){
    Users::delete($_POST['delete'], $db);
}

$members = Users::getAll($db);

require 'inc/header.php';
?>

    <div class="page-content">

        <div class="table">
            <h2>Membres</h2>
            <table>

                <thead>
                <tr>
                    <th>Login</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($members as $membre) {
                    ?>
                    <tr>

                        <td><?= $membre->username ?></td>
                        <td><?= $membre->name ?></td>
                        <td><?= $membre->surname ?></td>
                        <td><a href="mailto:<?= $membre->email ?>"><?= $membre->email ?></a></td>
                        <td><?= Users::getRole($membre->id, $db) ?></td>
                        <td class="button-col">
                            <form action="configUser.php" method="get">
                                <button type="submit" class="button-rounded button-std" name="userId"
                                        value="<?= $membre->id ?>"><i
                                            class="fa fa-pencil fa-lg" aria-hidden="true"></i></button>
                                <button type="submit" class="button-rounded button-abort" name="delete" formaction=""
                                        formmethod="post" value="<?= $membre->id ?>"><i
                                            class="fa fa-trash fa-lg" aria-hidden="true"></i></button>
                            </form>
                        </td>
                    </tr>

                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>

<?php
require 'inc/footer.php';
?>