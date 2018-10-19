<?php
    // requires config file
    require_once(dirname(__FILE__).'/../config/config.php');

    // not done yet
    $done = false;

    echo "Account signup script\n";
    $handle = fopen("php://stdin", "r");

    // breaks out of loop if any errors
    while (!$done) {
        echo "Email: ";
        $line = fgets($handle);
        $email = trim($line);
        // validate email is not empty
        if (empty($email)) {
            echo "ERROR: Email cannot be empty.";
            break;
        }
        // TODO: validate email with regex
        // validate email is not already in use
        $stmt = $conn->prepare("SELECT `email` FROM `users` WHERE `email`=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows != 0) {
            echo "ERROR: Email is already in use.";
            break;
        }
        echo "University: ";
        $line = fgets($handle);
        $university = trim($line);
        // validate university in database
        $stmt = $conn->prepare("SELECT `id` FROM `universities` WHERE `title`=?");
        $stmt->bind_param("s", $university);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows == 0) {
            echo "ERROR: University not found";
            break;
        }
        echo "Role (student/faculty): ";
        $line = fgets($handle);
        $role = trim($line);
        // forces lowercase
        $role = strtolower($role);
        if($role != "student" && $role != "faculty")
        {
            echo "ERROR: Invalid role";
            break;
        }
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
        if ($role == "Student") {
            $stmt = $conn->prepare("INSERT INTO `students` (`major`, `interests`) VALUES (?, ?)");
            $stmt->bind_param("ss", $major, $interests);
            if (!$stmt->execute()) {
                echo "Registration error: " . $stmt->error;
            } else {
                $student_id = $stmt->insert_id;
                $stmt = $conn->prepare("INSERT INTO `users` (`email`, `university_id`, `firstName`, `lastName`, `passHash`, `student_id`) VALUES (?, (SELECT `id` FROM `universities` WHERE `title`=?), ?, ?, ?, ?)");
                $stmt->bind_param("sssssi", $email, $university, $firstName, $lastName, $hash, $student_id);

                if ($stmt->execute()) {
                    header("Location: index.php");
                    exit();
                } else {
                    echo "Registration error: " . $stmt->error;
                }
            }
        } elseif ($role == "Faculty") {
            $stmt = $conn->prepare("INSERT INTO `faculty` (`department`) VALUES (?)");
            $stmt->bind_param("s", $department);
            if (!$stmt->execute()) {
                echo "Registration error: " . $stmt->error;
            } else {
                $faculty_id = $conn->insert_id;
                $stmt = $conn->prepare("INSERT INTO `users` (`email`, `university_id`, `firstName`, `lastName`, `passHash`, `faculty_id`) VALUES (?, (SELECT `id` FROM `universities` WHERE `title`=?), ?, ?, ?, ?)");
                $stmt->bind_param("sssssi", $email, $firstName, $lastName, $hash, $faculty_id);

                if ($stmt->execute()) {
                    header("Location: index.php");
                    exit();
                } else {
                    echo "Registration error: " . $stmt->error;
                }
            }
        }
    }
    fclose($handle);
    ?>

