<?php
include "../includes/auth.php";

if($_SESSION['role'] != 'recruiter'){
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Recruiter Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

<div class="card shadow">

<div class="card-body">

<h2>Recruiter Dashboard</h2>

<hr>

<h4>Welcome,
<span class="text-primary">
<?php echo $_SESSION['name']; ?>
</span>
</h4>

<div class="mt-4">

<a href="post_job.php" class="btn btn-primary">
➕ Post New Job
</a>

<a href="my_jobs.php" class="btn btn-success">
📋 My Jobs
</a>

<a href="profile.php" class="btn btn-info">
My Profile
</a>

<a href="applications.php" class="btn btn-warning">
    View Applications
</a>

<a href="../logout.php" class="btn btn-danger">
Logout
</a>

</div>

</div>

</div>

</div>

</body>
</html>