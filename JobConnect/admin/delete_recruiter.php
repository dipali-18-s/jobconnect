<?php
include "../includes/auth.php";
include "../includes/db.php";

if ($_SESSION['role'] != "admin") {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['user_id'])) {
    die("Invalid Request");
}

$user_id = (int)$_GET['user_id'];

// Find recruiter_id
$stmt = $conn->prepare("SELECT recruiter_id FROM recruiter_profiles WHERE user_id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {

    $recruiter = $result->fetch_assoc();
    $recruiter_id = $recruiter['recruiter_id'];

    // Find all jobs of this recruiter
    $jobs = $conn->prepare("SELECT job_id FROM jobs WHERE recruiter_id=?");
    $jobs->bind_param("i", $recruiter_id);
    $jobs->execute();
    $jobResult = $jobs->get_result();

    while ($job = $jobResult->fetch_assoc()) {

        $job_id = $job['job_id'];

        // Delete applications
        $deleteApp = $conn->prepare("DELETE FROM applications WHERE job_id=?");
        $deleteApp->bind_param("i", $job_id);
        $deleteApp->execute();
    }

    // Delete jobs
    $deleteJobs = $conn->prepare("DELETE FROM jobs WHERE recruiter_id=?");
    $deleteJobs->bind_param("i", $recruiter_id);
    $deleteJobs->execute();

    // Delete recruiter profile
    $deleteProfile = $conn->prepare("DELETE FROM recruiter_profiles WHERE recruiter_id=?");
    $deleteProfile->bind_param("i", $recruiter_id);
    $deleteProfile->execute();
}

// Delete user account
$deleteUser = $conn->prepare("DELETE FROM users WHERE user_id=?");
$deleteUser->bind_param("i", $user_id);
$deleteUser->execute();

header("Location: manage_recruiters.php?msg=deleted");
exit();
?>