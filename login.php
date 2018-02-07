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
                            <p><label>Email:</label><input class="textbox" name="email" type="text" value='<?php if (isset($email)) { echo htmlentities($email, ENT_QUOTES); } ?>'/></p>
                            <p><label>Password:</label><input class="textbox" name="pass" type="password" /></p>
                            <div class="submit-button"><input class="btn btn-primary btn-block" type="submit" value="Log in" /></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
