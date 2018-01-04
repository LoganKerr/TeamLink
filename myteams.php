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

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Venture</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="resources/css/site.css">
	<script src="buildmyteams.js"></script>
</head>
<body>
    <nav class="navbar navbar-inverse bg-primary">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">Venture</a>
            </div>
            <ul class="nav navbar-nav">
                <li><a href="menu.php">Main Menu</a></li>
                <li><a href="/profile.php">Profile</a></li>
                <li class="active"><a href="/myteams.php">My Teams</a></li>
                <li><a href="/createteam.php">Create a Team</a></li>
                <li><a href="/jointeam.php">Join a Team</a></li>
            </ul>
        </div>
    </nav>
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
                            <td><?php echo $row['title']; ?></td>
                            <td><?php echo $row['description']; ?></td>
                            <td><?php echo $row['role']; ?></td>
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
