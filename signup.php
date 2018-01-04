<?php

session_start();
require_once("config/config.php");
if (isset($_SESSION['user_id']))
{
    header("Location: menu.php");
}

// signup form submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$error = array();
	$warning = array();
	// validate data -----------------------------------
	// check empty fields
	$required = array("email", "firstName", "lastName", "pass1", "pass2", "university", "role");
	foreach ($required as $key => $value)
	{
		if(!isset($_POST[$value]) || empty($_POST[$value]) && $_POST[$value] != '0')
		{
			$error[$value] = "<strong>This field is required.</strong>";
		}
	}
	// escape data
	$email = mysqli_real_escape_string($conn, $_POST['email']);
    $university = mysqli_real_escape_string($conn, $_POST['university']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
	$firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
	$lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
	$pass1 = mysqli_real_escape_string($conn, $_POST['pass1']);
	$pass2 = mysqli_real_escape_string($conn, $_POST['pass2']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
	$major = mysqli_real_escape_string($conn, $_POST['major']);
	$interests = mysqli_real_escape_string($conn, $_POST['interests']);
	
	if (!empty($email))
	{
		// TODO: validate email
		// TODO: validate email is not already in use
		$query = "SELECT `email` FROM `users` WHERE `email`='".$email."'";
        $res = mysqli_query($conn, $query);
        if (mysqli_num_rows($res) >= 1) {
            $error['email'] = " <strong>Email is already in use.</strong>";
        }
	}
    
    if (!empty($university))
    {
        // TODO: validate university
    }
    
    if (!empty($role))
    {
        // TODO: validate role
    }
	
	if (!empty($firstName))
	{
		// TODO: validate first name
	}
	
	if (!empty($lastName))
	{
		// TODO: validate last name
	}
	
	if (!empty($pass1))
	{
		// $error['hostname'] .= " <strong>The hostname is the name configured in the operating system.&nbsp; For VMs, it is usually the DNS Name field shown in the vSphere client.</strong>";
		// TODO: validate password
	}
	
	if (!empty($pass2))
	{
		// TODO: validate passwod confirm equals password
	}
	
	if (!empty($major))
	{
		// TODO: validate major
	}
	
	if (!empty($interests))
	{
		// TODO: validate interests
	}
		
	if (count($error) == 0)
	{
		$hash = password_hash($pass1, PASSWORD_DEFAULT);
		// insert row into database
        
        if ($role == "student")
        {
            $query = "INSERT INTO `students` (`major`, `interests`) VALUES ('".$major."', '".$interests."')";
            if (!mysqli_query($conn, $query))
            {
                echo "<p><strong>Registration error: ".mysqli_error($conn)."</strong></p>";
            }
            else
            {
                $student_id = $conn->insert_id;
                $query = "INSERT INTO `users` (`email`, `university_id`, `firstName`, `lastName`, `passHash`, `student_id`) VALUES ('".$email."', (SELECT `id` FROM `universities` WHERE `title`='".$university."'), '".$firstName."', '".$lastName."', '".$hash."', '".$student_id."' )";
                
                if (mysqli_query($conn, $query))
                {
                    header("Location: index.php");
                    exit();
                }
                else
                {
                    echo "<strong>Registration error: ".mysqli_error($conn)."</strong>";
                }
            }
        }
        elseif ($role == "faculty")
        {
            $query = "INSERT INTO `faculty` (`department`) VALUES ('".$department."')";
            if (!mysqli_query($conn, $query))
            {
                echo "<p><strong>Registration error: ".mysqli_error($conn)."</strong></p>";
            }
            else
            {
                $faculty_id = $conn->insert_id;
                $query = "INSERT INTO `users` (`email`, `university_id`, `firstName`, `lastName`, `passHash`, `faculty_id`) VALUES ('".$email."', (SELECT `id` FROM `universities` WHERE `title`='".$university."'), '".$firstName."', '".$lastName."', '".$hash."', '".$faculty_id."' )";
                
                if (mysqli_query($conn, $query))
                {
                    header("Location: index.php");
                    exit();
                }
                else
                {
                    echo "<strong>Registration error: ".mysqli_error($conn)."</strong>";
                }
            }
        }
	}
}
?>
<?php include "resources/templates/header.php"; ?>
<body>
    <div id="signup-panel" class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">Enter your information</div>   
            <div class="panel-body">
                <form method="post" action="/signup.php">
                    <p><label>Email:</label><input class="textbox" name="email" type="text" />
					<?php echo(isset($error['email']))?$error['email']:""; ?></p>
                    <p><label>University:</label><input class="textbox" name="university" type="text" />
                    <?php echo(isset($error['university']))?$error['university']:""; ?></p>
                    <p><label>Role:</label><input class="textbox" name="role" type="text" />
                    <?php echo(isset($error['role']))?$error['role']:""; ?></p>
                    <p><label>First Name:</label><input class="textbox" name="firstName" type="text" />
					<?php echo(isset($error['firstName']))?$error['firstName']:""; ?></p>
					<p><label>Last Name:</label><input class="textbox" name="lastName" type="text" />
					<?php echo(isset($error['lastName']))?$error['lastName']:""; ?></p>
                    <p><label>Password:</label><input class="textbox" name="pass1" type="password" />
					<?php echo(isset($error['pass2']))?$error['pass1']:""; ?></p>
                    <p><label>Confirm password:</label><input class="textbox" name="pass2" type="password" />
					<?php echo(isset($error['pass2']))?$error['pass2']:""; ?></p>
                    <p><label>Department:</label><input class="textbox" name="department" type="text" />
                    <?php echo(isset($error['department']))?$error['department']:""; ?></p>
                    <p><label>Major:</label><input class="textbox" name="major" type="text" />
					<?php echo(isset($error['major']))?$error['major']:""; ?></p>
                    <p><label>Interests:</label><textarea name="interests" rows="4" cols="50"></textarea>
					<?php echo(isset($error['interests']))?$error['interests']:""; ?></p>
                    <div class="submit-button"><input class="btn btn-primary btn-block" type="submit" value="Submit" /></div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
