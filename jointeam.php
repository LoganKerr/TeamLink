<?php
    session_start();
    ob_start();
    
    require_once("config/config.php");
    
    // sets search to default value
    $search = "";
    
    // if user not signed in
    if (!isset($_SESSION['user_id']))
    {
        header("Location: index.php");
    }
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $search = $_POST['search'];
    }
    
?>

<?php include "resources/templates/header.php"; ?>
<?php include "resources/templates/navbar.php"; ?>
<body>
    <div id="join-panel" class="panel panel-primary">
    <div class="panel-body">
    <?php
        $user_id = $_SESSION['user_id'];
        $search_wildcard = "%".$search."%";
        // get teams that are similar to searched value
        $stmt = $conn->prepare("SELECT `firstName`, `lastName`, `title`, `description` FROM `teams` INNER JOIN `users` ON teams.`owner`=users.`id` WHERE `public` AND (`title` LIKE ? OR `description` LIKE ? OR CONCAT(`firstName`, ' ', `lastName`) LIKE ?)");
        $stmt->bind_param("sss", $search_wildcard, $search_wildcard, $search_wildcard);
        $stmt->execute();
        $res = $stmt->get_result();
        
        if ($res->num_rows == 0)
        {
            echo "There are no teams :(";
        }
        else
        {
        ?>
            <form method="post" action="jointeam.php">
            <input type="textbox" name="search" placeholder="Search" style="float: right;"/>
            <br><?php if(isset($search)) { echo "Showing results for \"".htmlentities($search)."\""; } ?><br>
            <table class="table-striped table-bordered">
                <tr>
                    <th>Name</th><th>Team Title</th><th>Description</th><th></th>
                </tr>
                <?php
                    while ($row = $res->fetch_assoc())
                    {
                ?>
                        <tr>
                            <td><?php echo htmlentities($row['firstName'], ENT_QUOTES)." ".htmlentities($row['lastName'], ENT_QUOTES); ?></td>
                            <td><?php echo htmlentities($row['title'], ENT_QUOTES); ?></td>
                            <td><?php echo htmlentities($row['description'], ENT_QUOTES); ?></td>
                            <td><button type="button" class="btn btn-primary disabled">Join</button></td>
                        </tr>
                    <?php
                    } // closes while row loop
                    ?>
            </table>
            </form>
        <?php
        } // closes else statement to no rows
        ?>
    </div>
    </div>
</body>
</html>
