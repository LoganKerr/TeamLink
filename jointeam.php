<?php
    session_start();
    ob_start();
    
    require_once("config/config.php");
    require_once("functions.php");
    require_once("vendor/autoload.php");
    
    // if user not signed in
    if (!isset($_SESSION['user_id']))
    {
        header("Location: index.php");
    }
    
    $user_id = $_SESSION['user_id'];
    
    // User has applied for a role
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $error = array();
        // for each team field
        foreach ($_POST as $key => $value)
        {
            // team is being deleted
            if ($key == "role")
            {
                $role_id = (int)$value;
                // checks if role exists
                $stmt = $conn->prepare("SELECT COUNT(`roles`.`id`) AS `count`, `roles`.`team_id` FROM `roles` WHERE `roles`.`id`=?");
                $stmt->bind_param("i", $role_id);
                // statement executed
                if ($stmt->execute())
                {
                    $res = $stmt->get_result();
                    $row = $res->fetch_assoc();
                    // if exactly one role has been found for role id
                    if ($row && $row['count'] == 1)
                    {
                        $team_id = $row['team_id'];
                        $stmt = $conn->prepare("SELECT `teams`.`owner` FROM `teams` WHERE `teams`.`id`=?");
                        $stmt->bind_param("i", $team_id);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        $row = $res->fetch_assoc();
                        $owner_id = $row['owner'];
                        $stmt = $conn->prepare("SELECT `users`.`id`, `users`.`university_id` FROM `users` WHERE `users`.`id`=? OR `users`.`id`=?");
                        $stmt->bind_param("ii", $user_id, $owner_id);
                        // execute statement
                        if ($stmt->execute())
                        {
                            $res = $stmt->get_result();
                            $same_university = false;
                            // owner is same as user
                            if (mysqli_num_rows($res) == 1)
                            {
                                $same_university = true;
                            }
                            else {
                                // get university of user id
                                $row = $res->fetch_assoc();
                                $user_university_id = $row['university_id'];
                                // get university of owner id
                                $row = $res->fetch_assoc();
                                $owner_university_id = $row['university_id'];
                                // owner and user are from same university
                                $same_university = $user_university_id == $owner_university_id;
                            }
                            if ($same_university)
                            {
                                $stmt = $conn->prepare("SELECT COUNT(`role_assoc`.`user_id`) AS `count` FROM `role_assoc` WHERE `role_assoc`.`role_id`=? AND `role_assoc`.`user_id`=?");
                                $stmt->bind_param("ii", $role_id, $user_id);
                                if ($stmt->execute())
                                {
                                    $res = $stmt->get_result();
                                    $row = $res->fetch_assoc();
                                    // if user has not already applied for role
                                    if ($row['count'] == 0)
                                    {
                                        // sets role_assoc row for role
                                        $stmt = $conn->prepare("INSERT INTO `role_assoc` (user_id, team_id, role_id) VALUES (?, ?, ?)");
                                        $stmt->bind_param("iii", $user_id, $team_id, $role_id);
                                        if (!$stmt->execute())
                                        {
                                            $error['sql'] = $stmt->error;
                                        }
                                    }
                                    else
                                    {
                                        $error['role'] = "Application has already been submitted for this role.";
                                    }
                                }
                            }
                            // user applied to a role that belongs to a team outside his/her university
                            else
                            {
                                $error['role'] = "Invalid role";
                            }
                        }
                    }
                    // more or less than one role found for role id
                    else
                    {
                        //$error['role'] = "test";
                        $num_roles = $row['count'];
                        $error['role'] = "Number of roles found: $num_roles";
                    }
                }
            }
        }
    }
    
    // sets search to default value
    $search = (($_GET['search'])? : '');
    $search_wildcard = "%".$search."%";
    // get teams that are similar to searched value
    //$stmt = $conn->prepare("SELECT `firstName`, `lastName`, `title`, `description` FROM `teams` INNER JOIN `users` ON teams.`owner`=users.`id` WHERE `public` AND (`title` LIKE ? OR `description` LIKE ? OR CONCAT(`firstName`, ' ', `lastName`) LIKE ?)");
    $stmt = $conn->prepare("SELECT `users`.`university_id` FROM `users` WHERE `users`.`id`=?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $university_id = $row['university_id'];
    $stmt = $conn->prepare("
    SELECT `firstName`, `lastName`, `teams`.`title`, `teams`.`description`, `roles`.`id`, `roles`.`role` FROM `roles` 
    INNER JOIN `teams` ON `roles`.`team_id`=`teams`.`id` 
    INNER JOIN `users` ON `teams`.`owner`=`users`.`id` 
    INNER JOIN `universities` ON `users`.`university_id`=`universities`.`id` 
    WHERE `universities`.`id`=? AND (`teams`.`title` LIKE ? OR `description` LIKE ? OR `role` LIKE ? OR CONCAT(`firstName`, ' ', `lastName`) LIKE ?)");
    $stmt->bind_param("issss", $university_id, $search_wildcard, $search_wildcard, $search_wildcard, $search_wildcard);
    $stmt->execute();
    $res = $stmt->get_result();
    $i = 0;
    $rows = array();
    while ($row = $res->fetch_assoc())
    {
        $rows[$i] = $row;
        $i++;
    }
    $length = count($rows);
    
    $loader = new Twig_Loader_Filesystem('resources/views');
    $twig = new Twig_Environment($loader);
    
    $admin = check_if_user_is_admin($_SESSION['user_id']);
    
    echo $twig->render('jointeam.html.twig', array(
                                              'nav' => array('page' => $_SERVER['PHP_SELF'], 'admin' => $admin),
                                              'request_method' => $_SERVER['REQUEST_METHOD'],
                                              'error' => (isset($error)? $error : array()),
                                              'rows' => $rows,
                                              'length' => $length,
                                              'search' => ((isset($search))? $search : "")
                                             ));
    
?>
