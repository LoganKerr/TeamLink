<?php
    session_start();
    ob_start();
    
    require_once("config/config.php");
    
    // if user not signed in
    if (!isset($_SESSION['user_id']))
    {
        header("Location: index.php");
    }
?>

<?php include "resources/templates/header.php"; ?>
<?php include "resources/templates/navbar.php"; ?>
<body>
    <div id="join-panel" class="panel panel-primary">
    <div class="panel-body">
    <?php
        $user_id = $_SESSION['user_id'];
        // get teams
        $query = "SELECT `teams`.`id`, `role`, `title`, `description`, `public` FROM `role_assoc` INNER JOIN `teams` ON `role_assoc`.`team_id`=`teams`.`id` WHERE `user_id`='".$user_id."'";
        $res = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($res) == 0)
        {
            echo "You have no teams :(";
        }
        else
        {
        ?>
            <table class="table-striped table-bordered">
		        <tr>
			        <th>Team Title</th><th>Description</th><th>Role</th><th>Public?</th><th></th>
		        </tr>
                <?php
                    while ($row = $res->fetch_assoc()) {
                ?>
		                <tr>
                            <td><?php echo htmlentities($row['title'], ENT_QUOTES); ?></td>
                            <td><?php echo htmlentities($row['description'], ENT_QUOTES); ?></td>
                            <td><?php echo htmlentities($row['role'], ENT_QUOTES); ?></td>
                            <td><?php echo (($row['public'])? "Yes" : "No"); ?></td>
                            <td><a href='<?php echo "/editteam.php?id=".$row['id'].""; ?>'>Edit</a></td>
		                </tr>
                    <?php
                    } // closes while row loop
                    ?>
	        </table>
        <?php
        } // closes else statment to no rows
        ?>
    </div>
    </div>
</body>
</html>
