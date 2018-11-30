<?php

session_start();
ob_start();

require_once("config/config.php");
require_once("functions.php");
require_once("vendor/autoload.php");

//$required = array("title", "description");
//$error['edit-team'] = set_error_on_empty_required_fields($_POST, $required, $error);
// escape data
$id = $_GET['id'];
$title = $_POST['title'];
$description = $_POST['description'];

if (!empty($title))
{
    // TODO: validate title (same as createam)
}

if (!empty($description))
{
    // TODO: validate description (same as createteam)
}

//Validates team exists and user submitting request has permission to edit it
$stmt = $conn->prepare("
SELECT `teams`.`owner`, `role_assoc`.`user_id` FROM `teams` 
LEFT JOIN `role_assoc` ON `teams`.`id`=`role_assoc`.`team_id` 
WHERE (`teams`.`owner`=? OR (`role_assoc`.`user_id`=? AND `selected`)) AND `teams`.`id`=?");
$stmt->bind_param("iii", $user_id, $user_id, $id);
$stmt->execute();
$res = $stmt->get_result();
// if user is not associated with team or team does not exist
if ($res->num_rows == 0)
{
    $error['page'] = "Invalid permission";
}


if (count($error) == 0)
{
    // updates team's title and description
    $stmt = $conn->prepare("UPDATE `teams` SET `title`=?, `description`=? WHERE `id`=?");
    $stmt->bind_param("ssi", $title, $description, $id);
    if (!$stmt->execute())
    {
        $error['sql'] = $stmt->error;
    }
    // updates new/removed roles/users
    // for each role field
    foreach ($_POST as $key => $value)
    {
        // updates user assigned to role
        if (substr($key, 0, 9) == "role_user")
        {
            $role_id = filter_var(substr($key, 9), FILTER_SANITIZE_NUMBER_INT);
            $user_id = (int)$value;
            $stmt = $conn->prepare("UPDATE `role_assoc` SET `selected`=0 WHERE `team_id`=? AND `role_id`=?");
            $stmt->bind_param("ii", $id, $role_id);
            $stmt->execute();
            $stmt = $conn->prepare("UPDATE `role_assoc` SET `selected`=1 WHERE `team_id`=? AND `role_id`=? AND `user_id`=?");
            $stmt->bind_param("iii", $id, $role_id, $user_id);
            $stmt->execute();
        }
        // updates role name if changed
        else if (substr($key, 0, 9) == "role_name")
        {
            $role_id = filter_var(substr($key, 9), FILTER_SANITIZE_NUMBER_INT);
            $stmt = $conn->prepare("UPDATE `roles` SET `role`=? WHERE `id`=?");
            $stmt->bind_param("si", $value, $role_id);
            $stmt->execute();

        }
        // adds new role to team
        else if (substr($key, 0, 8) == "role_new")
        {
            if ($value != "")
            {
                // inserts new goal into goals table with name and empty text
                $stmt = $conn->prepare("INSERT INTO `roles` (role, team_id) VALUES (?, ?)");
                $stmt->bind_param("si", $value, $id);
                $stmt->execute();
                $new_goal_id = $conn->insert_id;
                // inserts new goal into role_assoc for user who posted request
                $stmt = $conn->prepare("INSERT INTO `role_assoc` (user_id, role_id) VALUES (?, ?)");
                $stmt->bind_param("ii", $user_id, $new_role_id);
                $stmt->execute();
            }
        }
        // removes roles that a user has requested removal of
        else if (substr($key, 0, 11) == "role_remove")
        {
            $role_id = filter_var(substr($key, 11), FILTER_SANITIZE_NUMBER_INT);
            // deletes role from role_assoc
            $stmt = $conn->prepare("DELETE FROM `role_assoc` WHERE `role_id`=?");
            $stmt->bind_param("i", $role_id);
            $stmt->execute();
            // deletes role from row
            $stmt = $conn->prepare("DELETE FROM `roles` WHERE `id`=?");
            $stmt->bind_param("i", $role_id);
            $stmt->execute();
        }
    }
}
?>
