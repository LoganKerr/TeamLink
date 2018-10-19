<?php
    
    session_start();
    require_once("config/config.php");
    require_once("functions.php");
    require_once("vendor/autoload.php");
    
    // if user not signed in
    if (!isset($_SESSION['user_id']))
    {
        header("Location: index.php");
    }
    
    $user_id = $_SESSION['user_id'];
    $id = $_GET['id'];
    $stmt = $conn->prepare("
        SELECT `teams`.`owner`, `role_assoc`.`user_id` FROM `teams` 
        LEFT JOIN `role_assoc` ON `teams`.`id`=`role_assoc`.`team_id` 
        WHERE (`teams`.`owner`=? OR `role_assoc`.`user_id`=?) AND `teams`.`id`=?");
    $stmt->bind_param("iii", $user_id, $user_id, $id);
    $stmt->execute();
    $res = $stmt->get_result();
    // if user is not associated with team
    if ($res->num_rows == 0)
    {
        header("Location: myteams.php");
        exit();
    }
    
    // edit form submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $error = array();
        $warning = array();
        // validate data -----------------------------------
        // check empty fields
        $required = array("title", "description");
        $error = set_error_on_empty_required_fields($_POST, $required, $error);
        // escape data
        $id = $_GET['id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        
        if (!empty($title))
        {
            // TODO: validate title (same as createam)
        }
        
        if (!empty($description))
        {
            // TODO: validate description (same as createteam)
        }

        //Validates team exists and user submitting request has permission to edit it
        $stmt = $conn->prepare("
        SELECT `teams`.`owner`, `role_assoc`.`user_id` FROM `teams` 
        LEFT JOIN `role_assoc` ON `teams`.`id`=`role_assoc`.`team_id` 
        WHERE (`teams`.`owner`=? OR `role_assoc`.`user_id`=?) AND `teams`.`id`=?");
        $stmt->bind_param("iii", $user_id, $user_id, $id);
        $stmt->execute();
        $res = $stmt->get_result();
        // if user is not associated with team or team does not exist
        if ($res->num_rows == 0)
        {
            $error['page'] = "Invalid permission";
        }

        
        if (count($error) == 0)
        {
            // updates team's title and description
            $stmt = $conn->prepare("UPDATE `teams` SET `title`=?, `description`=? WHERE `id`=?");
            $stmt->bind_param("ssi", $title, $description, $id);
            if (!$stmt->execute())
            {
                $error['sql'] = $stmt->error;
            }
            // updates new/removed roles/users
            // for each role field
            foreach ($_POST as $key => $value)
            {
                // updates user assigned to role
                if (substr($key, 0, 9) == "role_user")
                {
                    $role_id = filter_var(substr($key, 9), FILTER_SANITIZE_NUMBER_INT);
                    $user_id = (int)$value;
                    $stmt = $conn->prepare("UPDATE `role_assoc` SET `selected`=0 WHERE `team_id`=? AND `role_id`=?");
                    $stmt->bind_param("ii", $id, $role_id);
                    $stmt->execute();
                    $stmt = $conn->prepare("UPDATE `role_assoc` SET `selected`=1 WHERE `team_id`=? AND `role_id`=? AND `user_id`=?");
                    $stmt->bind_param("iii", $id, $role_id, $user_id);
                    $stmt->execute();
                }
                // updates role name if changed
                else if (substr($key, 0, 9) == "role_name")
                {
                    $role_id = filter_var(substr($key, 9), FILTER_SANITIZE_NUMBER_INT);
                    $stmt = $conn->prepare("UPDATE `roles` SET `role`=? WHERE `id`=?");
                    $stmt->bind_param("si", $value, $role_id);
                    $stmt->execute();

                }
                // adds new role to team
                else if (substr($key, 0, 8) == "role_new")
                {
                    if ($value != "")
                    {
                        // inserts new goal into goals table with name and empty text
                        $stmt = $conn->prepare("INSERT INTO `roles` (role, team_id) VALUES (?, ?)");
                        $stmt->bind_param("si", $value, $id);
                        $stmt->execute();
                        $new_goal_id = $conn->insert_id;
                        // inserts new goal into role_assoc for user who posted request
                        $stmt = $conn->prepare("INSERT INTO `role_assoc` (user_id, role_id) VALUES (?, ?)");
                        $stmt->bind_param("ii", $user_id, $new_role_id);
                        $stmt->execute();
                    }
                }
                // removes roles that a user has requested removal of
                else if (substr($key, 0, 11) == "role_remove")
                {
                    $role_id = filter_var(substr($key, 11), FILTER_SANITIZE_NUMBER_INT);
                    // deletes role from role_assoc
                    $stmt = $conn->prepare("DELETE FROM `role_assoc` WHERE `role_id`=?");
                    $stmt->bind_param("i", $role_id);
                    $stmt->execute();
                    // deletes role from row
                    $stmt = $conn->prepare("DELETE FROM `roles` WHERE `id`=?");
                    $stmt->bind_param("i", $role_id);
                    $stmt->execute();
                }
            }
        }
    }
    
    $stmt = $conn->prepare("SELECT `title`, `description` FROM `teams` WHERE `id`=?");
    // SELECT teams.`title`, teams.`description`, role_assoc.`role`, users.`firstName`, users.`lastName` FROM `teams` INNER JOIN `role_assoc` ON teams.`id`=role_assoc.`team_id`  LEFT JOIN `users` ON role_assoc.`user_id`=users.`id` where teams.`id`=13
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    
    if ($res->num_rows == 0)
    {
        die("Team not found.");
    }
    
    //$stmt2 = $conn->prepare("SELECT `role_assoc`.`role_id`, `role_assoc`.`user_id`, `role_assoc`.`selected`, roles.`role`, users.`firstName`, users.`lastName` FROM `role_assoc` LEFT JOIN `users` ON role_assoc.`user_id`=users.`id` LEFT JOIN `roles` ON `role_assoc`.`role_id`=`roles`.`id` WHERE roles.`role`!='Owner' && role_assoc.`team_id`=? ORDER BY `role_assoc`.`role_id`");
    $stmt2 = $conn->prepare("SELECT `roles`.`role`, `roles`.`team_id`, `roles`.`id` AS `role_id`, `role_assoc`.`user_id`, `role_assoc`.`selected`, users.`firstName`, users.`lastName` FROM `roles` LEFT JOIN `role_assoc` ON `roles`.`id` = `role_assoc`.`role_id` LEFT JOIN `users` ON role_assoc.`user_id`=users.`id` WHERE `roles`.`team_id`=?");
    $stmt2->bind_param("i", $id);
    $stmt2->execute();
    $res2 = $stmt2->get_result();
    
    $row = $res->fetch_assoc();
    
    $role_ids = array();
    $i = 0;
    $rows2 = array();
    while ($row2 = $res2->fetch_assoc())
    {
        $rows2[$i] = $row2;
        $i++;
    }
    $length = count($rows2);
    
    $loader = new Twig_Loader_Filesystem('resources/views');
    $twig = new Twig_Environment($loader);
    
    $admin = check_if_user_is_admin($_SESSION['user_id']);
    
    echo $twig->render('editteam.html.twig', array(
                                              'nav' => array('page' => $_SERVER['PHP_SELF'], 'admin' => $admin),
                                              'request_method' => $_SERVER['REQUEST_METHOD'],
                                              'error' => (isset($error)? $error : array()),
                                              'team_id' => $_GET['id'],
                                              'title' => ((isset($title))? $title : $row['title']),
                                              'description' => ((isset($description))? $description : $row['description']),
                                              'rows2' => $rows2,
                                              'length' => $length
                                             ));
?>
