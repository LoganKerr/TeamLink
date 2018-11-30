<?php

session_start();
ob_start();
header('Content-Type: text/html; charset=utf-8');

require_once("config/config.php");
require_once("functions.php");
require_once("vendor/autoload.php");

// if user is not signed in
if (!isset($_SESSION['user_id']))
{
    header("Location: index.php");
}

$user_id = $_SESSION['user_id'];

// profile form submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    error_log(print_r($_POST, true));
    if ($_POST['action'] == "remove")
    {
        $role_id = (int)$_POST['role_id'];
        $stmt = $conn->prepare("DELETE FROM `interests` WHERE `id`=? AND `user_id`=?");
        $stmt->bind_param("ii", $role_id, $user_id);
        $stmt->execute();
    }
    else if ($_POST['action'] == "add")
    {
        $tag = $_POST['tag'];
        $stmt = $conn->prepare("INSERT INTO `interests` (`user_id`, `tag`) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $tag);
        $stmt->execute();
    }

}