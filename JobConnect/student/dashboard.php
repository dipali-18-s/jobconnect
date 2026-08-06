<?php

include "../includes/auth.php";
include "../includes/db.php";


if (!isset($_SESSION['role']) || $_SESSION['role'] != "student") {

    header("Location: ../login.php");
    exit();

}


$user_id = $_SESSION['user_id'];


// Total jobs

$total_jobs = $conn->query(
    "SELECT COUNT(*) AS total FROM jobs"
)->fetch_assoc()['total'];


// Total applications

$stmt = $conn->prepare(
    "SELECT COUNT(*) AS total 
     FROM applications a
     JOIN student_profiles s 
     ON a.student_id=s.student_id
     WHERE s.user_id=?"
);

$stmt->bind_param("i",$user_id);
$stmt->execute();

$applied = $stmt->get_result()->fetch_assoc()['total'];


// Recent jobs

$query = "

SELECT 
job_id,
job_title,
company_name

FROM jobs

ORDER BY posted_at DESC

LIMIT 5

";


$result = $conn->query($query);


?>


<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Student Dashboard</title>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">


<style>

body{
    background:#f5f7fb;
}


.sidebar{

    width:240px;
    height:100vh;
    position:fixed;
    background:#1468e8;
    color:white;
    padding-top:20px;

}


.sidebar a{

    display:block;
    color:white;
    padding:15px;
    text-decoration:none;

}


.sidebar a:hover{

    background:#0d56c7;

}


.content{

    margin-left:240px;
    padding:30px;

}


.card-box{

    background:white;
    padding:25px;
    border-radius:15px;
    box-shadow:0 5px 15px #ddd;
    text-align:center;

}


</style>


</head>


<body>



<div class="sidebar">


<h2 class="text-center">
JobConnect
</h2>

<hr>


<a href="dashboard.php">
Dashboard
</a>

<a href="view_profile.php" class="btn btn-info">
View Profile
</a>

<a href="profile.php">
My Profile
</a>


<a href="jobs.php">
Browse Jobs
</a>


<a href="upload_resume.php">
Upload Resume
</a>


<a href="my_applications.php">
Applications
</a>


<a href="../logout.php">
Logout
</a>


</div>




<div class="content">


<h1>
Welcome, <?= htmlspecialchars($_SESSION['name']); ?> 👋
</h1>



<div class="row mt-4">


<div class="col-md-4">

<div class="card-box">

<h1>
<?= $total_jobs ?>
</h1>

<p>
Total Jobs
</p>

</div>

</div>



<div class="col-md-4">

<div class="card-box">

<h1>
<?= $applied ?>
</h1>

<p>
Applied
</p>

</div>

</div>



<div class="col-md-4">

<div class="card-box">

<h1>
5
</h1>

<p>
Saved Jobs
</p>

</div>

</div>


</div>




<div class="card mt-5 shadow">


<div class="card-header">

<h3>
Recent Jobs
</h3>

</div>



<div class="card-body">


<table class="table">


<tr>

<th>
Job
</th>

<th>
Company
</th>

<th>
Action
</th>

</tr>



<?php while($row=$result->fetch_assoc()) { ?>


<tr>


<td>

<?= htmlspecialchars($row['job_title']); ?>

</td>



<td>

<?= htmlspecialchars($row['company_name']); ?>

</td>



<td>


<a href="apply_job.php?job_id=<?= $row['job_id']; ?>"
class="btn btn-success">

Apply

</a>


</td>


</tr>


<?php } ?>


</table>


</div>


</div>



</div>



</body>

</html>