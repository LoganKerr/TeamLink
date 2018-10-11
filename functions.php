<?php
    
    require_once("config/config.php");
    
    // checks if user_id is marked admin in database
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
    
    // marks error on all fields passed in $required that are found to be empty or missing
    function set_error_on_empty_required_fields($POST, $required, $error)
    {
        foreach ($required as $key => $value)
        {
            if(!isset($_POST[$value]) || empty(trim($_POST[$value])) && $_POST[$value] != '0')
            {
                $error[$value] = "This field is required";
            }
        }
        return $error;
    }
?>
