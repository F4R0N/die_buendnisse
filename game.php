<?php

session_start();

include "../private/mysql.php";
include "includes/check_login.php";
include "includes/template.php";
include "includes/login.php";

if (!isset($_SESSION['ID'])) {
    header('LOCATION: index.php');
}

if ($_GET['logout']) {
    $logout = new login();
    $logout->logout();
    header('LOCATION: index.php');
}

$allowed_templates = array(
    'overview',
    'messages',
    'map'
);

$template_name = $_GET['page'];

if (!isset($template_name) || !in_array($_GET['page'], $allowed_templates)) {
    $template_name = "overview";
}

include_template("header");
include_template("menu_top");
include_template("menu_left");
include_template($template_name);
include_template("footer");
?>
