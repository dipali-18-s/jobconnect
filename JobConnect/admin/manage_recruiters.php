<?php
include "../includes/auth.php";
include "../includes/db.php";

if ($_SESSION['role'] != "admin") {
    header("Location: ../login.php");
    exit();
}


// Fetch recruiters

$query = "
SELECT
    users.user_id,
    users.full_name,
    users.email,
    recruiter_profiles.company_name,
    recruiter_profiles.company_website,
    recruiter_profiles.company_location

FROM users

LEFT JOIN recruiter_profiles

ON users.user_id = recruiter_profiles.user_id

WHERE users.role='recruiter'

ORDER BY users.user_id DESC
";


$result = $conn->query($query);


if(!$result)
{
    die("SQL Error: " . $conn->error);
}

?>


<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Manage Recruiters</title>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">


</head>


<body class="bg-light">


<div class="container mt-5">


<div class="d-flex justify-content-between align-items-center mb-4">


<h2>
Manage Recruiters
</h2>


<a href="dashboard.php" class="btn btn-secondary">
Back
</a>


</div>



<?php if(isset($_GET['msg'])) { ?>

<div class="alert alert-success">

Recruiter deleted successfully.

</div>

<?php } ?>




<table class="table table-bordered table-hover shadow bg-white">


<thead class="table-dark">


<tr>

<th>ID</th>

<th>Name</th>

<th>Email</th>

<th>Company</th>

<th>Website</th>

<th>Location</th>

<th>Action</th>


</tr>


</thead>



<tbody>



<?php while($row = $result->fetch_assoc()) { ?>


<tr>


<td>
<?= $row['user_id'] ?>
</td>



<td>
<?= htmlspecialchars($row['full_name']) ?>
</td>



<td>
<?= htmlspecialchars($row['email']) ?>
</td>



<td>
<?= htmlspecialchars($row['company_name'] ?? 'Not Added') ?>
</td>



<td>
<?= htmlspecialchars($row['company_website'] ?? 'Not Added') ?>
</td>



<td>
<?= htmlspecialchars($row['company_location'] ?? 'Not Added') ?>
</td>



<td>


<a
href="delete_recruiter.php?user_id=<?= $row['user_id'] ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this recruiter?')">

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