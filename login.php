<?php
    session_start();
    ob_start();
    
    include "config/config.php";
    
    // if user signed in
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        header("Location: menu.php");
        exit();
    }
    
    // signup form submitted
    if (isset($_POST['login']))
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
        
        if (!empty($email) && !empty($password))
        {
            $query = "SELECT `id`, `passHash` FROM `users` WHERE `email`='".$email."'";
            $res = mysqli_query($conn, $query);
            
            if (mysqli_num_rows($res) == 0) {
                $error['email'] = "Invalid email or password";
            }
            else
            {
                $row = mysqli_fetch_assoc($res);
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

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<title>Venture</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="resources/css/site.css">
</head>
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
