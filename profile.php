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
        // escape data
        $department = mysqli_real_escape_string($link, $_POST['department']);
        $major = mysqli_real_escape_string($link, $_POST['major']);
        $interests = mysqli_real_escape_string($link, $_POST['interests']);
        
        
        if (!empty($department))
        {
            // TODO: validate department (same as signup)
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
            $query = "UPDATE `students` SET `major`='".$major."', `interests`='".$interests."' WHERE `id`=(SELECT `student_id` FROM `users` WHERE `id`=$user_id);";
            $query2 = "UPDATE `faculty` SET `department`='".$department."' WHERE `id`=(SELECT `faculty_id` FROM `users` WHERE `id`=$user_id);";
            
            if (mysqli_query($link, $query))
            {
                if (mysqli_query($link, $query2))
                {
                    echo "<p><strong>Changes have been saved.</strong></p>";
                }
                else
                {
                    echo "<strong>Changes could not be saved: ".mysqli_error($link)."</strong>";
                }
            } else {
                echo "<strong>Changes could not be saved: ".mysqli_error($link)."</strong>";
            }
        }
    }
    
    $query = "SELECT `student_id`, `faculty_id`, `major`, `interests`, `department` FROM `users` LEFT JOIN `faculty` ON `users`.`faculty_id`=`faculty`.`id` LEFT JOIN `students` ON `users`.`student_id`=`students`.`id` WHERE `users`.`id`=$user_id";
    
    $res = mysqli_query($link, $query);
    if (mysqli_num_rows($res) == 0)
    {
        die("User not found.");
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
                    <?php if ($row['faculty_id']) { ?>
                        <p><label>Department:</label><input class="textbox" name="department" type="text" value='<?php echo $row['department'] ?>'/></p>
                    <?php } ?>
                    <?php if ($row['student_id']) { ?>
                    <p><label>Major:</label><input class="textbox" name="major" type="text" value='<?php echo $row['major'] ?>'/></p>
                    <p><label>Interests:</label><textarea name="interests" rows="4" cols="50" ><?php echo $row['interests'] ?></textarea></p>
                    <?php } ?>
                    <div class="submit-button"><input class="btn btn-primary btn-block" type="submit" value="Submit" /></div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
