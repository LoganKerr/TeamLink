<?php
    // requires config file
    require_once(dirname(__FILE__).'/../config/config.php');

    // not done yet
    $done = false;
    $data = array();

    echo "Account signup script\n";
    $handle = fopen("php://stdin", "r");

    // breaks out of loop if any errors
    while (!$done) {
        echo "Email: ";
        $line = fgets($handle);
        $data["email"] = trim($line);
        // validate email is not empty
        if (empty($data["email"])) {
            echo "ERROR: Email cannot be empty.";
            break;
        }
        // TODO: validate email with regex
        // validate email is not already in use
        $stmt = $conn->prepare("SELECT `email` FROM `users` WHERE `email`=?");
        $stmt->bind_param("s", $data["email"]);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows != 0) {
            echo "ERROR: Email is already in use.";
            break;
        }
        echo "University: ";
        $line = fgets($handle);
        $data["university"] = trim($line);
        // validate university in database
        $stmt = $conn->prepare("SELECT `id` FROM `universities` WHERE LOWER(`title`)=LOWER(?)");
        $stmt->bind_param("s", $data["university"]);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows == 0) {
            echo "ERROR: University not found";
            break;
        }
        $row = $res->fetch_assoc();
        $data["university"] = $row["id"];
        echo "First Name: ";
        $line = fgets($handle);
        $data["firstName"] = trim($line);
        echo "Last Name: ";
        $line = fgets($handle);
        $data["lastName"] = trim($line);
        echo "Password: ";
        $line = fgets($handle);
        $pass = trim($line);
        $data["passhash"] = password_hash($pass, PASSWORD_DEFAULT);
        echo "Role (student/faculty): ";
        $line = fgets($handle);
        $data["role"] = trim($line);
        // forces lowercase
        $data["role"] = strtolower($data["role"]);
        if($data["role"] != "student" && $data["role"] != "faculty")
        {
            echo "ERROR: Invalid role";
            break;
        }
        // faculty specific fields
        if ($data["role"] == "faculty") {
            echo "Department: ";
            $line = fgets($handle);
            $data["department"] = trim($line);
        }
        // student specific fields
        if ($data["role"] == "student") {
            echo "Major: ";
            $line = fgets($handle);
            $data["major"] = trim($line);
            echo "Interests: ";
            $line = fgets($handle);
            $data["interests"] = trim($line);
        }
        $done = true;
    }
    fclose($handle);
    // insert data into database
    if ($done == true)
    {
        if ($data["role"] == "student")
        {
            $stmt = $conn->prepare("INSERT INTO `students` (`major`, `interests`) VALUES (?, ?)");
            $stmt->bind_param("ss", $data["major"], $data["interests"]);
            if (!$stmt->execute()) {
                echo "Registration error: " . $stmt->error;
            } else {
                $student_id = $stmt->insert_id;
                $stmt = $conn->prepare("INSERT INTO `users` (`email`, `university_id`, `firstName`, `lastName`, `passHash`, `student_id`) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssi", $data["email"], $data["university"], $data["firstName"], $data["lastName"], $data["passhash"], $student_id);

                if ($stmt->execute()) {
                    echo "Registration error: " . $stmt->error;
                }
                else
                {
                    echo "Account successfully created";
                }
            }
        } elseif ($data["role"] == "faculty")
        {
            $stmt = $conn->prepare("INSERT INTO `faculty` (`department`) VALUES (?)");
            $stmt->bind_param("s", $data["department"]);
            if (!$stmt->execute()) {
                echo "Registration error: " . $stmt->error;
            } else {
                $faculty_id = $conn->insert_id;
                $stmt = $conn->prepare("INSERT INTO `users` (`email`, `university_id`, `firstName`, `lastName`, `passHash`, `faculty_id`) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssi", $data["email"], $data["university"], $data["firstName"], $data["lastName"], $data["passhash"], $faculty_id);

                if (!$stmt->execute()) {
                    echo "Registration error: " . $stmt->error;
                }
                else
                {
                    echo "Account successfully created";
                }
            }
        }
    }
    ?>

