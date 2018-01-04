<?php
    session_start();
    ob_start();
    
    require_once("config/config.php");
    
    // if user not signed in
    if (!isset($_SESSION['user_id']))
    {
        header("Location: index.php");
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
    <nav class="navbar navbar-inverse bg-primary">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">Venture</a>
            </div>
            <ul class="nav navbar-nav">
                <li class="active"><a href="menu.php">Main Menu</a></li>
                <li><a href="/profile.php">Profile</a></li>
                <li><a href="/myteams.php">My Teams</a></li>
                <li><a href="/createteam.php">Create a Team</a></li>
                <li><a href="/jointeam.php">Join a Team</a></li>
            </ul>
        </div>
    </nav>
    <div id="menu-panel" class="container">
        <div class="panel panel-primary">
            <div class="panel-heading text-center">Choose one of the following options</div>
            <div class="panel-body">
                <a href="/profile.php" class="btn btn-primary btn-block">Profile</a><br />
                <a href="/myteams.php" class="btn btn-primary btn-block">My Teams</a><br />
                <a href="/createteam.php" class="btn btn-primary btn-block">Create Team</a><br />
                <a href="/jointeam.php" class="btn btn-primary btn-block">Join Team</a><br />
                <a href="/signout.php" class="btn btn-primary btn-block">Sign out</a><br />
            </div>
        </div>
    </div>
</body>
</html>
