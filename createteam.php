<?php
    session_start();
    ob_start();
    
    require_once("config/config.php");
    require_once("functions.php");
    require_once("vendor/autoload.php");
    
    // if user is not signed in
    if (!isset($_SESSION['user_id']))
    {
        header("Location: index.php");
    }
    
    $user_id = $_SESSION['user_id'];
    
    // create team form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $error = array();
        // validate data -------------------------------------
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
        $title = $_POST['title'];
        $description = $_POST['description'];
        
        if (!empty($title))
        {
            // TODO: validate title
        }
        
        if (!empty($description))
        {
            // TODO: validate description
        }
        
        if (count($error) == 0)
        {
            $stmt = $conn->prepare("INSERT INTO `teams` (`owner`, `title`, `description`) VALUES (?,?,?)");
            $stmt->bind_param("iss", $user_id, $title, $description);
            if ($stmt->execute())
            {
                $team_id = $stmt->insert_id;
                $stmt = $conn->prepare("INSERT INTO `role_assoc` (`user_id`, `team_id`, `role_id`, `selected`) VALUES (?,?, 0, 1)");
                $stmt->bind_param("ii", $user_id, $team_id);
                if ($stmt->execute())
                {
                    echo "<p><strong>Team created.</strong></p>";
                }
                else
                {
                echo "<p><strong>Error: ".$stmt->error."</strong></p>";
                }
            }
            else
            {
                echo "<p><strong>Error: ".$stmt->error."</strong></p>";
            }
        }
    }
    
    $loader = new Twig_Loader_Filesystem('resources/views');
    $twig = new Twig_Environment($loader);
    
    $admin = check_if_user_is_admin($_SESSION['user_id']);
    
    echo $twig->render('createteam.html', array(
                                             'nav' => array('page' => $_SERVER['PHP_SELF'], 'admin' => $admin),
                                                'error' => $error,
                                                'title' => $title,
                                                'description' => $description
                                             ));
?>
