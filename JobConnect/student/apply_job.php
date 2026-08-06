<?php

include "../includes/auth.php";
include "../includes/db.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);


// Check student login
if (!isset($_SESSION['role']) || $_SESSION['role'] != "student") {

    header("Location: ../login.php");
    exit();

}


// DEBUG SESSION (remove later)
echo "<pre>";
print_r($_SESSION);
echo "</pre>";



// Check job id

if (!isset($_GET['job_id'])) {

    die("Invalid Job ID");

}


$job_id = intval($_GET['job_id']);



// Get student profile using logged-in user

$user_id = $_SESSION['user_id'];



$stmt = $conn->prepare(
    "SELECT student_id 
     FROM student_profiles 
     WHERE user_id=?"
);


$stmt->bind_param(
    "i",
    $user_id
);


$stmt->execute();


$result = $stmt->get_result();



if ($result->num_rows == 0) {

    die("No student profile found for user_id = ".$user_id);

}



$student = $result->fetch_assoc();


$student_id = $student['student_id'];




// Check already applied

$stmt = $conn->prepare(
    "SELECT application_id 
     FROM applications 
     WHERE job_id=? 
     AND student_id=?"
);


$stmt->bind_param(
    "ii",
    $job_id,
    $student_id
);


$stmt->execute();


$result = $stmt->get_result();



if ($result->num_rows > 0) {

    echo "
    <script>
    alert('You have already applied for this job');
    window.location='jobs.php';
    </script>
    ";

    exit();

}




// Insert application

$status = "Pending";


$stmt = $conn->prepare(
    "INSERT INTO applications
    (job_id, student_id, status)
    VALUES (?, ?, ?)"
);



$stmt->bind_param(
    "iis",
    $job_id,
    $student_id,
    $status
);



if ($stmt->execute()) {


    echo "
    <script>
    alert('Application submitted successfully');
    window.location='my_applications.php';
    </script>
    ";


}
else {


    die("Database Error: ".$conn->error);


}


?>