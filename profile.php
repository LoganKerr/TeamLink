<?php
    session_start();
    ob_start();
    header('Content-Type: text/html; charset=utf-8');
    
    require_once("config/config.php");
    
    // if user is not signed in
    if (!isset($_SESSION['user_id']))
    {
        header("Location: index.php");
    }
    
    $user_id = $_SESSION['user_id'];
    
    // profile form submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $error = array();
        // validate data -----------------------
        // escape data
        $department = mysqli_real_escape_string($conn, $_POST['department']);
        $major = mysqli_real_escape_string($conn, $_POST['major']);
        $interests = mysqli_real_escape_string($conn, $_POST['interests']);
        
        
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
            
            if (mysqli_query($conn, $query))
            {
                if (mysqli_query($conn, $query2))
                {
                    echo "<p><strong>Changes have been saved.</strong></p>";
                }
                else
                {
                    echo "<strong>Changes could not be saved: ".mysqli_error($conn)."</strong>";
                }
            } else {
                echo "<strong>Changes could not be saved: ".mysqli_error($conn)."</strong>";
            }
        }
    }
    
    $query = "SELECT `student_id`, `faculty_id`, `major`, `interests`, `department` FROM `users` LEFT JOIN `faculty` ON `users`.`faculty_id`=`faculty`.`id` LEFT JOIN `students` ON `users`.`student_id`=`students`.`id` WHERE `users`.`id`=$user_id";
    
    $res = mysqli_query($conn, $query);
    if (mysqli_num_rows($res) == 0)
    {
        die("User not found.");
    }
    $row = mysqli_fetch_assoc($res);
?>
<?php include "resources/templates/header.php"; ?>
<?php include "resources/templates/navbar.php"; ?>
<body>
    <div id="signup-panel" class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">Change your information</div>   
            <div class="panel-body">
                <form method="post" action="/profile.php">
                    <?php if ($row['faculty_id']) { ?>
                    <p><label>Department:</label><input class="textbox" name="department" type="text" value='<?php if (isset($department)) { echo htmlentities($department, ENT_QUOTES); } else { echo htmlentities($row['department'], ENT_QUOTES); } ?>'/>
                    <?php echo(isset($error['department']))?$error['department']:""; ?></p>
                    <?php } ?>
                    <?php if ($row['student_id']) { ?>
                    <p><label>Major:</label><input class="textbox" name="major" type="text" value='<?php if (isset($major)) { echo htmlentities($major, ENT_QUOTES); } else { echo htmlentities($row['major'], ENT_QUOTES); } ?>'/>
                    <?php echo(isset($error['major']))?$error['major']:""; ?></p>
                    <p><label>Interests:</label><textarea name="interests" rows="4" cols="50" ><?php if (isset($interests)) { echo htmlentities($interests, ENT_QUOTES); } else { echo htmlentities($row['interests'], ENT_QUOTES); } ?></textarea>
                    <?php echo(isset($error['interests']))?$error['interests']:""; ?></p>
                    <?php } ?>
                    <div class="submit-button"><input class="btn btn-primary btn-block" type="submit" value="Submit" /></div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
