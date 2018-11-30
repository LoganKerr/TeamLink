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
        else if ($_POST['action'] == 'application')
        {
            include "toggle_application.php";
        }
        else if ($_POST['action'] == 'edit')
        {
            include "editteam.php";
        }
         //   include "delete_team.php";
    }

    // filter
    $filter = $_GET['filter'];
    $render_items['filter'] = $filter;

    // sets search to default value
    $search = (($_GET['search'])? : '');
    $search_wildcard = "%".$search."%";
    $render_items['search'] = $search;

    // sets edit value
    $edit = (($_GET['edit'])? $_GET['edit'] : '');
    $render_items['edit'] = $edit;

    // show my teams
    if ($filter == "my")
    {
        // get teams
        $stmt = $conn->prepare("
        SELECT `teams`.`id`, `title`, `firstName`, `lastName`,
        (CASE
            WHEN `teams`.`owner`=? then \"Owner\"
            ELSE GROUP_CONCAT(`roles`.`role`)
        END) AS `role`
        FROM `teams` 
        LEFT JOIN `role_assoc` ON `teams`.`id`=`role_assoc`.`team_id` 
        LEFT JOIN `roles` ON `role_assoc`.`role_id`=`roles`.`id`
        LEFT JOIN `users` ON `teams`.`owner` = `users`.`id` 
        WHERE `role_assoc`.`user_id`=? AND role_assoc.`selected` OR `teams`.`owner`=? AND (`teams`.`title` LIKE ? OR `description` LIKE ? OR `role` LIKE ? OR CONCAT(`firstName`, ' ', `lastName`) LIKE ?)
        GROUP BY `teams`.`id`
    ");
        $stmt->bind_param("iiissss", $user_id,$user_id, $user_id, $search_wildcard, $search_wildcard, $search_wildcard, $search_wildcard);
        $stmt->execute();
        $res = $stmt->get_result();
        $i = 0;
        $rows = array();
        while ($row = $res->fetch_assoc())
        {
            $rows[$i] = $row;
            $i++;
        }

        $render_items['list_items'] = $rows;
    }
    // applied teams
    else if ($filter == "applied")
    {
        $stmt = $conn->prepare("
            SELECT `teams`.`id`, `title`, `firstName`, `lastName` 
            FROM `teams`
            INNER JOIN `role_assoc` ON `teams`.`id` = `role_assoc`.`team_id`
            INNER JOIN `roles` ON `role_assoc`.`role_id` = `roles`.`id`
            INNER JOIN `users` ON `teams`.`owner` = `users`.`id`
            WHERE `user_id`=? AND (`teams`.`title` LIKE ? OR `description` LIKE ? OR `role` LIKE ? OR CONCAT(`firstName`, ' ', `lastName`) LIKE ?)");
        $stmt->bind_param("issss", $user_id,$search_wildcard, $search_wildcard, $search_wildcard, $search_wildcard);
        $stmt->execute();
        $res = $stmt->get_result();
        $i = 0;
        $rows = array();
        while ($row = $res->fetch_assoc())
        {
            $rows[$i] = $row;
            $i++;
        }

        $render_items['list_items'] = $rows;
    }
    // all teams
    else
    {
        $stmt = $conn->prepare("SELECT `users`.`university_id` FROM `users` WHERE `users`.`id`=?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $university_id = $row['university_id'];
        $stmt = $conn->prepare("
            SELECT `teams`.`id`,`firstName`, `lastName`, `teams`.`title`
            FROM `teams`
            LEFT JOIN `role_assoc` ON `teams`.`id` = `role_assoc`.`team_id`
            LEFT JOIN `roles` ON `role_assoc`.`role_id` = `roles`.`id`
            INNER JOIN `users` ON `teams`.`owner`=`users`.`id` 
            INNER JOIN `universities` ON `users`.`university_id`=`universities`.`id` 
            WHERE `universities`.`id`=? AND (`teams`.`title` LIKE ? OR `description` LIKE ? OR `role` LIKE ? OR CONCAT(`firstName`, ' ', `lastName`) LIKE ?)
            ORDER BY `teams`.`id` DESC");
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
        $render_items['list_items'] = $rows;
    }

    // if team selected
    if (isset($_GET['id']))
    {
        $id = $_GET['id'];
        $render_items['id'] = $id;
        include "load-team.php";
    }

    $render_items['nav'] = array('page' => $_SERVER['PHP_SELF']);
    $render_items['request_method'] = $_SERVER['REQUEST_METHOD'];
    $render_items['error'] = (isset($error)? $error : array());
    $render_items['message'] = (isset($message)? $message : "");
    
    $loader = new Twig_Loader_Filesystem('resources/views');
    $twig = new Twig_Environment($loader);
    
    $admin = check_if_user_is_admin($_SESSION['user_id']);
    
    echo $twig->render('teams.html.twig', $render_items);
?>
