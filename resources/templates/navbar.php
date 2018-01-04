<nav class="navbar navbar-inverse bg-primary">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">Venture</a>
        </div>
        <ul class="nav navbar-nav">
            <li
                <?php
                if ($_SERVER['PHP_SELF'] == "/menu.php")
                { echo "class='active'"; }
                ?>
            ><a href="menu.php">Main Menu</a></li>
            <li
                <?php
                if ($_SERVER['PHP_SELF'] == "/profile.php")
                { echo "class='active'"; }
                ?>
            ><a href="/profile.php">Profile</a></li>
            <li
                <?php
                if ($_SERVER['PHP_SELF'] == "/myteams.php" ||
                    $_SERVER['PHP_SELF'] == "/editteam.php")
                { echo "class='active'"; }
                ?>
            ><a href="/myteams.php">My Teams</a></li>
            <li
                <?php
                if ($_SERVER['PHP_SELF'] == "/createteam.php")
                { echo "class='active'"; }
                ?>
            ><a href="/createteam.php">Create a Team</a></li>
            <li
                <?php
                if ($_SERVER['PHP_SELF'] == "/jointeam.php")
                { echo "class='active'"; }
                ?>
            ><a href="/jointeam.php">Join a Team</a></li>
        </ul>
    </div>
</nav>
