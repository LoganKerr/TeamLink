<?php
    
    session_start();
    include "config/config.php";
    
    // if user not signed in
    if (!isset($_SESSION['user_id']))
    {
        header("Location: index.php");
    }
    
    $user_id = $_SESSION['user_id'];
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $query = "SELECT `user_id` FROM `role_assoc` WHERE `user_id`=$user_id AND `team_id`=$id";
    $res = mysqli_query($conn, $query);
    // if user is not associated with team
    if (mysqli_num_rows($res) == 0)
    {
        header("Location: myteams.php");
        exit();
    }
    
    // edit form submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $error = array();
        $warning = array();
        // validate data -----------------------------------
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
        $id = mysqli_real_escape_string($conn, $_GET['id']);
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        
        if (!empty($title))
        {
            // TODO: validate title (same as createam)
        }
        
        if (!empty($description))
        {
            // TODO: validate description (same as createteam)
        }
        
        
        if (count($error) == 0)
        {
            $query = "UPDATE `teams` SET `title`='".$title."', `description`='".$description."' WHERE `id`=$id";
            if (mysqli_query($conn, $query))
            {
                echo "Changes saved.";
            }
            else
            {
                echo "Error: ".mysqli_error($conn);
            }
        }
    }
    
    $query = "SELECT `title`, `description` FROM `teams` WHERE `id`=$id";
    
    $res = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($res) == 0)
    {
        die("Team not found.");
    }
    $row = mysqli_fetch_assoc($res);
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
                <li class="active"><a href="/myteams.php">My Teams</a></li>
                <li><a href="/createteam.php">Create a Team</a></li>
                <li><a href="/jointeam.php">Join a Team</a></li>
            </ul>
        </div>
    </nav>
    <div id="login-panel" class="container">
        <div class="panel panel-primary">
            <div class="panel-heading text-center">Enter the information for a new team</div>
                <div class="panel-body">
                    <div class="container">
                        <form method="post" action='<?php echo "/editteam.php?id=".mysqli_real_escape_string($conn, $_GET[id]); ?>'>
                            <p><label>Title:</label><input class="textbox" name="title" type="text" value='<?php echo $row['title'] ?>' /></p>
                            <p><label>Description:</label><textarea name="description"><?php echo $row['description']; ?></textarea></p>
                            <div class="submit-button"><input class="btn btn-primary btn-block" type="submit" value="Submit" /></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

