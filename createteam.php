<?php
require_once("config/config.php");
require_once("functions.php");
require_once("vendor/autoload.php");

// validate data -------------------------------------
// check empty fields
$required = array("title", "description");
$error['create-team'] = set_error_on_empty_required_fields($_POST, $required, $error);
// escape data
$title = $_POST['title'];
$description = $_POST['description'];

if (!empty($title))
{
    // TODO: validate title
    if ($title == "test")
    {
        $error['create-team']["title"] = "You're a fake";
    }
}

if (!empty($description))
{
    // TODO: validate description
}

if (count($error['create-team']) == 0)
{
    $stmt = $conn->prepare("INSERT INTO `teams` (`owner`, `title`, `description`) VALUES (?,?,?)");
    $stmt->bind_param("iss", $user_id, $title, $description);
    $stmt->execute();
}
else
{
    // set modal error
    $error['modal'] = true;
}

$render_items['modal']['title'] = (isset($title)? $title : "");
$render_items['modal']['description'] = (isset($description)? $description : "");
?>
