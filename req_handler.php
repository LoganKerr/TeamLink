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
     if ($_POST['action']=="delete_team")
     {
        $required = array("id");
        $error = set_error_on_empty_required_fields($_POST, $required, $error);
        $id = (int)$_POST['id'];
        if ($id > -1)
        {
            // validate team exists
            $stmt = $conn->prepare("SELECT * FROM `teams` WHERE `id`=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res->num_rows == 0) {
                $error['id'] = "No team found";
            }
            else {
                // validate user associated with team or owner
                $stmt = $conn->prepare("SELECT * FROM `teams` LEFT JOIN `role_assoc` ON `teams`.`id`=`team_id` WHERE `teams`.`id`=? AND (`owner`=? OR `user_id`=?)");
                $stmt->bind_param("iii", $id, $user_id, $user_id);
                $stmt->execute();
                $res = $stmt->get_result();
                if ($res->num_rows == 0) {
                    $error['id'] = "User not associated with team";
                }
            }
        }
        if (count($error) == 0)
        {
            // deletes team from teams table
            $stmt = $conn->prepare("DELETE FROM `teams` WHERE `id`=?");
            $stmt->bind_param("i", $id);
            $stmt2 = $conn->prepare("DELETE FROM `roles` WHERE `team_id`=?");
            $stmt2->bind_param("i", $id);
            $stmt3 = $conn->prepare("DELETE FROM `role_assoc` WHERE `team_id`=?");
            $stmt3->bind_param("i", $id);
            $stmt->execute();
            $stmt2->execute();
            $stmt3->execute();
        }
    }
    else if ($_POST['action'] == "remove")
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
    else if ($_POST['action'] == "change_title")
    {
        $required = array("title", "id");
        $error = set_error_on_empty_required_fields($_POST, $required, $error);
        $title = $_POST["title"];
        $id = $_POST["id"];
        if (!empty($title))
        {
            // validate title
            // validate user has permission to change title
            $stmt = $conn->prepare("SELECT * FROM `teams` LEFT JOIN `role_assoc` ON `teams`.`id`=`team_id` WHERE `teams`.`id`=? AND (`owner`=? OR `user_id`=?)");
            $stmt->bind_param("iii", $id, $user_id, $user_id);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res->num_rows == 0) {
                $error['id'] = "User not associated with team";
            }
        }
        if (count($error) == 0)
        {
            // change title
            $stmt = $conn->prepare("UPDATE `teams` SET `title` = ? WHERE `teams`.`id` = ?");
            $stmt->bind_param("si", $title, $id);
            $stmt->execute();
        }
    }
    else if ($_POST['action'] == "change_desc")
    {
        $required = array("desc", "id");
        $error = set_error_on_empty_required_fields($_POST, $required, $error);
        $desc = $_POST["desc"];
        $id = $_POST["id"];
        if (!empty($desc))
        {
            // validate description
            // validate user has permission to change title
            $stmt = $conn->prepare("SELECT * FROM `teams` LEFT JOIN `role_assoc` ON `teams`.`id`=`team_id` WHERE `teams`.`id`=? AND (`owner`=? OR `user_id`=?)");
            $stmt->bind_param("iii", $id, $user_id, $user_id);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res->num_rows == 0) {
                $error['id'] = "User not associated with team";
            }
        }
        if (count($error) == 0)
        {
            // change title
            $stmt = $conn->prepare("UPDATE `teams` SET `description` = ? WHERE `teams`.`id` = ?");
            $stmt->bind_param("si", $desc, $id);
            $stmt->execute();
        }
    }
}