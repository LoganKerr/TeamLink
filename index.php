<?php
    
    session_start();
    ob_start();
	require_once("config/config.php");
    if (isset($_SESSION['user_id']))
    {
        header("Location: menu.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Venture</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="resources/css/site.css">
</head>
<body>
    <div id="home-panel" class="container">
        <div class="panel panel-primary">
            <div class="panel-heading text-center"><image class="logo" src="/resources/images/logo.png"></image></div>
            <div class="panel-body">
                <a href="/login.php" class="btn btn-primary btn-block">Log in</a><br />
                <a href="/signup.php" class="btn btn-primary btn-block" role="button">Sign up</a>
            </div>
        </div>
    </div>
</body>
</html>
