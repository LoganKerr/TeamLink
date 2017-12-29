<?php

session_start();
include "config/config.php";
if (isset($_SESSION['user_id']))
{
    header("Location: menu.php");
}

// signup form submitted
if (isset($_POST['signup']))
{
	$error = array();
	$warning = array();
	// validate data -----------------------------------
	// check empty fields
	$required = array("email", "firstName", "lastName", "pass1", "pass2");
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
	$pass1 = mysqli_real_escape_string($link, $_POST['pass1']);
	$pass2 = mysqli_real_escape_string($link, $_POST['pass2']);
	$major = mysqli_real_escape_string($link, $_POST['major']);
	$interests = mysqli_real_escape_string($link, $_POST['interests']);
	
	if (!empty($email))
	{
		// TODO: validate email
		// TODO: validate email is not already in use
		$query = "SELECT `email` FROM `users` WHERE `email`='".$email."'";
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
		$query = "INSERT INTO `users` (`email`, `firstName`, `lastName`, `major`, `interests`, `passHash`) VALUES ('".$email."', '".$firstName."', '".$lastName."', '".$major."', '".$interests."', '".$hash."')";
		if (mysqli_query($link, $query))
		{
			echo "<p><strong>Registration successful.</strong></p>";
		}
		else
		{
			echo "<strong>Registration error: ".mysqli_error($link)."</strong>";
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
    <div id="signup-panel" class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">Enter your information</div>   
            <div class="panel-body">
                <form method="post" action="/signup.php">
                    <input type="hidden" name="signup" value="signup" />
                    <p><label>Email:</label><input class="textbox" name="email" type="text" />
					<?php echo(isset($error['email']))?$error['email']:""; ?></p>
                    <p><label>First Name:</label><input class="textbox" name="firstName" type="text" />
					<?php echo(isset($error['firstName']))?$error['firstName']:""; ?></p>
					<p><label>Last Name:</label><input class="textbox" name="lastName" type="text" />
					<?php echo(isset($error['lastName']))?$error['lastName']:""; ?></p>
                    <p><label>Password:</label><input class="textbox" name="pass1" type="password" />
					<?php echo(isset($error['pass2']))?$error['pass1']:""; ?></p>
                    <p><label>Confirm password:</label><input class="textbox" name="pass2" type="password" />
					<?php echo(isset($error['pass2']))?$error['pass2']:""; ?></p>
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
