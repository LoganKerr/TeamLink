<?php
    session_start();
    ob_start();
    
    require_once("config/config.php");
    
    // if user not signed in
    if (!isset($_SESSION['user_id']))
    {
        header("Location: index.php");
        exit();
    }
    
    $user_id = $_SESSION['user_id'];
    $query = "SELECT `admin` FROM `users` WHERE `id`=$user_id";
    $res = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($res);
    // if user is not admin (can't approve teams)
    if (!$row['admin'])
    {
        header("Location: menu.php");
    }
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $action = $_POST['action'];
        $team_id = $_POST['id'];
        // project was approved and must be set public
        if ($action == "approve")
        {
            $query = "UPDATE `teams` SET `public`=1 WHERE `id`=$team_id";
        }
        else if ($action == "delete")
        {
            $query = "DELETE FROM `teams` WHERE `id`=$team_id";
        }
        mysqli_query($conn, $query);
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
                <li><a href="/myteams.php">My Teams</a></li>
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
            $query = "SELECT `id`, `title`, `description` FROM `teams` WHERE NOT `public`";
            $res = mysqli_query($conn, $query);
    
            if (mysqli_num_rows($res) == 0)
            {
                echo "No teams need approval";
            }
            else
            {
            ?>
                <script>
                    function setDelete()
                    {
                        document.getElementById("action").value = "delete";
                    }
                </script>
                    <table class="table-striped table-bordered">
                        <tr>
                            <th>Team Title</th><th>Description</th><th>Approve</th><th>Delete</th>
                        </tr>
                        <?php
                        while ($row = $res->fetch_assoc()) {
                        ?>
                            <tr>
                                <td><?php echo $row['title']; ?></td>
                                <td><?php echo $row['description']; ?></td>
                                <form method="post" action="/approveteam.php" />
                                <input type="hidden" id="action" name="action" value="approve" />
                                <input type="hidden" name="id" value='<?php echo $row['id'] ?>'/>
                                <td><div class="submit-button"><input class="btn btn-primary btn-block" type="submit" value="Approve" /></div></td>
                                <td><div class="submit-button"><input class="btn btn-primary btn-block" type="submit" value="Delete" onclick="setDelete()"/></div></td>
                            </tr>
                        <?php
                        } // closes while row loop
                        ?>
                    </table>
                </form>
            <?php
            } // closes else statment to no rows
            ?>
        </div>
    </div>
</body>
</html>

