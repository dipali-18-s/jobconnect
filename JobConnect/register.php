<?php
include "includes/db.php";

$message = "";

if(isset($_POST['register']))
{
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Check if email already exists
    $check = $conn->prepare("SELECT user_id FROM users WHERE email=?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if($check->num_rows > 0)
    {
        $message = "<div class='alert alert-danger'>Email already registered!</div>";
    }
    else
    {
        // Hash Password
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // Insert into users table
        $stmt = $conn->prepare("INSERT INTO users(full_name,email,password,role) VALUES(?,?,?,?)");
        $stmt->bind_param("ssss", $full_name, $email, $hash, $role);

        if($stmt->execute())
        {
            // Get newly inserted user ID
            $userId = $conn->insert_id;

            // Create corresponding profile
            if($role == "student")
            {
                $profile = $conn->prepare("INSERT INTO student_profiles(user_id) VALUES(?)");
                $profile->bind_param("i", $userId);
                $profile->execute();
            }
            else if($role == "recruiter")
            {
                $profile = $conn->prepare("INSERT INTO recruiter_profiles(user_id) VALUES(?)");
                $profile->bind_param("i", $userId);
                $profile->execute();
            }

            $message = "<div class='alert alert-success'>Registration Successful! <a href='login.php'>Login Now</a></div>";
        }
        else
        {
            $message = "<div class='alert alert-danger'>Something went wrong.</div>";
        }
    }
}
?>

<?php include "includes/header.php"; ?>
<?php include "includes/navbar.php"; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow p-4">

                <h2 class="text-center mb-4">Create Account</h2>

                <?= $message ?>

                <form method="POST">

                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input
                            type="text"
                            name="full_name"
                            class="form-control"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Register As</label>

                        <select
                            name="role"
                            class="form-select"
                            required>

                            <option value="student">Student</option>
                            <option value="recruiter">Recruiter</option>

                        </select>
                    </div>

                    <button
                        type="submit"
                        name="register"
                        class="btn btn-primary w-100">

                        Register

                    </button>

                </form>

            </div>

        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>