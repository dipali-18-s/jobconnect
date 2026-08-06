<?php
include "../includes/auth.php";
include "../includes/db.php";

if ($_SESSION['role'] != "recruiter") {
    header("Location: ../login.php");
    exit();
}

$stmt = $conn->prepare("
SELECT recruiter_id
FROM recruiter_profiles
WHERE user_id=?
");

$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();

$recruiter = $stmt->get_result()->fetch_assoc();

$stmt = $conn->prepare("
SELECT *
FROM jobs
WHERE recruiter_id=?
ORDER BY posted_at DESC
");

$stmt->bind_param("i", $recruiter['recruiter_id']);
$stmt->execute();

$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>My Jobs</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

<div class="d-flex justify-content-between align-items-center mb-4">

<h2>My Posted Jobs</h2>

<a href="dashboard.php" class="btn btn-secondary">
Dashboard
</a>

</div>

<?php
if(isset($_GET['msg']))
{
?>

<div class="alert alert-success">
Job deleted successfully.
</div>

<?php
}
?>

<table class="table table-bordered table-hover bg-white shadow">

<thead class="table-dark">

<tr>

<th>ID</th>

<th>Title</th>

<th>Company</th>

<th>Location</th>

<th>Salary</th>

<th>Type</th>

<th>Date</th>

<th width="220">Action</th>

</tr>

</thead>

<tbody>

<?php

while($job=$result->fetch_assoc())
{

?>

<tr>

<td><?= $job['job_id'] ?></td>

<td><?= htmlspecialchars($job['job_title']) ?></td>

<td><?= htmlspecialchars($job['company_name']) ?></td>

<td><?= htmlspecialchars($job['location']) ?></td>

<td><?= htmlspecialchars($job['salary']) ?></td>

<td><?= htmlspecialchars($job['job_type']) ?></td>

<td><?= $job['posted_at'] ?></td>

<td>

<a
href="edit_job.php?job_id=<?= $job['job_id'] ?>"
class="btn btn-warning btn-sm">

Edit

</a>

<a
href="delete_job.php?job_id=<?= $job['job_id'] ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Are you sure you want to delete this job?');">

Delete

</a>

<a
href="applications.php?job_id=<?= $job['job_id'] ?>"
class="btn btn-primary btn-sm">

Applicants

</a>

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

</body>

</html>