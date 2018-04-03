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
        
        // TODO: validate team and user adding
        //var_dump($_POST);
        // for each role field
        foreach ($_POST as $key => $value)
        {
            // updates user assigned to role
            if (substr($key, 0, 9) == "role_user")
            {
                $role_id = filter_var(substr($key, 9), FILTER_SANITIZE_NUMBER_INT);
                $user_id = (int)$value;
                $stmt = $conn->prepare("UPDATE `role_assoc` SET `selected`=0 WHERE `team_id`=? AND `role_id`=?");
                $stmt->bind_param("ii", $id, $role_id);
                $stmt->execute();
                $stmt = $conn->prepare("UPDATE `role_assoc` SET `selected`=1 WHERE `team_id`=? AND `role_id`=? AND `user_id`=?");
                $stmt->bind_param("iii", $id, $role_id, $user_id);
                $stmt->execute();
            }
            // updates role name if changed
            else if (substr($key, 0, 9) == "role_name")
            {
                $role_id = filter_var(substr($key, 9), FILTER_SANITIZE_NUMBER_INT);
                $stmt = $conn->prepare("UPDATE `roles` SET `role`=? WHERE `id`=?");
                $stmt->bind_param("si", $value, $role_id);
                $stmt->execute();
                
            }
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
    // SELECT teams.`title`, teams.`description`, role_assoc.`role`, users.`firstName`, users.`lastName` FROM `teams` INNER JOIN `role_assoc` ON teams.`id`=role_assoc.`team_id`  LEFT JOIN `users` ON role_assoc.`user_id`=users.`id` where teams.`id`=13
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    
    if ($res->num_rows == 0)
    {
        die("Team not found.");
    }
    
    $stmt2 = $conn->prepare("SELECT `role_assoc`.`role_id`, `role_assoc`.`user_id`, `role_assoc`.`selected`, roles.`role`, users.`firstName`, users.`lastName` FROM `role_assoc` LEFT JOIN `users` ON role_assoc.`user_id`=users.`id` LEFT JOIN `roles` ON `role_assoc`.`role_id`=`roles`.`id` WHERE roles.`role`!='Owner' && role_assoc.`team_id`=? ORDER BY `role_assoc`.`role_id`");
    $stmt2->bind_param("i", $id);
    $stmt2->execute();
    $res2 = $stmt2->get_result();
    
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
                            <p><label class="form-label">Roles:</label>
                                <?php
                                    $sel_open = false;
                                    $role_ids = array ();
                                    while ($row2 = $res2->fetch_assoc())
                                    {
                                        if (in_array($row2['role_id'], $role_ids))
                                        {
                                            if ($row2['user_id'])
                                            {
                                            ?>
                                                <option <?php echo (($row2['selected'])? "selected" : "");?> value='<?php echo htmlentities($row2['user_id'], ENT_QUOTES); ?>'><?php echo htmlentities($row2['firstName'], ENT_QUOTES); ?></option>
                                            <?php
                                            }
                                        }
                                        else
                                        {
                                            array_push($role_ids, $row2['role_id']);
                                            if ($sel_open) { ?></select><?php $sel_open = false; } ?>
                                            <br>
                                            <input type="text" name='role_name<?php echo htmlentities($row2['role_id'], ENT_QUOTES); ?>' value='<?php echo htmlentities($row2['role'], ENT_QUOTES);?>'></input>
                                            <input type="hidden" name='role_id<?php echo htmlentities($row2['role_id'], ENT_QUOTES); ?>' value='<?php echo htmlentities($row2['role_id'], ENT_QUOTES);?>'/>
                                            <select name='role_user<?php echo htmlentities($row2['role_id'], ENT_QUOTES); ?>'>
                                            <option>None</option>
                                            <?php
                                            if ($row2['user_id'])
                                            {
                                            ?>
                                                <option <?php echo (($row2['selected'])? "selected" : "");?> value='<?php echo htmlentities($row2['user_id'], ENT_QUOTES); ?>'><?php echo htmlentities($row2['firstName'], ENT_QUOTES); ?></option>
                                            <?php
                                            }
                                            $sel_open = true;
                                        }
                                    }
                                     ?>
                            </p>
                            <div class="submit-button"><input class="btn btn-primary btn-block" type="submit" value="Submit" /></div>
                    </form>
            </div>
        </div>
    </div>
</body>
</html>

