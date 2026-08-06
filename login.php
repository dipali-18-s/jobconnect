<?php
session_start();
include "includes/db.php";

$message = "";

if(isset($_POST['login']))
{
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s",$email);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows==1)
    {
        $user = $result->fetch_assoc();

        if(password_verify($password,$user['password']))
        {
            $_SESSION['user_id']=$user['user_id'];
            $_SESSION['name']=$user['full_name'];
            $_SESSION['role']=$user['role'];

            if($user['role']=="student")
            {
                header("Location: student/dashboard.php");
            }
            elseif($user['role']=="recruiter")
            {
                header("Location: recruiter/dashboard.php");
            }
            else
            {
                header("Location: admin/dashboard.php");
            }

            exit();
        }
        else
        {
            $message="<div class='alert alert-danger'>Incorrect Password</div>";
        }
    }
    else
    {
        $message="<div class='alert alert-danger'>Email not found</div>";
    }
}
?>

<?php include "includes/header.php"; ?>
<?php include "includes/navbar.php"; ?>

<div class="container mt-5">
<div class="row justify-content-center">
<div class="col-md-5">

<div class="card shadow p-4">

<h3 class="text-center">Login</h3>

<?= $message ?>

<form method="POST">

<div class="mb-3">
<label>Email</label>
<input type="email" class="form-control" name="email" required>
</div>

<div class="mb-3">
<label>Password</label>
<input type="password" class="form-control" name="password" required>
</div>

<button class="btn btn-primary w-100" name="login">
Login
</button>

</form>

</div>

</div>
</div>
</div>

<?php include "includes/footer.php"; ?>