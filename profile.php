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
    
    // profile form submitted
    if (isset($_POST['profile']))
    {
        $error = array();
        // validate data -----------------------
        // check empty fields
        $required = array("email", "firstName", "lastName");
        foreach ($required as $key => $value)
        {
            if(!isset($_POST[$value]) || empty($_POST[$value]) && $_POST[$value] != '0')
            {
                $error[$value] = "<strong>This field is required.</strong>";
            }
        }
        // escape data
        $email = mysqli_real_escape_string($link, $_POST['email']);
        $firstName = mysqli_real_escape_string($link, $_POST['firstName']);
        $lastName = mysqli_real_escape_string($link, $_POST['lastName']);
        $major = mysqli_real_escape_string($link, $_POST['major']);
        $interests = mysqli_real_escape_string($link, $_POST['interests']);
        
        if (!empty($email))
        {
            // TODO: validate email (same as signup)
        }
        
        if (!empty($firstName))
        {
            // TODO: validate first name (same as signup)
        }
        
        if (!empty($lastName))
        {
            // TODO: validate last name (same as signup)
        }
        
        if (!empty($major))
        {
            // TODO: validate majors (same as signup)
        }
        
        if (!empty($interests))
        {
            // TODO: validate interests (same as signup)
        }
        
        if (count($error) == 0)
        {
            $query = "UPDATE `users` SET `email`='".$email."', `firstName`='".$firstName."', `lastName`='".$lastName."', `major`='".$major."', `interests`='".$interests."' WHERE `id`=$user_id";
            if (mysqli_query($link, $query))
            {
                echo "<p><strong>Changes have been saved.</strong></p>";
            } else {
                echo "<strong>Changes could not be saved: ".mysqli_error($link)."</strong>";
            }
        }
    }
    $query = "SELECT email, firstName, lastName, major, interests FROM `users` WHERE `id`=$user_id";
    $res = mysqli_query($link, $query);
    if (mysqli_num_rows($res) == 0)
    {
        die("User not found");
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
                <li class="active"><a href="/profile.php">Profile</a></li>
                <li><a href="/myteams.php">My Teams</a></li>
                <li><a href="/createteam.php">Create a Team</a></li>
                <li><a href="/jointeam.php">Join a Team</a></li>
            </ul>
        </div>
    </nav>
    <div id="signup-panel" class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">Change your information</div>   
            <div class="panel-body">
                <form method="post" action="/profile.php">
                    <input type="hidden" name="profile" value="profile" />
                    <p><label>Email:</label><input class="textbox" name="email" type="text" value='<?php echo $row['email']; ?>'/></p>
                    <p><label>First Name:</label><input class="textbox" name="firstName" type="text" value='<?php echo $row['firstName']; ?>'/></p>
                    <p><label>Last Name:</label><input class="textbox" name="lastName" type="text" value='<?php echo $row['lastName']; ?>'/></p>
                    <p><label>Major:</label><input class="textbox" name="major" type="text" value='<?php echo $row['major'] ?>'/></p>
                    <p><label>Interests:</label><textarea name="interests" rows="4" cols="50" ><?php echo $row['interests'] ?></textarea></p>
                    <div class="submit-button"><input class="btn btn-primary btn-block" type="submit" value="Submit" /></div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
