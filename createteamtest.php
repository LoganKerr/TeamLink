<?php
require_once("config/config.php");
require_once("functions.php");
require_once("vendor/autoload.php");

// validate data -------------------------------------
// check empty fields
$required = array("title", "description");
$error = set_error_on_empty_required_fields($_POST, $required, $error);
// escape data
$title = $_POST['title'];
$description = $_POST['description'];

if (!empty($title))
{
    // TODO: validate title
}

if (!empty($description))
{
    // TODO: validate description
}

if (count($error) == 0)
{
    $stmt = $conn->prepare("INSERT INTO `teams` (`owner`, `title`, `description`) VALUES (?,?,?)");
    $stmt->bind_param("iss", $user_id, $title, $description);
    $stmt->execute();
}
/*
echo $twig->render('myteams.html.twig', array(
    'nav' => array('page' => $_SERVER['PHP_SELF'], 'admin' => $admin),
    'request_method' => $_SERVER['REQUEST_METHOD'],
    'error' => (isset($error)? $error : array()),
    'title' => (isset($title)? $title : ""),
    'description' => (isset($description)? $description : "")
));
*/
?>
