<?php
include "../includes/auth.php";
include "../includes/db.php";

if ($_SESSION['role'] != "student") {
    header("Location: ../login.php");
    exit();
}

$message = "";

if (isset($_POST['upload'])) {

    if (!isset($_FILES['resume'])) {
        $message = "<div class='alert alert-danger'>No file selected.</div>";
    } elseif ($_FILES['resume']['error'] != UPLOAD_ERR_OK) {

        $message = "<div class='alert alert-danger'>
            Upload Error Code: " . $_FILES['resume']['error'] . "
        </div>";

    } else {

        $fileName = $_FILES['resume']['name'];
        $tmpName = $_FILES['resume']['tmp_name'];
        $fileSize = $_FILES['resume']['size'];

        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Allow only PDF
        if ($extension != "pdf") {

            $message = "<div class='alert alert-warning'>
                Only PDF files are allowed.
            </div>";

        } elseif ($fileSize > 5 * 1024 * 1024) {

            $message = "<div class='alert alert-warning'>
                File size should be less than 5 MB.
            </div>";

        } else {

            // Generate unique filename
            $newName = time() . "_" . preg_replace("/[^a-zA-Z0-9._-]/", "_", $fileName);

            // Absolute upload directory
            $uploadDir = dirname(__DIR__) . DIRECTORY_SEPARATOR .
                         "uploads" . DIRECTORY_SEPARATOR .
                         "resumes" . DIRECTORY_SEPARATOR;

            // Create folder if not exists
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $destination = $uploadDir . $newName;

            if (!is_uploaded_file($tmpName)) {

                $message = "<div class='alert alert-danger'>
                    Invalid uploaded file.
                </div>";

            } elseif (move_uploaded_file($tmpName, $destination)) {

                $stmt = $conn->prepare("UPDATE student_profiles SET resume=? WHERE user_id=?");
                $stmt->bind_param("si", $newName, $_SESSION['user_id']);

                if ($stmt->execute()) {

                    $message = "<div class='alert alert-success'>
                        Resume uploaded successfully.
                    </div>";

                } else {

                    $message = "<div class='alert alert-danger'>
                        Database Error: " . htmlspecialchars($conn->error) . "
                    </div>";

                }

            } else {

                $message = "<div class='alert alert-danger'>
                    Unable to move uploaded file.<br><br>

                    <strong>Temp File:</strong><br>
                    $tmpName<br><br>

                    <strong>Destination:</strong><br>
                    $destination
                </div>";

            }
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Upload Resume</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="row justify-content-center">

        <div class="col-md-6">

            <div class="card shadow">

                <div class="card-header bg-primary text-white">

                    <h3 class="mb-0">Upload Resume</h3>

                </div>

                <div class="card-body">

                    <?= $message ?>

                    <form method="POST" enctype="multipart/form-data">

                        <div class="mb-3">

                            <label class="form-label">
                                Select Resume (PDF Only)
                            </label>

                            <input
                                type="file"
                                name="resume"
                                class="form-control"
                                accept=".pdf"
                                required>

                        </div>

                        <button
                            type="submit"
                            name="upload"
                            class="btn btn-primary w-100">

                            Upload Resume

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

</body>

</html>