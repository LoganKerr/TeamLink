<?php
// for each team field
foreach ($_POST as $key => $value) {
    // team is being deleted
    if ($key == "team") {
        $team_id = (int)$value;
        // deletes team from teams table
        $stmt = $conn->prepare("DELETE FROM `teams` WHERE `id`=?");
        $stmt2 = $conn->prepare("DELETE FROM `roles` WHERE `team_id`=?");
        $stmt3 = $conn->prepare("DELETE FROM `role_assoc` WHERE `team_id`=?");
        $stmt->bind_param("i", $team_id);
        $stmt2->bind_param("i", $team_id);
        $stmt3->bind_param("i", $team_id);
        $stmt->execute();
        $stmt2->execute();
        $stmt3->execute();
        $message = "Team deleted";
    }
}
?>