<?php

spl_autoload_register('loader');

function loader($class){
    require "class/$class.php";
}