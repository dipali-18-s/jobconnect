<?php
include "../includes/auth.php";
include "../includes/db.php";

if($_SESSION['role'] != "student"){
    header("Location: ../login.php");
    exit();
}

$userId = $_SESSION['user_id'];

$message = "";

// Save Profile
if(isset($_POST['save']))
{
    $phone = trim($_POST['phone']);
    $college = trim($_POST['college']);
    $course = trim($_POST['course']);
    $skills = trim($_POST['skills']);


    // Check profile exists
    $check = $conn->prepare(
        "SELECT * FROM student_profiles WHERE user_id=?"
    );

    $check->bind_param("i",$userId);
    $check->execute();

    $result = $check->get_result();


    if($result->num_rows > 0)
    {
        // Update existing profile

        $stmt = $conn->prepare("
            UPDATE student_profiles
            SET phone=?, college=?, course=?, skills=?
            WHERE user_id=?
        ");

        $stmt->bind_param(
            "ssssi",
            $phone,
            $college,
            $course,
            $skills,
            $userId
        );
    }
    else
    {
        // Insert new profile

        $stmt = $conn->prepare("
            INSERT INTO student_profiles
            (user_id, phone, college, course, skills)
            VALUES (?,?,?,?,?)
        ");

        $stmt->bind_param(
            "issss",
            $userId,
            $phone,
            $college,
            $course,
            $skills
        );
    }


    if($stmt->execute())
    {
        $message = "
        <div class='alert alert-success'>
        Profile Updated Successfully!
        </div>";
    }
}


// Fetch Profile

$stmt = $conn->prepare(
    "SELECT * FROM student_profiles WHERE user_id=?"
);

$stmt->bind_param("i",$userId);
$stmt->execute();

$profile = $stmt->get_result()->fetch_assoc();

?>


<!DOCTYPE html>
<html>

<head>

<title>Student Profile</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

</head>


<body>


<div class="container mt-5">


<div class="card shadow p-4">


<h2 class="text-center mb-4">
My Profile
</h2>


<?= $message ?>


<form method="POST">


<div class="mb-3">

<label class="form-label">
Phone Number
</label>

<input 
type="text"
class="form-control"
name="phone"
value="<?= htmlspecialchars($profile['phone'] ?? '') ?>"
required>

</div>



<div class="mb-3">

<label class="form-label">
College
</label>

<input 
type="text"
class="form-control"
name="college"
value="<?= htmlspecialchars($profile['college'] ?? '') ?>"
required>

</div>




<div class="mb-3">

<label class="form-label">
Course
</label>

<input 
type="text"
class="form-control"
name="course"
value="<?= htmlspecialchars($profile['course'] ?? '') ?>"
required>

</div>




<div class="mb-3">

<label class="form-label">
Skills
</label>

<textarea
class="form-control"
rows="4"
name="skills"><?= htmlspecialchars($profile['skills'] ?? '') ?></textarea>

</div>



<?php if(!empty($profile['resume'])) { ?>

<div class="mb-3">

<label class="form-label">
Resume
</label>

<br>

<a href="../uploads/resumes/<?= $profile['resume'] ?>"
class="btn btn-success"
target="_blank">

View Resume

</a>

</div>

<?php } ?>



<button 
class="btn btn-primary w-100"
name="save">

Save Profile

</button>



</form>


</div>


</div>


</body>

</html>