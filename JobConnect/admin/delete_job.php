<?php
include "../includes/auth.php";
include "../includes/db.php";

if($_SESSION['role']!="admin"){
    header("Location: ../login.php");
    exit();
}

if(!isset($_GET['job_id'])){
    die("Invalid Request");
}

$job_id = (int)$_GET['job_id'];

// Delete all applications for this job
$stmt = $conn->prepare("DELETE FROM applications WHERE job_id=?");
$stmt->bind_param("i",$job_id);
$stmt->execute();

// Delete the job
$stmt = $conn->prepare("DELETE FROM jobs WHERE job_id=?");
$stmt->bind_param("i",$job_id);
$stmt->execute();

header("Location: manage_jobs.php?msg=deleted");
exit();
?>