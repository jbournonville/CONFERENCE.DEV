<?php
require 'inc/autoloader.php';

App::logout();
Session::getInstance()->setFlashMessage("Vous êtes déconnecté");
App::redirect('signin');
