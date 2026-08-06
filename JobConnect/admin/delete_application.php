<?php
include "../includes/auth.php";
include "../includes/db.php";

if ($_SESSION['role'] != "admin") {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['application_id'])) {
    die("Invalid Request");
}

$application_id = (int)$_GET['application_id'];

$stmt = $conn->prepare("
DELETE FROM applications
WHERE application_id=?
");

$stmt->bind_param("i",$application_id);

$stmt->execute();

header("Location: manage_applications.php?msg=deleted");

exit();
?>