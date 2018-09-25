<?php
    // requires config file
    require_once(dirname(__FILE__).'/../config/config.php');
    
    echo "University insertion script\n";
    $handle = fopen ("php://stdin","r");
    echo "University title: ";
    $line = fgets($handle);
    $university = trim($line);
    if ($university == "")
    {
        echo "University title cannot be empty\n";
    }
    else
    {
        $stmt = $conn->prepare("SELECT COUNT(`universities`.`id`) AS count FROM `universities` WHERE `universities`.`title`=?");
        $stmt->bind_param("s", $university);
        if ($stmt->execute())
        {
            $res = $stmt->get_result();
            $row = $res->fetch_assoc();
            // No university with that title exists in the database. Insert into database
            if ($row && $row['count'] == 0)
            {
                $stmt = $conn->prepare("INSERT INTO `universities` (`title`) VALUES (?)");
                $stmt->bind_param("s", $university);
                // execute stmt
                if ($stmt->execute())
                {
                    echo "University added successfully\n";
                }
                // execution error
                else
                {
                    echo "Errror: ".$stmt->error;
                }
            }
            // University already exists in table
            else
            {
                echo "University already exists in database\n";
            }
        }
        // execution error
        else
        {
            echo "Errror: ".$stmt->error;
        }
    }
?>
