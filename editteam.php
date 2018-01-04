<?php
    
    session_start();
    require_once("config/config.php");
    
    // if user not signed in
    if (!isset($_SESSION['user_id']))
    {
        header("Location: index.php");
    }
    
    $user_id = $_SESSION['user_id'];
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $query = "SELECT `user_id` FROM `role_assoc` WHERE `user_id`=$user_id AND `team_id`=$id";
    $res = mysqli_query($conn, $query);
    // if user is not associated with team
    if (mysqli_num_rows($res) == 0)
    {
        header("Location: myteams.php");
        exit();
    }
    
    // edit form submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $error = array();
        $warning = array();
        // validate data -----------------------------------
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
        $id = mysqli_real_escape_string($conn, $_GET['id']);
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        
        if (!empty($title))
        {
            // TODO: validate title (same as createam)
            if ($title == "test") { $error['title'] = "<strong>TEST</strong>"; }
        }
        
        if (!empty($description))
        {
            // TODO: validate description (same as createteam)
        }
        
        if (count($error) == 0)
        {
            $query = "UPDATE `teams` SET `title`='".$title."', `description`='".$description."' WHERE `id`=$id";
            if (mysqli_query($conn, $query))
            {
                echo "Changes saved.";
            }
            else
            {
                echo "Error: ".mysqli_error($conn);
            }
        }
    }
    
    $query = "SELECT `title`, `description` FROM `teams` WHERE `id`=$id";
    
    $res = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($res) == 0)
    {
        die("Team not found.");
    }
    $row = mysqli_fetch_assoc($res);
?>

<?php include "resources/templates/header.php"; ?>
<?php include "resources/templates/navbar.php"; ?>
<body>
    <div id="login-panel" class="container">
        <div class="panel panel-primary">
            <div class="panel-heading text-center">Enter the information for a new team</div>
                <div class="panel-body">
                    <div class="container">
                        <form method="post" action='<?php echo "/editteam.php?id=".mysqli_real_escape_string($conn, $_GET[id]); ?>'>
                            <p><label>Title:</label><input class="textbox" name="title" type="text" value='<?php echo(isset($title))?htmlentities($title, ENT_QUOTES):htmlentities($row['title'], ENT_QUOTES); ?>' />
                            <?php echo(isset($error['title']))?$error['title']:""; ?></p>
                            <p><label>Description:</label><textarea name="description"><?php if (isset($description)) { echo htmlentities($description, ENT_QUOTES); } else { echo htmlentities($row['description'], ENT_QUOTES); } ?></textarea>
                            <?php echo(isset($error['description']))?$error['description']:""; ?></p>
                            <div class="submit-button"><input class="btn btn-primary btn-block" type="submit" value="Submit" /></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

