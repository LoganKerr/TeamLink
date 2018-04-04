<?php
    
    require_once("config/config.php");
    
    function check_if_user_is_admin($user_id)
    {
        global $conn;
        $stmt = $conn->prepare("SELECT `admin` FROM `users` WHERE `id`=?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        return $row['admin'];
    }
?>
