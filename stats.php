<?php
require 'inc/app.php';

require 'inc/header.php';
?>

    <div class="page-content">
        <h2 class="centered">Statistiques</h2>
        <div class="table table-small">
            <table>
                <thead>
                <tr>
                    <th>Information</th>
                    <th>Statistique</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Personnes connéctées actuellement</td>
                    <td class="dataNumeric"><?= Users::getNbUserConnected($db) ?></td>
                </tr>
                <tr>
                    <td>Personnes connéctées ajourd'hui</td>
                    <td class="dataNumeric"><?= Users::getNbUserConnectedToday($db) ?></td>
                </tr>
                <tr>
                    <td>Personnes enregistrées</td>
                    <td class="dataNumeric"><?= Users::getNbUser($db) ?></td>
                </tr>
                <tr>
                    <td>Nombre de conférencier</td>
                    <td class="dataNumeric"><?= Users::getNbSpeakers($db) ?></td>
                </tr>

                </tbody>
            </table>
        </div>
        <br>
        <div class="table table-small">
            <table>
                <thead>
                <tr>
                    <th>Sessions</th>
                    <th>Nombre de personnes inscrite</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $sessions = Sessions::getAll($db);
                  foreach ($sessions AS $session){
                      $module = Modules::getById($db, $session->idModule);
                      $slot = Slots::getById($db, $session->idSlot);
                      ?>
                      <tr>
                          <td><?= $module->module ?> - <?= App::dateHourFormat($slot->slot) ?></td>
                          <td class="dataNumeric"><?= Sessions::getNbUserSubscribe($db, $session->id) ?></td>
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