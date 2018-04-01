<?php

session_start();
require_once("config/config.php");
if (isset($_SESSION['user_id']))
{
    header("Location: menu.php");
}

// possible signup roles
$roles = array("Student", "Faculty");

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
	$email = $_POST['email'];
    $university = $_POST['university'];
    $role = $_POST['role'];
	$firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $pass1 = $_POST['pass1'];
    $pass2 = $_POST['pass2'];
    $department = $_POST['department'];
    $major = $_POST['major'];
    $interests = $_POST['interests'];
	
	if (!empty($email))
	{
		// TODO: validate email with regex
		// validate email is not already in use
		$stmt = $conn->prepare("SELECT `email` FROM `users` WHERE `email`=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();
        
        if ($res->num_rows != 0) {
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
        
        if ($role == "Student")
        {
            $stmt = $conn->prepare("INSERT INTO `students` (`major`, `interests`) VALUES (?, ?)");
            $stmt->bind_param("ss", $major, $interests);
            if (!$stmt->execute())
            {
                echo "<p><strong>Registration error: ".$stmt->error."</strong></p>";
            }
            else
            {
                $student_id = $stmt->insert_id;
                $stmt = $conn->prepare("INSERT INTO `users` (`email`, `university_id`, `firstName`, `lastName`, `passHash`, `student_id`) VALUES (?, (SELECT `id` FROM `universities` WHERE `title`=?), ?, ?, ?, ?)");
                $stmt->bind_param("sssssi", $email, $university, $firstName, $lastName, $hash, $student_id);
                
                if ($stmt->execute())
                {
                    header("Location: index.php");
                    exit();
                }
                else
                {
                    echo "<strong>Registration error: ".$stmt->error."</strong>";
                }
            }
        }
        elseif ($role == "Faculty")
        {
            $stmt = $conn->prepare("INSERT INTO `faculty` (`department`) VALUES (?)");
            $stmt->bind_param("s", $department);
            if (!$stmt->execute())
            {
                echo "<p><strong>Registration error: ".$stmt->error."</strong></p>";
            }
            else
            {
                $faculty_id = $conn->insert_id;
                $stmt = $conn->prepare("INSERT INTO `users` (`email`, `university_id`, `firstName`, `lastName`, `passHash`, `faculty_id`) VALUES (?, (SELECT `id` FROM `universities` WHERE `title`=?), ?, ?, ?, ?)");
                $stmt->bind_param("sssssi", $email, $firstName, $lastName, $hash, $faculty_id);
                
                if ($stmt->execute())
                {
                    header("Location: index.php");
                    exit();
                }
                else
                {
                    echo "<strong>Registration error: ".$stmt->error."</strong>";
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
            <div class="panel-heading">
                <a href="/index.php" class="btn btn-default btn-sm"><</a>
                Enter your information</div>
            <div class="panel-body">
                <form method="post" action="/signup.php">
                    <p><label>Email:</label><input class="textbox" name="email" type="text" value='<?php if (isset($email)) { echo htmlentities($email, ENT_QUOTES); } ?>'/>
					<?php echo(isset($error['email']))?$error['email']:""; ?></p>
                    <p><label>University:</label><input class="textbox" name="university" type="text" value='<?php if (isset($university)) { echo htmlentities($university, ENT_QUOTES); } ?>'/>
                    <?php echo(isset($error['university']))?$error['university']:""; ?></p>
                    <p><label>Role:</label><select name="role">
                        <?php
                        foreach ($roles as $key => $value)
                        {
                            echo "<option ".((isset($role) && $value==$role)? "selected" : "").">$value</option>";
                        }
                    ?>
                    </select></p>
                    <?php echo(isset($error['role']))?$error['role']:""; ?></p>
                    <p><label>First Name:</label><input class="textbox" name="firstName" type="text" value='<?php if (isset($firstName)) { echo htmlentities($firstName, ENT_QUOTES); } ?>'/>
					<?php echo(isset($error['firstName']))?$error['firstName']:""; ?></p>
					<p><label>Last Name:</label><input class="textbox" name="lastName" type="text" value='<?php if (isset($lastName)) { echo htmlentities($lastName, ENT_QUOTES); } ?>'/>
					<?php echo(isset($error['lastName']))?$error['lastName']:""; ?></p>
                    <p><label>Password:</label><input class="textbox" name="pass1" type="password" />
					<?php echo(isset($error['pass2']))?$error['pass1']:""; ?></p>
                    <p><label>Confirm password:</label><input class="textbox" name="pass2" type="password" />
					<?php echo(isset($error['pass2']))?$error['pass2']:""; ?></p>
                    <p><label>Department:</label><input class="textbox" name="department" type="text" value='<?php if (isset($department)) { echo htmlentities($department, ENT_QUOTES); } ?>'/>
                    <?php echo(isset($error['department']))?$error['department']:""; ?></p>
                    <p><label>Major:</label><input class="textbox" name="major" type="text" value='<?php if (isset($major)) { echo htmlentities($major, ENT_QUOTES); } ?>'/>
					<?php echo(isset($error['major']))?$error['major']:""; ?></p>
                    <p><label>Interests:</label><textarea name="interests" rows="4" cols="50"><?php if ($interests) { echo htmlentities($interests, ENT_QUOTES); } ?></textarea>
					<?php echo(isset($error['interests']))?$error['interests']:""; ?></p>
                    <div class="submit-button"><input class="btn btn-primary btn-block" type="submit" value="Submit" /></div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
