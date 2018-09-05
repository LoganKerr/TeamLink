<?php
    session_start();
    ob_start();
    
    
    require_once("config/config.php");
    require_once("vendor/autoload.php");
    
    // if user signed in
    if (isset($_SESSION['user_id']))
    {
        header("Location: menu.php");
        exit();
    }
    
    // signup form submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $error = array();
        $required = array("email", "pass");
        foreach ($required as $key => $value)
        {
            if(!isset($_POST[$value]) || empty($_POST[$value]) && $_POST[$value] != '0')
            {
                $error[$value] = "This field is required";
            }
        }
        $email = $_POST['email'];
        $pass = $_POST['pass'];
        
        if (!empty($email) && !empty($pass))
        {
            $stmt = $conn->prepare("SELECT `id`, `passHash` FROM `users` WHERE `email`=?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $res = $stmt->get_result();
            
            if ($res->num_rows == 0) {
                $error['email'] = "Invalid email or password";
            }
            else
            {
                $row = $res->fetch_assoc();
                $passHash = $row['passHash'];
                if (!password_verify($pass, $passHash))
                {
                    $error['email'] = "Invalid email or password";
                }
            }
        }
        
        if (count($error) == 0)
        {
            $_SESSION['user_id'] = $row['id'];
            header('Location: menu.php');
            exit();
        }
    }
    
    $loader = new Twig_Loader_Filesystem('resources/views');
    $twig = new Twig_Environment($loader);
    
    echo $twig->render('login.html', array(
                                           'error' => $error,
                                           'email' => $email
                       ));
    
?>
