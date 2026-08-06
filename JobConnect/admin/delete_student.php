<?php
include "../includes/auth.php";
include "../includes/db.php";

if ($_SESSION['role'] != "admin") {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['user_id'])) {
    die("Invalid Request");
}

$user_id = (int)$_GET['user_id'];

// Get student_id
$stmt = $conn->prepare("SELECT student_id FROM student_profiles WHERE user_id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0){

    $student = $result->fetch_assoc();
    $student_id = $student['student_id'];

    // Delete applications
    $stmt = $conn->prepare("DELETE FROM applications WHERE student_id=?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();

    // Delete student profile
    $stmt = $conn->prepare("DELETE FROM student_profiles WHERE student_id=?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
}

// Delete user
$stmt = $conn->prepare("DELETE FROM users WHERE user_id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

header("Location: manage_students.php?msg=deleted");
exit();
?>