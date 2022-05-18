<!--файл на который будут приходить все запросы которые захочет ввести user-->
<?php

const VG_ACCESS = true; //константа безопасиности

header('Content-Type:text/html;charset=utf-8');
session_start();



require_once 'config.php';
require_once 'core/base/settings/internal_settings.php';