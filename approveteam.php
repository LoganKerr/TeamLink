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

<?php include "resources/templates/header.php"; ?>
<?php include "resources/templates/navbar.php"; ?>
<body>
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

