<?php
    session_start();
    ob_start();
    
    require_once("config/config.php");
    require_once("functions.php");
    require_once("vendor/autoload.php");

    $render_items = array();
    $error = array();
    $message = "";
    
    // if user not signed in
    if (!isset($_SESSION['user_id']))
    {
        header("Location: index.php");
    }
    
    $user_id = $_SESSION['user_id'];
    
    // myteams form submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        // create team
        if($_POST['action']=='create')
        {
            include "createteam.php";
        }
        // team deletion
        else
        {
            include "delete_team.php";
        }
    }
    
    // get teams
    $stmt = $conn->prepare("
        SELECT `teams`.`id`, `title`, `description`,
        (CASE
            WHEN `teams`.`owner`=? then \"Owner\"
            ELSE GROUP_CONCAT(`roles`.`role`)
        END) AS `role`
        FROM `teams` 
        LEFT JOIN `role_assoc` ON `teams`.`id`=`role_assoc`.`team_id` 
        LEFT JOIN `roles` ON `role_assoc`.`role_id`=`roles`.`id` 
        WHERE `role_assoc`.`user_id`=? AND role_assoc.`selected` OR `teams`.`owner`=?
        GROUP BY `teams`.`id`
    ");
    $stmt->bind_param("iii", $user_id,$user_id, $user_id);
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

    $render_items['nav'] = array('page' => $_SERVER['PHP_SELF'], 'admin' => $admin);
    $render_items['request_method'] = $_SERVER['REQUEST_METHOD'];
    $render_items['error'] = (isset($error)? $error : array());
    $render_items['rows'] = $rows;
    $render_items['length'] = $length;
    $render_items['message'] = (isset($message)? $message : "");
    
    $loader = new Twig_Loader_Filesystem('resources/views');
    $twig = new Twig_Environment($loader);
    
    $admin = check_if_user_is_admin($_SESSION['user_id']);
    
    echo $twig->render('myteams.html.twig', $render_items);
?>
