<?php

session_start();

require_once("config/config.php");
require_once("vendor/autoload.php");

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
			$error[$value] = "This field is required.";
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
            $error['email'] = " Email is already in use.";
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
		// $error['hostname'] .= " The hostname is the name configured in the operating system.&nbsp; For VMs, it is usually the DNS Name field shown in the vSphere client.";
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
                echo "Registration error: ".$stmt->error;
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
                    echo "Registration error: ".$stmt->error;
                }
            }
        }
        elseif ($role == "Faculty")
        {
            $stmt = $conn->prepare("INSERT INTO `faculty` (`department`) VALUES (?)");
            $stmt->bind_param("s", $department);
            if (!$stmt->execute())
            {
                echo "Registration error: ".$stmt->error;
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
                    echo "Registration error: ".$stmt->error;
                }
            }
        }
	}
}

    $loader = new Twig_Loader_Filesystem('resources/views');
    $twig = new Twig_Environment($loader);
    
    echo $twig->render('signup.html', array(
                                            'email' => $email,
                                            'university' => $university,
                                            'roles' => $roles,
                                            'firstName' => $firstName,
                                            'lastName' => $lastName,
                                            'department' => $department,
                                            'major' => $major,
                                            'interests' => $interests,
                                            'error' => $error
                       ));
    
?>
