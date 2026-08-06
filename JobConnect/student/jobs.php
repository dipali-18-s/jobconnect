<?php

include "../includes/auth.php";
include "../includes/db.php";


if (!isset($_SESSION['role']) || $_SESSION['role'] != "student") {

    header("Location: ../login.php");
    exit();

}


$search = "";


if (isset($_GET['search'])) {

    $search = trim($_GET['search']);

}



$sql = "SELECT * FROM jobs
        WHERE job_title LIKE ?
        OR company_name LIKE ?
        OR location LIKE ?
        ORDER BY posted_at DESC";


$stmt = $conn->prepare($sql);


$keyword = "%" . $search . "%";


$stmt->bind_param(
    "sss",
    $keyword,
    $keyword,
    $keyword
);


$stmt->execute();


$result = $stmt->get_result();


?>


<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Browse Jobs</title>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">


</head>


<body class="bg-light">


<div class="container mt-5">


<div class="d-flex justify-content-between align-items-center mb-4">


<h2>
Available Jobs
</h2>


<a href="dashboard.php" class="btn btn-secondary">
Back to Dashboard
</a>


</div>




<form method="GET" class="mb-4">


<div class="input-group">


<input

type="text"

name="search"

class="form-control"

placeholder="Search by Job Title, Company or Location"

value="<?= htmlspecialchars($search) ?>">



<button class="btn btn-primary">

Search

</button>


</div>


</form>





<?php


if ($result->num_rows > 0) {


while ($row = $result->fetch_assoc()) {


?>


<div class="card shadow mb-4">


<div class="card-body">



<h4 class="text-primary">

<?= htmlspecialchars($row['job_title']) ?>

</h4>


<hr>



<p>

<strong>Company :</strong>

<?= htmlspecialchars($row['company_name']) ?>

</p>



<p>

<strong>Location :</strong>

<?= htmlspecialchars($row['location']) ?>

</p>



<p>

<strong>Salary :</strong>

<?= htmlspecialchars($row['salary']) ?>

</p>



<p>

<strong>Job Type :</strong>

<?= htmlspecialchars($row['job_type']) ?>

</p>



<p>

<strong>Description :</strong>

<br>

<?= nl2br(htmlspecialchars($row['description'])) ?>

</p>




<a href="apply_job.php?job_id=<?= $row['job_id']; ?>" 

class="btn btn-success">

Apply Now

</a>



</div>


</div>



<?php


}


}

else {


?>


<div class="alert alert-warning">

No Jobs Found.

</div>


<?php

}


?>


</div>


</body>

</html>