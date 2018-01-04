<?php
    session_start();
    ob_start();
    
    require_once("config/config.php");
    
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
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        
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
            $query = "INSERT INTO `teams` (`owner`, `title`, `description`) VALUES ('".$user_id."', '".$title."', '".$description."')";
            if (mysqli_query($conn, $query))
            {
                $team_id = $conn->insert_id;
                $query = "INSERT INTO `role_assoc` (`user_id`, `team_id`, `role`) VALUES ('".$user_id."', '".$team_id."', 'Owner')";
                if (mysqli_query($conn, $query))
                {
                    echo "<p><strong>Team created.</strong></p>";
                }
                else
                {
                echo "<p><strong>Error: ".mysqli_error($conn)."</strong></p>";
                }
            }
            else
            {
                echo "<p><strong>Error: ".mysqli_error($conn)."</strong></p>";
            }
        }
    }
?>
<?php include "resources/templates/header.php"; ?>
<?php include "resources/templates/navbar.php"; ?>
<body>
    <div id="login-panel" class="container">
        <div class="panel panel-primary">
            <div class="panel-heading text-center">Enter the information for a new team</div>
            <div class="panel-body">
                <div class="container">
                    <form method="post" action="/createteam.php">
                        <p><label>Title:</label><input class="textbox" name="title" type="text" value='<?php if (isset($title)) { echo htmlentities($title, ENT_QUOTES); } ?>'/>
                        <?php echo(isset($error['title']))?$error['title']:""; ?></p>
                        <p><label>Description:</label><textarea name="description"><?php if (isset($description)) { echo htmlentities($description, ENT_QUOTES); } ?></textarea>
                        <?php echo(isset($error['description']))?$error['description']:""; ?></p>
                        <div class="submit-button"><input class="btn btn-primary btn-block" type="submit" value="Create Team" /></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
