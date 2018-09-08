<?php

// requires config file
require_once(dirname(__FILE__).'/../config/config.php');
// email is first parameter
$email = $argv[1];
// password is second parameter
$pass = $argv[2];
$hash = password_hash($pass, PASSWORD_DEFAULT);
$query = "UPDATE `users` SET `passHash`='".$hash."' WHERE `email`='".$email."'";

if(mysqli_query($conn, $query))
{
    echo "Password successfully updated";
}
else
{
    echo "Error: ".mysqli_error($conn);
}
?>

