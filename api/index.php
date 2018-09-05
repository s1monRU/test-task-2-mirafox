<?php
/**
 * Created by PhpStorm.
 * User: Ivan Semenov
 * Date: 8/30/2018
 * Time: 1:33 AM
 */

/**
 * Автозагрузка классов, Трейты, Контроллеры, Исключения или Модели
 */
spl_autoload_register(function($className){
    if(stristr($className, 'Trait') !== false) {
        include "models/traits/$className.php";
        return;
    }
    if(stristr($className, 'Controller') !== false) {
        include "controllers/$className.php";
        return;
    }
    if(stristr($className, 'Exception') !== false) {
        include "exceptions/$className.php";
        return;
    }
    include "models/$className.php";
});

$action = $_GET['action'] ?? 'index';
$dataFromClient = $_POST ?? null;

$controller = new DefaultController($dataFromClient);
echo $controller->$action();
