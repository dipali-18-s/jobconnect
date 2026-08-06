<?php
include "../includes/auth.php";
include "../includes/db.php";

if ($_SESSION['role'] != "student") {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get Student ID
$stmt = $conn->prepare("SELECT student_id FROM student_profiles WHERE user_id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Student profile not found.");
}

$student = $result->fetch_assoc();
$student_id = $student['student_id'];

// Get Applications
$query = "
SELECT
    applications.application_id,
    jobs.job_title,
    jobs.company_name,
    jobs.location,
    jobs.salary,
    applications.status,
    applications.applied_at
FROM applications
JOIN jobs
ON applications.job_id = jobs.job_id
WHERE applications.student_id=?
ORDER BY applications.applied_at DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();

$applications = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>My Applications</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

<div class="d-flex justify-content-between align-items-center mb-4">

<h2>My Applications</h2>

<a href="dashboard.php" class="btn btn-secondary">
Back
</a>

</div>

<?php

if($applications->num_rows>0)
{

?>

<table class="table table-bordered table-hover shadow bg-white">

<thead class="table-dark">

<tr>

<th>#</th>

<th>Job Title</th>

<th>Company</th>

<th>Location</th>

<th>Salary</th>

<th>Status</th>

<th>Applied On</th>

</tr>

</thead>

<tbody>

<?php

$count=1;

while($row=$applications->fetch_assoc())
{

$status=$row['status'];

$badge="secondary";

if($status=="Applied")
$badge="primary";

elseif($status=="Shortlisted")
$badge="warning";

elseif($status=="Interview")
$badge="info";

elseif($status=="Selected")
$badge="success";

elseif($status=="Rejected")
$badge="danger";

?>

<tr>

<td><?= $count++ ?></td>

<td><?= htmlspecialchars($row['job_title']) ?></td>

<td><?= htmlspecialchars($row['company_name']) ?></td>

<td><?= htmlspecialchars($row['location']) ?></td>

<td><?= htmlspecialchars($row['salary']) ?></td>

<td>

<span class="badge bg-<?= $badge ?>">

<?= htmlspecialchars($status) ?>

</span>

</td>

<td><?= htmlspecialchars($row['applied_at']) ?></td>

</tr>

<?php

}

?>

</tbody>

</table>

<?php

}
else
{

?>

<div class="alert alert-warning">

You have not applied for any jobs yet.

</div>

<?php

}

?>

</div>

</body>

</html>