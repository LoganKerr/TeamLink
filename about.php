<?php
    session_start();
    ob_start();
    
    require_once("config/config.php");
?>

<?php include "resources/templates/header.php"; ?>
<body>
    <div id="about-panel" class="container">
        <div class="panel panel-primary">
            <div class="panel-heading text-center">About Us</div>
                <div class="panel-body">
                    <div class="container">
                    TeamLink is a web application where college students and faculty can create and join teams with other students and faculty. From a band, a development team, or even a gaming group, students have the chance to make new friends, cultivate experience, and put their free time to use.
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>