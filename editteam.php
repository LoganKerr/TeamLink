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
    $stmt = $conn->prepare("SELECT `user_id` FROM `role_assoc` WHERE `user_id`=? AND `team_id`=?");
    $stmt->bind_param("ii", $user_id, $id);
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
        foreach ($required as $key => $value)
        {
            if(!isset($_POST[$value]) || empty($_POST[$value]) && $_POST[$value] != '0')
            {
                $error[$value] = "<strong>This field is required.</strong>";
            }
         }
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
        
        // TODO: validate team and user adding
        //var_dump($_POST);
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
        }
        
        if (count($error) == 0)
        {
            $stmt = $conn->prepare("UPDATE `teams` SET `title`=?, `description`=? WHERE `id`=?");
            $stmt->bind_param("ssi", $title, $description, $id);
            if ($stmt->execute())
            {
                echo "Changes saved.";
            }
            else
            {
                echo "Error: ".$stmt->error;
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
    
    $stmt2 = $conn->prepare("SELECT `role_assoc`.`role_id`, `role_assoc`.`user_id`, `role_assoc`.`selected`, roles.`role`, users.`firstName`, users.`lastName` FROM `role_assoc` LEFT JOIN `users` ON role_assoc.`user_id`=users.`id` LEFT JOIN `roles` ON `role_assoc`.`role_id`=`roles`.`id` WHERE roles.`role`!='Owner' && role_assoc.`team_id`=? ORDER BY `role_assoc`.`role_id`");
    $stmt2->bind_param("i", $id);
    $stmt2->execute();
    $res2 = $stmt2->get_result();
    
    $row = $res->fetch_assoc();
    
    
    
    
    $role_ids = array();
    $i = 0;
    while ($row2 = $res2->fetch_assoc())
    {
        $rows2[$i] = $row2;
        $i++;
    }
    $length = count($rows2);
    
    
    
    $loader = new Twig_Loader_Filesystem('resources/views');
    $twig = new Twig_Environment($loader);
    
    $admin = check_if_user_is_admin($_SESSION['user_id']);
    
    echo $twig->render('editteam.html', array(
                                              'nav' => array('page' => $_SERVER['PHP_SELF'], 'admin' => $admin),
                                              'error' => $error,
                                              'user_id' => $_GET['id'],
                                              'title' => ((isset($title))? $title : $row['title']),
                                              'description' => ((isset($description))? $description : $row['description']),
                                              'rows2' => $rows2,
                                              'length' => $length
                                             ));
?>
