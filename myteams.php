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
    // get teams
    $stmt = $conn->prepare("SELECT `teams`.`id`, GROUP_CONCAT(`roles`.`role`) AS `role`, `title`, `description`, `public` FROM `role_assoc` INNER JOIN `teams` ON `role_assoc`.`team_id`=`teams`.`id` LEFT JOIN `roles` ON `role_assoc`.`role_id`=`roles`.`id` WHERE `user_id`=? AND role_assoc.`selected` GROUP BY `teams`.`id`");
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
    
    echo $twig->render('myteams.html', array(
                                             'nav' => array('page' => $_SERVER['PHP_SELF'], 'admin' => $admin),
                                             'error' => $error,
                                             'rows' => $rows,
                                             'length' => $length
                                             ));
?>
