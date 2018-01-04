<?php
    session_start();
    ob_start();
    
    include "config/config.php";
    
    // if user is not signed in
    if (!isset($_SESSION['user_id']))
    {
        header("Location: index.php");
    }
    
    $user_id = $_SESSION['user_id'];
    
    // create team form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $error = array();
        // validate data -------------------------------------
        // check empty fields
        $required = array("title", "description");
        foreach ($required as $key => $value)
        {
            if(!isset($_POST[$value]) || empty($_POST[$value]) && $_POST[$value] != '0')
            {
                $error[$value] = "<strong>This field is required.</strong>";
            }
        }
        // escape data
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        
        if (!empty($title))
        {
            // TODO: validate title
        }
        
        if (!empty($description))
        {
            // TODO: validate description
        }
        
        if (count($error) == 0)
        {
            $query = "INSERT INTO `teams` (`owner`, `title`, `description`) VALUES ('".$user_id."', '".$title."', '".$description."')";
            if (mysqli_query($conn, $query))
            {
                $team_id = $conn->insert_id;
                $query = "INSERT INTO `role_assoc` (`user_id`, `team_id`, `role`) VALUES ('".$user_id."', '".$team_id."', 'Owner')";
                if (mysqli_query($conn, $query))
                {
                    echo "<p><strong>Team created.</strong></p>";
                }
                else
                {
                echo "<p><strong>Error: ".mysqli_error($conn)."</strong></p>";
                }
            }
            else
            {
                echo "<p><strong>Error: ".mysqli_error($conn)."</strong></p>";
            }
        }
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
                <li><a href="menu.php">Main Menu</a></li>
                <li><a href="/profile.php">Profile</a></li>
                <li><a href="/myteams.php">My Teams</a></li>
                <li class="active"><a href="/createteam.php">Create a Team</a></li>
                <li><a href="/jointeam.php">Join a Team</a></li>
            </ul>
        </div>
    </nav>
    <div id="login-panel" class="container">
        <div class="panel panel-primary">
            <div class="panel-heading text-center">Enter the information for a new team</div>
            <div class="panel-body">
                <div class="container">
                    <form method="post" action="/createteam.php">
                        <p><label>Title:</label><input class="textbox" name="title" type="text" /></p>
                        <p><label>Description:</label><textarea name="description"></textarea></p>
                        <div class="submit-button"><input class="btn btn-primary btn-block" type="submit" value="Create Team" /></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
