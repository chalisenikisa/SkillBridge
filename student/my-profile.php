<?php
session_start();
include('includes/config.php');
error_reporting(0);

if (empty($_SESSION['login'])) {
    header('Location:index.php');
    exit();
}

$msg = '';
$err = '';

if (isset($_POST['submit'])) {
    $studentName = trim($_POST['studentname']);
    $cgpa = trim($_POST['cgpa']);
    $regno = $_SESSION['login'];

    if (!empty($_FILES['photo']['name'])) {
        $photoName = basename($_FILES['photo']['name']);
        $targetDir = 'studentphoto/';
        $targetFile = $targetDir . $photoName;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
            $stmt = $con->prepare("UPDATE students SET studentName = ?, studentPhoto = ?, cgpa = ? WHERE StudentRegno = ?");
            $stmt->bind_param("ssis", $studentName, $photoName, $cgpa, $regno);
        } else {
            $err = "Failed to upload photo.";
        }
    } else {
        $stmt = $con->prepare("UPDATE students SET studentName = ?, cgpa = ? WHERE StudentRegno = ?");
        $stmt->bind_param("sis", $studentName, $cgpa, $regno);
    }

    if (empty($err) && isset($stmt)) {
        if ($stmt->execute()) {
            $msg = "Profile updated successfully!";
        } else {
            $err = "Error updating profile. Please try again.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Student | My Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />

    <style>
        body {
            margin: 0;
            padding: 0;
        }
        .wrapper {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 220px;
            background-color: #f8f9fa;
            padding: 20px;
            border-right: 1px solid #ddd;
        }
        .sidebar a {
            display: block;
            padding: 10px;
            color: #333;
            text-decoration: none;
            margin-bottom: 5px;
        }
        .sidebar a:hover {
            background-color: #e9ecef;
        }
        .content {
            flex: 1;
            padding: 20px;
        }
        .page-head-line {
            border-bottom: 2px solid #ccc;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
    </style>
</head>
<body>

<?php include('includes/header.php'); ?>

<div class="wrapper">
    <!-- Sidebar -->
    <div class="sidebar">
        <a href="enroll-course.php">Enroll for Course</a>
        <a href="enroll-history.php">Enroll History</a>
        <a href="my-profile.php">My Profile</a>
        <a href="change-password.php">Change Password</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Main content -->
    <div class="content">
        <h2 class="page-head-line">My Profile</h2>

        <?php
        $stmt = $con->prepare("SELECT studentName, StudentRegno, pincode, cgpa, studentPhoto FROM students WHERE StudentRegno = ?");
        $stmt->bind_param("s", $_SESSION['login']);
        $stmt->execute();
        $stmt->bind_result($currName, $currRegno, $currPin, $currCgpa, $currPhoto);
        $stmt->fetch();
        $stmt->close();
        ?>

        <?php if ($msg): ?>
            <div class="alert alert-success"><?php echo htmlentities($msg); ?></div>
        <?php elseif ($err): ?>
            <div class="alert alert-danger"><?php echo htmlentities($err); ?></div>
        <?php endif; ?>

        <div class="panel panel-default">
            <div class="panel-heading">Update Profile</div>
            <div class="panel-body">
                <form method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Student Name</label>
                        <input type="text" name="studentname" class="form-control" value="<?php echo htmlentities($currName); ?>" required />
                    </div>

                    <div class="form-group">
                        <label>Registration No</label>
                        <input type="text" class="form-control" value="<?php echo htmlentities($currRegno); ?>" readonly />
                    </div>

                    <div class="form-group">
                        <label>Pincode</label>
                        <input type="text" class="form-control" value="<?php echo htmlentities($currPin); ?>" readonly />
                    </div>

                    <div class="form-group">
                        <label>CGPA</label>
                        <input type="text" name="cgpa" class="form-control" value="<?php echo htmlentities($currCgpa); ?>" required />
                    </div>

                    <div class="form-group">
                        <label>Current Photo</label><br>
                        <?php if ($currPhoto): ?>
                            <img src="studentphoto/<?php echo htmlentities($currPhoto); ?>" alt="Profile Photo" width="150" class="img-thumbnail">
                        <?php else: ?>
                            <img src="studentphoto/noimage.png" alt="No Photo" width="150" class="img-thumbnail">
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label>Upload New Photo</label>
                        <input type="file" name="photo" class="form-control" accept="image/*" />
                    </div>

                    <button type="submit" name="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<script src="assets/js/jquery-1.11.1.js"></script>
<script src="assets/js/bootstrap.js"></script>
</body>
</html>
