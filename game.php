<?php

$time_start = microtime(true);

session_start();

include "../private/mysql.php";
include "includes/include.inc.php";
contain("class", "player");
contain("class", "login");
contain("inc", "check_login");


if (!isset($_SESSION['ID'])) {
    header('LOCATION: index.php');
}

if ($_GET['logout']) {
    $logout = new login();
    $logout->logout();
    header('LOCATION: index.php');
}

$allowed_pages = array(
    "overview",
    "messages",
    "map",
    "settings",
    "research",
    "create_village"
 );

$page_name = $_GET['page'];

if (!isset($page_name) || !in_array($_GET['page'], $allowed_pages)) {
    $page_name = "overview";
}

contain("tpl", "header");
contain("tpl", "menu/top");
contain("tpl", "menu/left");
contain("inc", $page_name);

$time_end = microtime(true);
$data['time'] = round(($time_end - $time_start) * 1000);

contain("tpl", "footer", $data);
?>
