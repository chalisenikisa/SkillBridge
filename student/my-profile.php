<?php
session_start();
include('includes/config.php');
error_reporting(0);

// Redirect if user is not logged in
if (!isset($_SESSION['login']) || strlen($_SESSION['login']) == 0) {
    header('location:student/index.php');
    exit();
}

if (isset($_POST['submit'])) {
    $studentname = $_POST['studentname'];
    $cgpa = $_POST['cgpa'];
    $regno = $_SESSION['login'];

    $photo = $_FILES['photo']['name'];
    $photoPath = '';

    if ($photo) {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $fileExt = strtolower(pathinfo($photo, PATHINFO_EXTENSION));
        $mimeType = mime_content_type($_FILES['photo']['tmp_name']);

        if (in_array($fileExt, $allowedExtensions) && strpos($mimeType, 'image/') === 0) {
            $photoPath = "studentphoto/" . basename($photo);
            move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath);
        } else {
            echo "<script>alert('Invalid photo format. Only image files are allowed.');</script>";
            echo "<script>window.location.href='my-profile.php'</script>";
            exit();
        }
    }

    // Build update query based on whether photo was uploaded
    if ($photoPath) {
        $stmt = $con->prepare("UPDATE students SET studentName=?, studentPhoto=?, cgpa=? WHERE StudentRegno=?");
        $stmt->bind_param("ssds", $studentname, $photo, $cgpa, $regno);
    } else {
        $stmt = $con->prepare("UPDATE students SET studentName=?, cgpa=? WHERE StudentRegno=?");
        $stmt->bind_param("sds", $studentname, $cgpa, $regno);
    }

    if ($stmt->execute()) {
        echo '<script>alert("Student Record updated Successfully !!");</script>';
    } else {
        echo '<script>alert("Something went wrong. Please try again.");</script>';
    }

    echo '<script>window.location.href="my-profile.php";</script>';
    exit();
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Student Profile</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>
<?php include('includes/header.php'); ?>
<?php if ($_SESSION['login'] != "") {
    
} ?>

<div class="content-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-head-line">Student Profile</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">Edit Profile</div>

                    <div class="panel-body">
                        <?php
                        $sql = $con->prepare("SELECT * FROM students WHERE StudentRegno=?");
                        $sql->bind_param("s", $_SESSION['login']);
                        $sql->execute();
                        $result = $sql->get_result();
                        if ($row = $result->fetch_assoc()) {
                        ?>
                        <form name="profile" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="studentname">Student Name</label>
                                <input type="text" class="form-control" name="studentname" value="<?= htmlentities($row['studentName']) ?>" required />
                            </div>

                            <div class="form-group">
                                <label>Student Reg No</label>
                                <input type="text" class="form-control" value="<?= htmlentities($row['StudentRegno']) ?>" readonly />
                            </div>

                            <div class="form-group">
                                <label>Pincode</label>
                                <input type="text" class="form-control" value="<?= htmlentities($row['pincode']) ?>" readonly />
                            </div>

                            <div class="form-group">
                                <label>CGPA</label>
                                <input type="text" class="form-control" name="cgpa" value="<?= htmlentities($row['cgpa']) ?>" required />
                            </div>

                            <div class="form-group">
                                <label>Current Photo</label><br />
                                <?php if ($row['studentPhoto'] == "") { ?>
                                    <img src="studentphoto/noimage.png" width="200" height="200" />
                                <?php } else { ?>
                                    <img src="studentphoto/<?= htmlentities($row['studentPhoto']) ?>" width="200" height="200" />
                                <?php } ?>
                            </div>

                            <div class="form-group">
                                <label>Upload New Photo</label>
                                <input type="file" class="form-control" name="photo" />
                            </div>

                            <button type="submit" name="submit" class="btn btn-primary">Update</button>
                        </form>
                        <?php } else { echo "<div class='alert alert-danger'>Student record not found.</div>"; } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
<script src="assets/js/jquery-1.11.1.js"></script>
<script src="assets/js/bootstrap.js"></script>
</body>
</html>
