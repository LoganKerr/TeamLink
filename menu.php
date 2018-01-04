<?php
    session_start();
    ob_start();
    
    require_once("config/config.php");
    
    // if user not signed in
    if (!isset($_SESSION['user_id']))
    {
        header("Location: index.php");
        exit();
    }
?>
<?php include "resources/templates/header.php"; ?>
<?php include "resources/templates/navbar.php"; ?>
<body>
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
