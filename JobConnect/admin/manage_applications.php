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
    applications.application_id,
    users.full_name,
    jobs.job_title,
    jobs.company_name,
    applications.status,
    applications.applied_at
FROM applications

JOIN student_profiles
ON applications.student_id = student_profiles.student_id

JOIN users
ON student_profiles.user_id = users.user_id

JOIN jobs
ON applications.job_id = jobs.job_id

WHERE
users.full_name LIKE ?
OR jobs.job_title LIKE ?
OR jobs.company_name LIKE ?
OR applications.status LIKE ?

ORDER BY applications.applied_at DESC
";

$stmt = $conn->prepare($query);

$keyword = "%".$search."%";

$stmt->bind_param(
    "ssss",
    $keyword,
    $keyword,
    $keyword,
    $keyword
);

$stmt->execute();

$result = $stmt->get_result();
?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Manage Applications</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

<div class="d-flex justify-content-between align-items-center mb-4">

<h2>Manage Applications</h2>

<a href="dashboard.php" class="btn btn-secondary">
Dashboard
</a>

</div>

<?php if(isset($_GET['msg'])){ ?>

<div class="alert alert-success">

Application deleted successfully.

</div>

<?php } ?>

<form method="GET" class="mb-4">

<div class="input-group">

<input
type="text"
name="search"
class="form-control"
placeholder="Search Student, Job, Company or Status"
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

<th>Student</th>

<th>Job</th>

<th>Company</th>

<th>Status</th>

<th>Applied On</th>

<th>Action</th>

</tr>

</thead>

<tbody>

<?php while($row=$result->fetch_assoc()){ ?>

<tr>

<td><?= $row['application_id'] ?></td>

<td><?= htmlspecialchars($row['full_name']) ?></td>

<td><?= htmlspecialchars($row['job_title']) ?></td>

<td><?= htmlspecialchars($row['company_name']) ?></td>

<td>

<?php

$color="secondary";

switch($row['status'])
{
    case "Applied":
        $color="primary";
        break;

    case "Shortlisted":
        $color="warning";
        break;

    case "Interview":
        $color="info";
        break;

    case "Selected":
        $color="success";
        break;

    case "Rejected":
        $color="danger";
        break;
}

?>

<span class="badge bg-<?= $color ?>">
<?= htmlspecialchars($row['status']) ?>
</span>

</td>

<td><?= $row['applied_at'] ?></td>

<td>

<a
href="delete_application.php?application_id=<?= $row['application_id'] ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this application?')">

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