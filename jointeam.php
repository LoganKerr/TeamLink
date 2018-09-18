<?php
    session_start();
    ob_start();
    
    require_once("config/config.php");
    require_once("functions.php");
    require_once("vendor/autoload.php");
    
    // sets search to default value
    $search = "";
    
    // if user not signed in
    if (!isset($_SESSION['user_id']))
    {
        header("Location: index.php");
    }
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $search = $_POST['search'];
    }
    
    $user_id = $_SESSION['user_id'];
    $search_wildcard = "%".$search."%";
    // get teams that are similar to searched value
    //$stmt = $conn->prepare("SELECT `firstName`, `lastName`, `title`, `description` FROM `teams` INNER JOIN `users` ON teams.`owner`=users.`id` WHERE `public` AND (`title` LIKE ? OR `description` LIKE ? OR CONCAT(`firstName`, ' ', `lastName`) LIKE ?)");
    $stmt = $conn->prepare("SELECT `firstName`, `lastName`, `title`, `teams`.`description`, `roles`.`role` FROM `roles` INNER JOIN `teams` ON `roles`.`team_id`=`teams`.`id` INNER JOIN `users` ON `teams`.`owner`=`users`.`id` WHERE `public` AND (`title` LIKE ? OR `description` LIKE ? OR `role` LIKE ? OR CONCAT(`firstName`, ' ', `lastName`) LIKE ?)");
    $stmt->bind_param("ssss", $search_wildcard, $search_wildcard, $search_wildcard, $search_wildcard);
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
    
    echo $twig->render('jointeam.html', array(
                                              'nav' => array('page' => $_SERVER['PHP_SELF'], 'admin' => $admin),
                                              'error' => $error,
                                              'rows' => $rows,
                                              'length' => $length,
                                              'search' => ((isset($search))? $search : "")
                                             ));
    
?>
