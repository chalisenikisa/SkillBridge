<?php
session_start();
include('includes/config.php');

// Redirect if not logged in
if (!isset($_SESSION['alogin']) || strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
}

$regid = intval($_GET['id']); // Get student ID from URL

if (isset($_POST['submit'])) {
    $studentname = trim($_POST['studentname']);
    $cgpa = trim($_POST['cgpa']);

    // Validate CGPA
    if (!is_numeric($cgpa) || $cgpa < 0 || $cgpa > 10) {
        echo '<script>alert("Invalid CGPA! Please enter a number between 0 and 10.");</script>';
    } else {
        $photo_uploaded = false;
        $new_photo_name = '';

        // Handle photo upload
        if (!empty($_FILES["photo"]["name"])) {
            $photo = $_FILES["photo"];
            $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
            $max_size = 2 * 1024 * 1024; // 2MB

            if (in_array($photo["type"], $allowed_types) && $photo["size"] <= $max_size) {
                $extension = pathinfo($photo["name"], PATHINFO_EXTENSION);
                $new_photo_name = uniqid("photo_", true) . "." . $extension;
                $upload_path = "studentphoto/" . $new_photo_name;

                if (move_uploaded_file($photo["tmp_name"], $upload_path)) {
                    $photo_uploaded = true;
                } else {
                    echo '<script>alert("Failed to upload photo.");</script>';
                }
            } else {
                echo '<script>alert("Only JPG, JPEG, or PNG files under 2MB are allowed.");</script>';
            }
        }

        // Update using prepared statement
        if ($photo_uploaded) {
            $stmt = $con->prepare("UPDATE students SET studentName=?, studentPhoto=?, cgpa=? WHERE StudentRegno=?");
            $stmt->bind_param("sssi", $studentname, $new_photo_name, $cgpa, $regid);
        } else {
            $stmt = $con->prepare("UPDATE students SET studentName=?, cgpa=? WHERE StudentRegno=?");
            $stmt->bind_param("ssi", $studentname, $cgpa, $regid);
        }

        if ($stmt->execute()) {
            echo '<script>alert("Student Record updated Successfully !!"); window.location.href="manage-students.php";</script>';
        } else {
            echo '<script>alert("Error: Student Record not updated !!"); window.location.href="manage-students.php";</script>';
        }
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Edit Student Profile</title>
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <link href="../assets/css/style.css" rel="stylesheet" />
</head>

<body>
<?php include('includes/header.php'); ?>
<?php if ($_SESSION['alogin'] != "") include('includes/menubar.php'); ?>

<div class="content-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-head-line">Edit Student Profile</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">Update Student</div>
                    <div class="panel-body">
                        <form method="post" enctype="multipart/form-data">
                            <?php
                            $sql = mysqli_query($con, "SELECT * FROM students WHERE StudentRegno='$regid'");
                            if ($row = mysqli_fetch_array($sql)) {
                            ?>
                                <div class="form-group">
                                    <label for="studentname">Student Name</label>
                                    <input type="text" class="form-control" name="studentname" value="<?php echo htmlentities($row['studentName']); ?>" required />
                                </div>

                                <div class="form-group">
                                    <label for="studentregno">Student Reg No</label>
                                    <input type="text" class="form-control" value="<?php echo htmlentities($row['StudentRegno']); ?>" readonly />
                                </div>

                                <div class="form-group">
                                    <label for="pincode">Pincode</label>
                                    <input type="text" class="form-control" value="<?php echo htmlentities($row['pincode']); ?>" readonly />
                                </div>

                                <div class="form-group">
                                    <label for="cgpa">CGPA</label>
                                    <input type="text" class="form-control" name="cgpa" value="<?php echo htmlentities($row['cgpa']); ?>" required />
                                </div>

                                <div class="form-group">
                                    <label>Current Photo</label><br />
                                    <?php if ($row['studentPhoto'] == "") { ?>
                                        <img src="studentphoto/noimage.png" width="200" height="200">
                                    <?php } else { ?>
                                        <img src="studentphoto/<?php echo htmlentities($row['studentPhoto']); ?>" width="200" height="200">
                                    <?php } ?>
                                </div>

                                <div class="form-group">
                                    <label for="photo">Upload New Photo (optional)</label>
                                    <input type="file" class="form-control" name="photo" />
                                </div>

                                <button type="submit" name="submit" class="btn btn-primary">Update</button>
                            <?php
                            } else {
                                echo "<p class='text-danger'>Student record not found.</p>";
                            }
                            ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
<script src="../assets/js/jquery-1.11.1.js"></script>
<script src="../assets/js/bootstrap.js"></script>
</body>
</html>
