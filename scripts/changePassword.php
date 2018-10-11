<?php

// requires config file
require_once(dirname(__FILE__).'/../config/config.php');
// email is first parameter
$email = $argv[1];
// password is second parameter
$pass = $argv[2];
$hash = password_hash($pass, PASSWORD_DEFAULT);
$stmt = $conn->prepare("UPDATE `users` SET `passHash`=? WHERE `email`=?");
$stmt->bind_param("ss", $hash, $email);

if($stmt->execute())
{
    echo "Password successfully updated";
}
else
{
    echo "Error: ".$stmt->error;
}
?>

