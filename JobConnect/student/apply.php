<?php
include "../includes/auth.php";
include "../includes/db.php";

if($_SESSION['role'] != "student")
{
    header("Location: ../login.php");
    exit();
}

if(!isset($_GET['job_id']))
{
    die("Invalid Job");
}

$job_id = (int)$_GET['job_id'];

// Get student profile ID
$stmt = $conn->prepare("SELECT student_id FROM student_profiles WHERE user_id=?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0)
{
    die("Student profile not found.");
}

$student = $result->fetch_assoc();
$student_id = $student['student_id'];

// Check if already applied
$stmt = $conn->prepare("SELECT application_id FROM applications WHERE job_id=? AND student_id=?");
$stmt->bind_param("ii", $job_id, $student_id);
$stmt->execute();
$stmt->store_result();

if($stmt->num_rows > 0)
{
    echo "<script>
            alert('You have already applied for this job.');
            window.location='jobs.php';
          </script>";
    exit();
}

// Apply
$stmt = $conn->prepare("INSERT INTO applications(job_id, student_id) VALUES(?, ?)");
$stmt->bind_param("ii", $job_id, $student_id);

if($stmt->execute())
{
    echo "<script>
            alert('Application submitted successfully!');
            window.location='jobs.php';
          </script>";
}
else
{
    echo "<script>
            alert('Something went wrong.');
            window.location='jobs.php';
          </script>";
}
?>