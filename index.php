<!--файл на который будут приходить все запросы которые захочет ввести user-->
<?php

const VG_ACCESS = true; //константа безопасиности

header('Content-Type:text/html;charset=utf-8');
session_start();

require_once 'config.php';
require_once 'core/base/settings/internal_settings.php';
require_once 'core/base/settings/internal_settings.php';


use core\base\exceptions\RouteException;
use core\base\controller\RouteController;

try {
    RouteController::instance()->route();
} catch (RouteException $e) {
    exit($e->getMessage());
}