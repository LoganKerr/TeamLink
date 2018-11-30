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
        $required = array("role_id");
        $error = set_error_on_empty_required_fields($_POST, $required, $error);
        $role_id = (int)$_POST['role_id'];
        if (count($error) == 0)
        {
            // removes tag for user
            $stmt = $conn->prepare("DELETE FROM `interests` WHERE `id`=? AND `user_id`=?");
            $stmt->bind_param("ii", $role_id, $user_id);
            $stmt->execute();
        }
    }
    else if ($_POST['action'] == "add")
    {
        $required = array("tag");
        $error = set_error_on_empty_required_fields($_POST, $required, $error);
        $tag = $_POST['tag'];
        if (!empty($tag))
        {
            // validate tag
            // gets number of rows where user id has given tag
            $stmt = $conn->prepare("SELECT * FROM `interests` WHERE `user_id`=? AND `tag`=?");
            $stmt->bind_param("is", $user_id, $tag);
            $stmt->execute();
            $res = $stmt->get_result();
            // if user does not already have tag
            if ($res->num_rows > 0) { $error['tag'] = "duplicate tag"; }
        }
        if (count($error) == 0)
        {
            // insert new tag into database
            $stmt = $conn->prepare("INSERT INTO `interests` (`user_id`, `tag`) VALUES (?, ?)");
            $stmt->bind_param("is", $user_id, $tag);
            $stmt->execute();
        }
    }

}