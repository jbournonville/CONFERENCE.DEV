<?php
require 'inc/app.php';

if (isset($_REQUEST['modify'])) {

    $user = Users::getById($db, $_POST['id']);

    if ($_POST['password'] != "") {
        if ($_POST['password'] == $_POST['password_confirmation']) {
            $passwordEncrypt = password_hash($_POST['password'], PASSWORD_BCRYPT);
        }
    } else {
        $passwordEncrypt = $user->password;
    }

    if (isset($_POST['idRole'])) {
        $idRole = $_POST['idRole'];
    } else {
        $idRole = $user->idRole;
    }

    $params = [
        $_POST['username'],
        $_POST['name'],
        $_POST['surname'],
        $_POST['email'],
        $passwordEncrypt,
        intval($idRole),
        $_POST['themeChoice'],
        intval($_POST['id'])
    ];

    Users::updateData($params, $db);

    if ($auth->getId() == $_POST['id']) {
        $auth->resetUser($_POST['id'], $db);
    }

    $auth = Users::getInstance($db, $auth->getId());

    $sess = Session::getInstance();
    $sess->setFlashMessage('Le profil est mis à jour', 'success');

    $user = Users::getById($db, $_POST['id']);

} else if (isset($_GET['userId'])) {
    if ($auth->getId() != $_GET['userId']) {
        App::needAdmin($auth);
    }
    $user = Users::getById($db, $_GET['userId']);
}

$req = $db->query("SELECT * FROM roles");
$roles = $req->fetchAll();

require 'inc/header.php';
?>
    <div class="page-content">
        <div class="form">
            <h2>Votre profil</h2>
            <form action="" name="profile" method="post">
                <div class="item">
                    <label for="">Login</label>
                    <input type="text" name="username" placeholder="Login" value="<?= $user->username ?>">
                </div>
                <div class="item">
                    <label for="">Nom</label>
                    <input type="text" name="name" placeholder="Nom" value="<?= $user->name ?>" <?php
                    if ($user->name == "") {
                        echo "class='input-needed'";
                    }
                    ?>>
                </div>
                <div class="item">
                    <label for="">Prenom</label>
                    <input type="text" name="surname" placeholder="Prénom"
                           value="<?= $user->surname ?>" <?php
                    if ($user->surname == "") {
                        echo "class='input-needed'";
                    }
                    ?>>
                </div>
                <div class="item">
                    <label for="">Email</label>
                    <input type="email" name="email" placeholder="Email" value="<?= $user->email ?>">
                </div>
                <div class="item">
                    <label for="">Catégorie</label>
                    <select name="idRole" <?php if (!$auth->isAdmin()) {
                        echo "disabled";
                    } ?> title="idRole">
                        <?php
                        foreach ($roles as $role) {
                            if ($role->id == $user->idRole) {
                                echo "<option value=$role->id selected>$role->role</option>";
                            } else {
                                echo "<option value=$role->id>$role->role</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="item">
                    <label for="">Changer Mot De Passe</label>
                    <input type="password" name="password" placeholder="Mot de passe">
                </div>
                <div class="item">
                    <label for="">Confirmation Mot De Passe</label>
                    <input type="password" name="password_confirmation" placeholder="Mot de passe">
                </div>
                <div class="item">
                    <label for="">Thème</label>
                    <select name="themeChoice" title="themeChoice">
                        <option value="main1.css" <?php
                        if ($user->themeChoice == 'main1.css') {
                            echo 'selected';
                        }
                        ?> >Thème clair
                        </option>
                        <option value="main2.css" <?php
                        if ($user->themeChoice == 'main2.css') {
                            echo 'selected';
                        }
                        ?>>Thème sombre
                        </option>
                    </select>
                </div>
                <div class="item button-centered">
                    <button type="submit" name="modify" class="button button-std button-centered">Mettre à jour</button>
                </div>
                <input type="hidden" name="id" value="<?= $user->id ?>">
            </form>
        </div>
    </div>


<?php require 'inc/footer.php'; ?>