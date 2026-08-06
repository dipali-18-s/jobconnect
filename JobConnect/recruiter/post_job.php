<?php
include "../includes/auth.php";
include "../includes/db.php";

$message="";

if(isset($_POST['post_job']))
{
    $title=$_POST['title'];
    $company=$_POST['company'];
    $location=$_POST['location'];
    $salary=$_POST['salary'];
    $type=$_POST['type'];
    $description=$_POST['description'];

    // Get recruiter profile ID
    $stmt = $conn->prepare("SELECT recruiter_id FROM recruiter_profiles WHERE user_id=?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 1)
    {
        $recruiter = $result->fetch_assoc();

        $stmt=$conn->prepare("INSERT INTO jobs(recruiter_id,job_title,company_name,location,salary,job_type,description)
        VALUES(?,?,?,?,?,?,?)");

        $stmt->bind_param(
            "issssss",
            $recruiter['recruiter_id'],
            $title,
            $company,
            $location,
            $salary,
            $type,
            $description
        );

        if($stmt->execute())
        {
            $message="<div class='alert alert-success'>Job Posted Successfully!</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Post Job</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<h2>Post New Job</h2>

<?= $message ?>

<form method="POST">

<input
class="form-control mb-3"
name="title"
placeholder="Job Title"
required>

<input
class="form-control mb-3"
name="company"
placeholder="Company Name"
required>

<input
class="form-control mb-3"
name="location"
placeholder="Location"
required>

<input
class="form-control mb-3"
name="salary"
placeholder="Salary"
required>

<select
class="form-select mb-3"
name="type">

<option>Full Time</option>
<option>Part Time</option>
<option>Internship</option>

</select>

<textarea
class="form-control mb-3"
name="description"
rows="5"
placeholder="Job Description"></textarea>

<button
class="btn btn-primary"
name="post_job">

Post Job

</button>

</form>

</div>

</body>
</html>