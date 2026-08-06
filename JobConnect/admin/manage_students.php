<?php
include "../includes/auth.php";
include "../includes/db.php";

if ($_SESSION['role'] != "admin") {
    header("Location: ../login.php");
    exit();
}

$query = "
SELECT
    users.user_id,
    users.full_name,
    users.email,
    student_profiles.phone,
    student_profiles.college,
    student_profiles.course
FROM users
LEFT JOIN student_profiles
ON users.user_id = student_profiles.user_id
WHERE users.role='student'
ORDER BY users.user_id DESC
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Manage Students</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

<div class="d-flex justify-content-between align-items-center mb-4">

<h2>Manage Students</h2>

<a href="dashboard.php" class="btn btn-secondary">
Back
</a>

</div>

<?php if(isset($_GET['msg'])) { ?>

<div class="alert alert-success">

Student deleted successfully.

</div>

<?php } ?>

<table class="table table-bordered table-hover shadow bg-white">

<thead class="table-dark">

<tr>

<th>ID</th>

<th>Name</th>

<th>Email</th>

<th>Phone</th>

<th>College</th>

<th>Course</th>

<th width="120">Action</th>

</tr>

</thead>

<tbody>

<?php while($row=$result->fetch_assoc()) { ?>

<tr>

<td><?= $row['user_id'] ?></td>

<td><?= htmlspecialchars($row['full_name']) ?></td>

<td><?= htmlspecialchars($row['email']) ?></td>

<td><?= htmlspecialchars($row['phone']) ?></td>

<td><?= htmlspecialchars($row['college']) ?></td>

<td><?= htmlspecialchars($row['course']) ?></td>

<td>

<a
href="delete_student.php?user_id=<?= $row['user_id'] ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this student?')">

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