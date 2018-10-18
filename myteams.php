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
    
    // myteams form submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $error = array();
        $message = "";
        // for each team field
        foreach ($_POST as $key => $value)
        {
            // team is being deleted
            if ($key == "team")
            {
                $team_id = (int)$value;
                // deletes team from teams table
                $stmt = $conn->prepare("DELETE FROM `teams` WHERE `id`=?");
                $stmt2 = $conn->prepare("DELETE FROM `roles` WHERE `team_id`=?");
                $stmt3 = $conn->prepare("DELETE FROM `role_assoc` WHERE `team_id`=?");
                $stmt->bind_param("i", $team_id);
                $stmt2->bind_param("i", $team_id);
                $stmt3->bind_param("i", $team_id);
                $stmt->execute();
                $stmt2->execute();
                $stmt3->execute();
                $message = "Team deleted";
            }
        }
    }
    
    // get teams
    $stmt = $conn->prepare("SELECT `teams`.`id`, GROUP_CONCAT(`roles`.`role`) AS `role`, `title`, `description` FROM `role_assoc` INNER JOIN `teams` ON `role_assoc`.`team_id`=`teams`.`id` LEFT JOIN `roles` ON `role_assoc`.`role_id`=`roles`.`id` WHERE `user_id`=? AND role_assoc.`selected` GROUP BY `teams`.`id`");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $i = 0;
    while ($row = $res->fetch_assoc())
    {
        $rows[$i] = $row;
        $i++;
    }
    $length = count($rows);
    
    $loader = new Twig_Loader_Filesystem('resources/views');
    $twig = new Twig_Environment($loader);
    
    $admin = check_if_user_is_admin($_SESSION['user_id']);
    
    echo $twig->render('myteams.html.twig', array(
                                             'nav' => array('page' => $_SERVER['PHP_SELF'], 'admin' => $admin),
                                             'request_method' => $_SERVER['REQUEST_METHOD'],
                                             'error' => $error,
                                             'rows' => $rows,
                                             'length' => $length,
                                             'message' => $message
                                             ));
?>
