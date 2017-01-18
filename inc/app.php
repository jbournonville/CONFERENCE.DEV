<?php
require 'inc/autoloader.php';

$session = Session::getInstance();

App::needAuth($session);

$db = App::dbConnection();

$auth = Users::getInstance($session->getParams('user'), $db);

Users::updateActiveConnection($db, $auth->getId());
