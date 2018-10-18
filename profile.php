<?php
    session_start();
    ob_start();
    header('Content-Type: text/html; charset=utf-8');
    
    require_once("config/config.php");
    require_once("functions.php");
    require_once("vendor/autoload.php");
    
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
        $department = $_POST['department'];
        $major = $_POST['major'];
        $interests = $_POST['interests'];
        
        
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
            //$query = "UPDATE `students` SET `major`='".$major."', `interests`='".$interests."' WHERE `id`=(SELECT `student_id` FROM `users` WHERE `id`=$user_id);";
            $stmt = $conn->prepare("UPDATE `students` SET `major`=?, `interests`=? WHERE `id`=(SELECT `student_id` FROM `users` WHERE `id`=?);");
            $stmt->bind_param("ssi", $major, $interests, $user_id);
            
            //$query2 = "UPDATE `faculty` SET `department`='".$department."' WHERE `id`=(SELECT `faculty_id` FROM `users` WHERE `id`=$user_id);";
            $stmt2 = $conn->prepare("UPDATE `faculty` SET `department`=? WHERE `id`=(SELECT `faculty_id` FROM `users` WHERE `id`=?);");
            $stmt2->bind_param("si", $department, $user_id);
            
            //if (mysqli_query($conn, $stmt))
            if ($stmt->execute())
            {
                if (!$stmt2->execute())
                {
                    $error['sql'] = $stmt2->error;
                }
            } else {
                $error['sql'] = $stmt->error;
            }
        }
    }
    
    $stmt = $conn->prepare("SELECT `student_id`, `faculty_id`, `major`, `interests`, `department` FROM `users` LEFT JOIN `faculty` ON `users`.`faculty_id`=`faculty`.`id` LEFT JOIN `students` ON `users`.`student_id`=`students`.`id` WHERE `users`.`id`=?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows == 0)
    {
        die("User not found.");
    }
    $row = $res->fetch_assoc();
    
    $loader = new Twig_Loader_Filesystem('resources/views');
    $twig = new Twig_Environment($loader);
    
    $admin = check_if_user_is_admin($_SESSION['user_id']);
    
    echo $twig->render('profile.html.twig', array(
                                             'nav' => array('page' => $_SERVER['PHP_SELF'], 'admin' => $admin),
                                             'request_method' => $_SERVER['REQUEST_METHOD'],
                                             'error' => $error,
                                             'faculty_id' => $row['faculty_id'],
                                             'department' => ((isset($department))? $department : $row['department']),
                                             'student_id' => $row['student_id'],
                                             'major' => ((isset($major))? $major : $row['major']),
                                             'interests' => ((isset($interests))? $interests : $row['interests'])
                                          ));
?>
