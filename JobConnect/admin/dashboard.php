<?php
include "../includes/auth.php";
include "../includes/db.php";

if($_SESSION['role']!="admin")
{
    header("Location: ../login.php");
    exit();
}

$totalStudents=$conn->query("SELECT COUNT(*) total FROM users WHERE role='student'")->fetch_assoc()['total'];

$totalRecruiters=$conn->query("SELECT COUNT(*) total FROM users WHERE role='recruiter'")->fetch_assoc()['total'];

$totalJobs=$conn->query("SELECT COUNT(*) total FROM jobs")->fetch_assoc()['total'];

$totalApplications=$conn->query("SELECT COUNT(*) total FROM applications")->fetch_assoc()['total'];
?>

<!DOCTYPE html>

<html>

<head>

<title>Admin Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

<h2 class="mb-4">

Admin Dashboard

</h2>

<div class="row">

<div class="col-md-3">

<div class="card bg-primary text-white">

<div class="card-body">

<h5>Total Students</h5>

<h2><?= $totalStudents ?></h2>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card bg-success text-white">

<div class="card-body">

<h5>Total Recruiters</h5>

<h2><?= $totalRecruiters ?></h2>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card bg-warning">

<div class="card-body">

<h5>Total Jobs</h5>

<h2><?= $totalJobs ?></h2>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card bg-danger text-white">

<div class="card-body">

<h5>Total Applications</h5>

<h2><?= $totalApplications ?></h2>

</div>

</div>

</div>

</div>

<hr>

<div class="mt-4">

<a href="manage_students.php" class="btn btn-primary">
Manage Students
</a>

<a href="manage_recruiters.php" class="btn btn-success">
Manage Recruiters
</a>

<a href="manage_jobs.php" class="btn btn-warning">
Manage Jobs
</a>

<a href="manage_applications.php" class="btn btn-danger">
Manage Applications
</a>

<a href="../logout.php" class="btn btn-dark">
Logout
</a>

</div>

</div>

</body>

</html>