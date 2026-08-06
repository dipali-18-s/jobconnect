<?php
include "../includes/auth.php";
include "../includes/db.php";

if ($_SESSION['role'] != "recruiter") {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['job_id'])) {
    die("Invalid Job ID.");
}

$job_id = (int)$_GET['job_id'];

// Verify that the recruiter owns this job
$stmt = $conn->prepare("
    SELECT jobs.job_id
    FROM jobs
    JOIN recruiter_profiles
    ON jobs.recruiter_id = recruiter_profiles.recruiter_id
    WHERE jobs.job_id=? AND recruiter_profiles.user_id=?
");

$stmt->bind_param("ii", $job_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("You are not authorized to delete this job.");
}

// Delete related applications first
$stmt = $conn->prepare("DELETE FROM applications WHERE job_id=?");
$stmt->bind_param("i", $job_id);
$stmt->execute();

// Delete the job
$stmt = $conn->prepare("DELETE FROM jobs WHERE job_id=?");
$stmt->bind_param("i", $job_id);

if ($stmt->execute()) {
    header("Location: my_jobs.php?msg=deleted");
    exit();
} else {
    echo "Unable to delete job.";
}
?>