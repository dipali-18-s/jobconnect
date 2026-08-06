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

$stmt = $conn->prepare("SELECT * FROM jobs WHERE job_id=?");
$stmt->bind_param("i", $job_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Job not found.");
}

$job = $result->fetch_assoc();

$message = "";

if (isset($_POST['update'])) {

    $job_title = trim($_POST['job_title']);
    $company_name = trim($_POST['company_name']);
    $location = trim($_POST['location']);
    $salary = trim($_POST['salary']);
    $job_type = trim($_POST['job_type']);
    $description = trim($_POST['description']);

    $stmt = $conn->prepare("
        UPDATE jobs
        SET
            job_title=?,
            company_name=?,
            location=?,
            salary=?,
            job_type=?,
            description=?
        WHERE job_id=?
    ");

    $stmt->bind_param(
        "ssssssi",
        $job_title,
        $company_name,
        $location,
        $salary,
        $job_type,
        $description,
        $job_id
    );

    if ($stmt->execute()) {

        header("Location: my_jobs.php");
        exit();

    } else {

        $message = "<div class='alert alert-danger'>Unable to update job.</div>";

    }
}
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Edit Job</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

<div class="row justify-content-center">

<div class="col-md-8">

<div class="card shadow">

<div class="card-header bg-warning">

<h3>Edit Job</h3>

</div>

<div class="card-body">

<?= $message ?>

<form method="POST">

<div class="mb-3">

<label>Job Title</label>

<input
type="text"
name="job_title"
class="form-control"
value="<?= htmlspecialchars($job['job_title']) ?>"
required>

</div>

<div class="mb-3">

<label>Company Name</label>

<input
type="text"
name="company_name"
class="form-control"
value="<?= htmlspecialchars($job['company_name']) ?>"
required>

</div>

<div class="mb-3">

<label>Location</label>

<input
type="text"
name="location"
class="form-control"
value="<?= htmlspecialchars($job['location']) ?>"
required>

</div>

<div class="mb-3">

<label>Salary</label>

<input
type="text"
name="salary"
class="form-control"
value="<?= htmlspecialchars($job['salary']) ?>"
required>

</div>

<div class="mb-3">

<label>Job Type</label>

<select
name="job_type"
class="form-select">

<option value="Full-Time" <?= $job['job_type']=="Full-Time"?"selected":"" ?>>
Full-Time
</option>

<option value="Part-Time" <?= $job['job_type']=="Part-Time"?"selected":"" ?>>
Part-Time
</option>

<option value="Internship" <?= $job['job_type']=="Internship"?"selected":"" ?>>
Internship
</option>

<option value="Remote" <?= $job['job_type']=="Remote"?"selected":"" ?>>
Remote
</option>

</select>

</div>

<div class="mb-3">

<label>Description</label>

<textarea
name="description"
class="form-control"
rows="6"
required><?= htmlspecialchars($job['description']) ?></textarea>

</div>

<button
type="submit"
name="update"
class="btn btn-success">

Update Job

</button>

<a
href="my_jobs.php"
class="btn btn-secondary">

Cancel

</a>

</form>

</div>

</div>

</div>

</div>

</div>

</body>

</html>