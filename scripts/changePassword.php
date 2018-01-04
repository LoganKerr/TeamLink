<?php

require "/Applications/MAMP/htdocs/config/config.php";
$email = $argv[1];
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

