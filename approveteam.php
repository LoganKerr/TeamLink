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
        exit();
    }
    
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT `admin` FROM `users` WHERE `id`=?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    // if user is not admin (can't approve teams)
    if (!$row['admin'])
    {
        header("Location: menu.php");
    }
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $action = $_POST['action'];
        $team_id = $_POST['id'];
        // project was approved and must be set public
        if ($action == "approve")
        {
            $stmt = $conn->prepare("UPDATE `teams` SET `public`=1 WHERE `id`=?");
            $stmt->bind_param("i", $team_id);
        }
        else if ($action == "delete")
        {
            $stmt = $conn->prepare("DELETE FROM `teams` WHERE `id`=?");
            $stmt->bind_param("i", $team_id);
        }
        $stmt->execute();
    }
    
    $user_id = $_SESSION['user_id'];
    // get teams
    $stmt = $conn->prepare("SELECT `id`, `title`, `description` FROM `teams` WHERE NOT `public`");
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
    
    echo $twig->render('approveteam.html', array(
                                             'nav' => array('page' => $_SERVER['PHP_SELF'], 'admin' => $admin),
                                             'error' => $error,
                                             'rows' => $rows,
                                             'length' => $length
                                             ));
    
?>
