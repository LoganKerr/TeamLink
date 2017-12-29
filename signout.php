<?php
    session_start();
    ob_start();
    
    include "config/config.php";
    
    // if user is not signed in
    if(!isset($_SESSION['user_id']))
    {
        header("Location: index.php");
    }
    
    unset($_SESSION['user_id']);
    header("Location: index.php");
?>
