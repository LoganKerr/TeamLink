<?php
    // requires config file
    require_once(dirname(__FILE__).'/../config/config.php');
    
    echo "Account signup script\n";
    $handle = fopen ("php://stdin","r");
    echo "Email: ";
    $line = fgets($handle);
    // TODO: check if email is already in use
    /*
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
    */
    $email = trim($line);
    echo "\nUniversity: ";
    $line = fgets($handle);
    // TODO: USE QUERY TO VERIFY UNIVERSITY EXISTS IN TABLE
    $university = trim($line);
    echo "\nRole (student/faculty): ";
    $line = fgets($handle);
    // TODO: USE strcasecmp($var1, $var2) to see if student/faculty typed case insensitive
    $role = trim($line);
    echo "First Name: ";
    $line = fgets($handle);
    $first = trim($line);
    echo "Last Name: ";
    $line = fgets($handle);
    $last = trim($line);
    echo "Password: ";
    $line = fgets($handle);
    $pass = trim($line);
    $hash = password_hash($pass, PASSWORD_DEFAULT);
    // TODO: ONLY ASK DEPARTMENT/MAJOR/INTERESTS DEPENDING ON ROLE
    // TODO: MAYBE CHANGE TO LOOP THROUGH ARRAY OR DATABASE FOR WHAT TO ASK
    echo "Department: ";
    $line = fgets($handle);
    $department = trim($line);
    echo "Major: ";
    $line = fgets($handle);
    $major = trim($line);
    echo "Interests: ";
    $line = fgets($handle);
    $interests = trim($line);
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
    ?>

