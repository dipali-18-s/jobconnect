<?php

include "../includes/auth.php";
include "../includes/db.php";


if ($_SESSION['role'] != "recruiter") {

    header("Location: ../login.php");
    exit();

}



$query = "

SELECT

applications.application_id,
users.full_name,
jobs.job_title,
applications.status,
applications.applied_at,
student_profiles.resume

FROM applications


JOIN student_profiles

ON applications.student_id = student_profiles.student_id


JOIN users

ON student_profiles.user_id = users.user_id


JOIN jobs

ON applications.job_id = jobs.job_id


JOIN recruiter_profiles

ON jobs.recruiter_id = recruiter_profiles.recruiter_id


WHERE recruiter_profiles.user_id=?


ORDER BY applications.applied_at DESC

";



$stmt = $conn->prepare($query);


$stmt->bind_param(
    "i",
    $_SESSION['user_id']
);


$stmt->execute();


$result = $stmt->get_result();


?>


<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Applications</title>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">


</head>


<body class="bg-light">


<div class="container mt-5">


<div class="d-flex justify-content-between align-items-center mb-4">


<h2>
Job Applications
</h2>


<a href="dashboard.php" class="btn btn-secondary">
Dashboard
</a>


</div>



<table class="table table-bordered table-hover shadow bg-white">


<thead class="table-dark">


<tr>

<th>ID</th>

<th>Student</th>

<th>Job</th>

<th>Resume</th>

<th>Status</th>

<th>Applied On</th>

<th>Action</th>

</tr>


</thead>



<tbody>


<?php while($row = $result->fetch_assoc()) { ?>


<tr>


<td>
<?= $row['application_id'] ?>
</td>



<td>
<?= htmlspecialchars($row['full_name']) ?>
</td>



<td>
<?= htmlspecialchars($row['job_title']) ?>
</td>




<td>


<?php if(!empty($row['resume'])) { ?>


<a 
href="../uploads/resumes/<?= htmlspecialchars($row['resume']) ?>"
target="_blank"
class="btn btn-primary btn-sm">

View Resume

</a>


<?php } else { ?>


<span class="text-danger">
No Resume
</span>


<?php } ?>


</td>





<td>


<?php

$badge="secondary";


if($row['status']=="Pending")
$badge="warning";


elseif($row['status']=="Accepted")
$badge="success";


elseif($row['status']=="Rejected")
$badge="danger";


?>


<span class="badge bg-<?= $badge ?>">

<?= htmlspecialchars($row['status']) ?>

</span>


</td>




<td>

<?= $row['applied_at'] ?>

</td>




<td>


<a

href="change_status.php?application_id=<?= $row['application_id'] ?>"

class="btn btn-warning btn-sm">

Update Status

</a>


</td>


</tr>


<?php } ?>


</tbody>


</table>


</div>


</body>

</html>