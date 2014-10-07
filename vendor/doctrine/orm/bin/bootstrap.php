<?php
// bootstrap.php
//require_once __DIR__."/../../module/wm/Entity/User.php";
//require_once __DIR__."/../../module/wm/Entity/Task.php";
//require_once __DIR__."/../../module/wm/Entity/Role.php";

if (!class_exists("Doctrine\Common\Version", false)) {
    require_once "bootstrap_doctrine.php";
}

//require_once "doctrinecomponents/repositories/BugRepository.php";
