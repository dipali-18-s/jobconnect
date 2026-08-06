<?php
include "../includes/auth.php";
include "../includes/db.php";

if($_SESSION['role'] != "student"){
    header("Location: ../login.php");
    exit();
}

$userId = $_SESSION['user_id'];


// Fetch student details

$query = "
SELECT 
users.full_name,
users.email,
student_profiles.phone,
student_profiles.college,
student_profiles.course,
student_profiles.skills,
student_profiles.resume

FROM users

LEFT JOIN student_profiles

ON users.user_id = student_profiles.user_id

WHERE users.user_id=?
";


$stmt = $conn->prepare($query);

$stmt->bind_param("i",$userId);

$stmt->execute();

$result = $stmt->get_result();

$student = $result->fetch_assoc();

?>


<!DOCTYPE html>
<html>

<head>

<title>My Profile</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

</head>


<body class="bg-light">


<div class="container mt-5">


<div class="card shadow p-4">


<h2 class="text-center mb-4">
My Profile
</h2>


<table class="table table-bordered">


<tr>
<th>Name</th>
<td>
<?= htmlspecialchars($student['full_name']) ?>
</td>
</tr>


<tr>
<th>Email</th>
<td>
<?= htmlspecialchars($student['email']) ?>
</td>
</tr>


<tr>
<th>Phone</th>
<td>
<?= htmlspecialchars($student['phone'] ?? 'Not Added') ?>
</td>
</tr>


<tr>
<th>College</th>
<td>
<?= htmlspecialchars($student['college'] ?? 'Not Added') ?>
</td>
</tr>


<tr>
<th>Course</th>
<td>
<?= htmlspecialchars($student['course'] ?? 'Not Added') ?>
</td>
</tr>


<tr>
<th>Skills</th>
<td>
<?= htmlspecialchars($student['skills'] ?? 'Not Added') ?>
</td>
</tr>


<tr>

<th>Resume</th>

<td>

<?php if(!empty($student['resume'])) { ?>

<a href="../uploads/resumes/<?= $student['resume'] ?>"
target="_blank"
class="btn btn-success btn-sm">

View Resume

</a>

<?php }
else
{
echo "Not Uploaded";
}

?>

</td>

</tr>


</table>


<a href="profile.php" class="btn btn-primary">
Edit Profile
</a>


</div>


</div>


</body>

</html>