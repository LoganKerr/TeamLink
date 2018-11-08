<?php

require_once("config/config.php");
require_once("functions.php");
require_once("vendor/autoload.php");

$stmt = $conn->prepare("SELECT `title`, `description` FROM `teams` WHERE `id`=?");
// SELECT teams.`title`, teams.`description`, role_assoc.`role`, users.`firstName`, users.`lastName` FROM `teams` INNER JOIN `role_assoc` ON teams.`id`=role_assoc.`team_id`  LEFT JOIN `users` ON role_assoc.`user_id`=users.`id` where teams.`id`=13
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows == 0)
{
    die("Team not found.");
}

//$stmt2 = $conn->prepare("SELECT `role_assoc`.`role_id`, `role_assoc`.`user_id`, `role_assoc`.`selected`, roles.`role`, users.`firstName`, users.`lastName` FROM `role_assoc` LEFT JOIN `users` ON role_assoc.`user_id`=users.`id` LEFT JOIN `roles` ON `role_assoc`.`role_id`=`roles`.`id` WHERE roles.`role`!='Owner' && role_assoc.`team_id`=? ORDER BY `role_assoc`.`role_id`");
$stmt2 = $conn->prepare("SELECT `roles`.`role`, `roles`.`team_id`, `roles`.`id` AS `role_id`, `role_assoc`.`user_id`, `role_assoc`.`selected`, users.`firstName`, users.`lastName` FROM `roles` LEFT JOIN `role_assoc` ON `roles`.`id` = `role_assoc`.`role_id` LEFT JOIN `users` ON role_assoc.`user_id`=users.`id` WHERE `roles`.`team_id`=?");
$stmt2->bind_param("i", $id);
$stmt2->execute();
$res2 = $stmt2->get_result();

$team_row = $res->fetch_assoc();

$i = 0;
$team_rows2 = array();
while ($team_row2 = $res2->fetch_assoc())
{
    $team_rows2[$i] = $team_row2;
    $i++;
}

$render_items['title'] = $team_row['title'];
$render_items['description']= $team_row['description'];
$render_items['roles'] = $team_rows2;

?>