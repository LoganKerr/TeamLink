<?php
    
    session_start();
    require_once("config/config.php");
    
    // if user not signed in
    if (!isset($_SESSION['user_id']))
    {
        header("Location: index.php");
    }
    
    $user_id = $_SESSION['user_id'];
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT `user_id` FROM `role_assoc` WHERE `user_id`=? AND `team_id`=?");
    $stmt->bind_param("ii", $user_id, $id);
    $stmt->execute();
    $res = $stmt->get_result();
    // if user is not associated with team
    if ($res->num_rows == 0)
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
        $id = $_GET['id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        
        if (!empty($title))
        {
            // TODO: validate title (same as createam)
        }
        
        if (!empty($description))
        {
            // TODO: validate description (same as createteam)
        }
        
        if (count($error) == 0)
        {
            $stmt = $conn->prepare("UPDATE `teams` SET `title`=?, `description`=? WHERE `id`=?");
            $stmt->bind_param("ssi", $title, $description, $id);
            if ($stmt->execute())
            {
                echo "Changes saved.";
            }
            else
            {
                echo "Error: ".$stmt->error;
            }
        }
    }
    
    $stmt = $conn->prepare("SELECT `title`, `description` FROM `teams` WHERE `id`=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    
    if ($res->num_rows == 0)
    {
        die("Team not found.");
    }
    $row = $res->fetch_assoc();
?>

<?php include "resources/templates/header.php"; ?>
<?php include "resources/templates/navbar.php"; ?>
<body>
    <div id="login-panel" class="container">
        <div class="panel panel-primary">
            <div class="panel-heading text-center">Update the information for your team</div>
                <div class="panel-body">
<form method="post" action='<?php echo "/editteam.php?id=$_GET[id]"; ?>'>
                            <p><label class="form-label">Title:</label><input class="textbox" name="title" type="text" value='<?php echo(isset($title))?htmlentities($title, ENT_QUOTES):htmlentities($row['title'], ENT_QUOTES); ?>' />
                            <?php echo(isset($error['title']))?$error['title']:""; ?></p>
                            <p><label class="form-label">Description:</label><textarea name="description"><?php if (isset($description)) { echo htmlentities($description, ENT_QUOTES); } else { echo htmlentities($row['description'], ENT_QUOTES); } ?></textarea>
                            <?php echo(isset($error['description']))?$error['description']:""; ?></p>
                            <div class="submit-button"><input class="btn btn-primary btn-block" type="submit" value="Submit" /></div>
                    </form>
            </div>
        </div>
    </div>
</body>
</html>

