<?php
include "../includes/auth.php";
include "../includes/db.php";

if ($_SESSION['role'] != "admin") {
    header("Location: ../login.php");
    exit();
}

$search = "";

if(isset($_GET['search'])){
    $search = trim($_GET['search']);
}

$query = "
SELECT
    jobs.job_id,
    jobs.job_title,
    jobs.company_name,
    jobs.location,
    jobs.salary,
    jobs.job_type,
    jobs.posted_at,
    users.full_name AS recruiter_name
FROM jobs
LEFT JOIN recruiter_profiles
ON jobs.recruiter_id = recruiter_profiles.recruiter_id
LEFT JOIN users
ON recruiter_profiles.user_id = users.user_id
WHERE
jobs.job_title LIKE ?
OR jobs.company_name LIKE ?
OR jobs.location LIKE ?
ORDER BY jobs.posted_at DESC
";

$stmt = $conn->prepare($query);

$keyword = "%".$search."%";

$stmt->bind_param("sss",$keyword,$keyword,$keyword);

$stmt->execute();

$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Manage Jobs</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

<div class="d-flex justify-content-between align-items-center mb-4">

<h2>Manage Jobs</h2>

<a href="dashboard.php" class="btn btn-secondary">
Dashboard
</a>

</div>

<?php if(isset($_GET['msg'])){ ?>

<div class="alert alert-success">

Job deleted successfully.

</div>

<?php } ?>

<form method="GET" class="mb-4">

<div class="input-group">

<input
type="text"
name="search"
class="form-control"
placeholder="Search Job, Company or Location"
value="<?= htmlspecialchars($search) ?>">

<button class="btn btn-primary">

Search

</button>

</div>

</form>

<table class="table table-bordered table-hover shadow bg-white">

<thead class="table-dark">

<tr>

<th>ID</th>

<th>Job</th>

<th>Company</th>

<th>Recruiter</th>

<th>Location</th>

<th>Salary</th>

<th>Type</th>

<th>Date</th>

<th>Action</th>

</tr>

</thead>

<tbody>

<?php while($row=$result->fetch_assoc()){ ?>

<tr>

<td><?= $row['job_id'] ?></td>

<td><?= htmlspecialchars($row['job_title']) ?></td>

<td><?= htmlspecialchars($row['company_name']) ?></td>

<td><?= htmlspecialchars($row['recruiter_name']) ?></td>

<td><?= htmlspecialchars($row['location']) ?></td>

<td><?= htmlspecialchars($row['salary']) ?></td>

<td><?= htmlspecialchars($row['job_type']) ?></td>

<td><?= $row['posted_at'] ?></td>

<td>

<a
href="delete_job.php?job_id=<?= $row['job_id'] ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this job?');">

Delete

</a>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</body>

</html>