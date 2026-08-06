<?php
include "../includes/auth.php";
include "../includes/db.php";

if($_SESSION['role'] != "recruiter"){
    header("Location: ../login.php");
    exit();
}

$userId = $_SESSION['user_id'];

$message = "";


// Save Profile

if(isset($_POST['save']))
{
    $company_name = trim($_POST['company_name']);
    $company_website = trim($_POST['company_website']);
    $company_location = trim($_POST['company_location']);


    // Check profile exists

    $check = $conn->prepare(
        "SELECT * FROM recruiter_profiles WHERE user_id=?"
    );

    $check->bind_param("i",$userId);
    $check->execute();

    $result = $check->get_result();


    if($result->num_rows > 0)
    {

        // Update

        $stmt = $conn->prepare("
        UPDATE recruiter_profiles
        SET company_name=?,
            company_website=?,
            company_location=?
        WHERE user_id=?
        ");


        $stmt->bind_param(
            "sssi",
            $company_name,
            $company_website,
            $company_location,
            $userId
        );

    }
    else
    {

        // Insert

        $stmt = $conn->prepare("
        INSERT INTO recruiter_profiles
        (user_id, company_name, company_website, company_location)
        VALUES(?,?,?,?)
        ");


        $stmt->bind_param(
            "isss",
            $userId,
            $company_name,
            $company_website,
            $company_location
        );

    }


    if($stmt->execute())
    {
        $message="
        <div class='alert alert-success'>
        Profile Updated Successfully
        </div>";
    }

}



// Fetch profile

$stmt = $conn->prepare(
"SELECT * FROM recruiter_profiles WHERE user_id=?"
);

$stmt->bind_param("i",$userId);
$stmt->execute();

$profile = $stmt->get_result()->fetch_assoc();

?>


<!DOCTYPE html>
<html>

<head>

<title>Recruiter Profile</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

</head>


<body>


<div class="container mt-5">


<div class="card shadow p-4">


<h2 class="text-center">
Company Profile
</h2>


<?= $message ?>


<form method="POST">


<label>Company Name</label>

<input 
class="form-control mb-3"
name="company_name"
value="<?= htmlspecialchars($profile['company_name'] ?? '') ?>"
required>



<label>Company Website</label>

<input 
class="form-control mb-3"
name="company_website"
value="<?= htmlspecialchars($profile['company_website'] ?? '') ?>">



<label>Company Location</label>

<input 
class="form-control mb-3"
name="company_location"
value="<?= htmlspecialchars($profile['company_location'] ?? '') ?>">



<button class="btn btn-primary" name="save">

Save Profile

</button>


</form>


</div>


</div>


</body>

</html>