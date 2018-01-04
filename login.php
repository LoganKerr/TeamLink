<?php
    session_start();
    ob_start();
    
    require_once("config/config.php");
    
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
                $error[$value] = "<strong>This field is required.</strong>";
            }
        }
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $pass = mysqli_real_escape_string($conn, $_POST['pass']);
        
        if (!empty($email) && !empty($pass))
        {
            $query = "SELECT `id`, `passHash` FROM `users` WHERE `email`='".$email."'";
            $res = mysqli_query($conn, $query);
            
            echo mysqli_num_rows($res) == 0;
            
            if (mysqli_num_rows($res) == 0) {
                $error['email'] = "Invalid email or password";
            }
            else
            {
                $row = mysqli_fetch_assoc($res);
                var_dump($row);
                $passHash = $row['passHash'];
                if (!password_verify($pass, $passHash))
                {
                    $error['email'] = "Invalid email or password";
                }
            }
        }
        
        if (count($error) == 0)
        {
            var_dump($row['id']);
            $_SESSION['user_id'] = $row['id'];
            header('Location: menu.php');
            exit();
        }
    }
?>

<?php include "resources/templates/header.php"; ?>
<body>
    <div id="login-panel" class="container">
        <div class="panel panel-primary">
            <div class="panel-heading text-center">Enter your email address and password</div>
                <div class="panel-body">
                    <div class="container">
                    <form method="post" action="/login.php">
                            <?php echo(isset($error['email']))?$error['email']:""; ?>
                            <p><label>Email:</label><input class="textbox" name="email" type="text" /></p>
                            <p><label>Password:</label><input class="textbox" name="pass" type="password" /></p>
                            <div class="submit-button"><input class="btn btn-primary btn-block" type="submit" value="Log in" /></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
