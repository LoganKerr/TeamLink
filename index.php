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

<?php include "resources/templates/header.php"; ?>
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
