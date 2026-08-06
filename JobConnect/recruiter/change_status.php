<?php

include "../includes/auth.php";
include "../includes/db.php";


if ($_SESSION['role'] != "recruiter") {

    header("Location: ../login.php");
    exit();

}


if (!isset($_GET['application_id'])) {

    die("Invalid Application ID.");

}


$application_id = (int)$_GET['application_id'];



// Get application details

$query = "

SELECT
applications.application_id,
applications.status,
users.full_name,
jobs.job_title

FROM applications

JOIN student_profiles
ON applications.student_id = student_profiles.student_id

JOIN users
ON student_profiles.user_id = users.user_id

JOIN jobs
ON applications.job_id = jobs.job_id

WHERE applications.application_id=?

";


$stmt = $conn->prepare($query);

$stmt->bind_param("i",$application_id);

$stmt->execute();


$result = $stmt->get_result();


if($result->num_rows == 0){

    die("Application not found.");

}


$application = $result->fetch_assoc();


$message = "";



// Update status

if(isset($_POST['update'])){


    $status = $_POST['status'];



    $stmt = $conn->prepare(
        "UPDATE applications 
         SET status=? 
         WHERE application_id=?"
    );


    $stmt->bind_param(
        "si",
        $status,
        $application_id
    );



    if($stmt->execute()){


        header("Location: applications.php");

        exit();


    }
    else{


        $message = "
        <div class='alert alert-danger'>
        Database Error: ".$conn->error."
        </div>";

    }


}


?>


<!DOCTYPE html>

<html>

<head>

<title>Update Status</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

</head>


<body class="bg-light">


<div class="container mt-5">


<div class="card shadow">


<div class="card-header bg-primary text-white">

<h3>Update Application Status</h3>

</div>



<div class="card-body">


<?= $message ?>


<p>
<b>Student:</b>
<?= htmlspecialchars($application['full_name']) ?>
</p>


<p>
<b>Job:</b>
<?= htmlspecialchars($application['job_title']) ?>
</p>



<form method="POST">


<label>Status</label>


<select name="status" class="form-select mb-3">


<option value="Pending"
<?= $application['status']=="Pending"?"selected":"" ?>>
Pending
</option>


<option value="Accepted"
<?= $application['status']=="Accepted"?"selected":"" ?>>
Accepted
</option>


<option value="Rejected"
<?= $application['status']=="Rejected"?"selected":"" ?>>
Rejected
</option>


</select>



<button 
name="update"
class="btn btn-success">

Update Status

</button>



<a href="applications.php"
class="btn btn-secondary">

Back

</a>


</form>


</div>


</div>


</div>


</body>

</html>