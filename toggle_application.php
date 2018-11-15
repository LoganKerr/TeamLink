<?php

require_once("config/config.php");
require_once("functions.php");
require_once("vendor/autoload.php");

// check empty fields
$required = array("role", "team-id", "role-application-action");
$error['role-application'] = set_error_on_empty_required_fields($_POST, $required, $error);

$role_id = (int)$_POST['role'];
$team_id = (int)$_POST['team-id'];
$role_action = $_POST['role-application-action'];


// check if role exists
$stmt = $conn->prepare("
    SELECT COUNT(`roles`.`id`) AS 'count'
    FROM `roles`
    WHERE `roles`.`id`=? AND `roles`.`team_id`=?");
$stmt->bind_param("ii", $role_id, $team_id);
if ($stmt->execute()) {
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    // if exactly one role has been found for role id and team_id
    if (isset($row) && $row['count'] == 1)
    {
        // gets owner id of applied team
        $stmt = $conn->prepare("SELECT `teams`.`owner` FROM `teams` WHERE `teams`.`id`=?");
        $stmt->bind_param("i", $team_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $owner_id = $row['owner'];
        // gets user id of applying user and university of user applying
        $stmt = $conn->prepare("SELECT `users`.`id`, `users`.`university_id` FROM `users` WHERE `users`.`id`=? OR `users`.`id`=?");
        $stmt->bind_param("ii", $user_id, $owner_id);
        if ($stmt->execute()) {
            $res = $stmt->get_result();
            $same_university = false;
            // owner is same as user
            if (mysqli_num_rows($res) == 1) {
                $same_university = true;
            } // applying user is not same user as owner of team
            else {
                // get university of user id
                $row = $res->fetch_assoc();
                $user_university_id = $row['university_id'];
                // get university of owner id
                $row = $res->fetch_assoc();
                $owner_university_id = $row['university_id'];
                // owner and user are from same university
                $same_university = $user_university_id == $owner_university_id;
            }
            // user applying and owner of team are at same university
            if ($same_university) {
                // get number of times user has applied for this role
                $stmt = $conn->prepare("SELECT COUNT(`role_assoc`.`user_id`) AS `count` FROM `role_assoc` WHERE `role_assoc`.`role_id`=? AND `role_assoc`.`user_id`=?");
                $stmt->bind_param("ii", $role_id, $user_id);
                if ($stmt->execute()) {
                    $res = $stmt->get_result();
                    $row = $res->fetch_assoc();
                    // if user wants to apply and has not already applied for this role
                    if ($role_action == "apply" && $row['count'] == 0)
                    {
                        // sets role_assoc row for role
                        $stmt = $conn->prepare("INSERT INTO `role_assoc` (user_id, team_id, role_id) VALUES (?, ?, ?)");
                        $stmt->bind_param("iii", $user_id, $team_id, $role_id);
                        if (!$stmt->execute()) {
                            $error['role-application']['sql'] = $stmt->error;
                        }
                    }
                    // if user wants to apply and has already applied for this role
                    else if ($role_action == "apply" && $row['count'] != 0)
                    {
                        $error['role-application']['role'] = "Application has already been submitted for this role.";
                    }
                    // if user wants to retract role and has applied for this role previously
                    else if ($role_action == "retract" && $row['count'] != 0)
                    {
                        // removes role_assoc row
                        $stmt = $conn->prepare("DELETE FROM `role_assoc` WHERE `role_assoc`.`role_id`=? AND `role_assoc`.`user_id`=?");
                        $stmt->bind_param("ii", $role_id, $user_id);
                        if (!$stmt->execute()) {
                            $error['role-application']['sql'] = $stmt->error;
                        }

                    }
                    // if user wants to retract role and has not applied for this role previously
                    else
                    {
                        $error['role-application']['role'] = "Application for this role has not been found";
                    }
                }
            } // user applied to a role that belongs to a team outside his/her university
            else {
                $error['role-application']['role'] = "Invalid role";
            }
        }
        else
        {
            $error['role-application']['sql'] = $stmt->error;
        }
    }
    else
    {
        $error['role-application']['role'] = "Number of roles found: ".$row['count'];
    }
}
else
{
    $error['role-application']['sql'] = $stmt->error;
}

?>