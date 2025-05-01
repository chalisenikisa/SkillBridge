<?php
session_start();
include('includes/config.php');

if (!isset($_SESSION['alogin']) || strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
}

$regid = intval($_GET['id']);

if (isset($_POST['submit'])) {
    $studentname = mysqli_real_escape_string($con, $_POST['studentname']);
    $cgpa = mysqli_real_escape_string($con, $_POST['cgpa']);

    // File upload
    $photo = $_FILES["photo"]["name"];
    $photo_uploaded = false;

    if (!empty($photo)) {
        $target_dir = "studentphoto/";
        $target_file = $target_dir . basename($photo);

        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            $photo_uploaded = true;
        }
    }

    // SQL Query
    if ($photo_uploaded) {
        $ret = mysqli_query($con, "UPDATE students SET studentName='$studentname', studentPhoto='$photo', cgpa='$cgpa' WHERE StudentRegno='$regid'");
    } else {
        $ret = mysqli_query($con, "UPDATE students SET studentName='$studentname', cgpa='$cgpa' WHERE StudentRegno='$regid'");
    }

    if ($ret) {
        echo '<script>alert("Student Record updated Successfully !!")</script>';
    } else {
        echo '<script>alert("Error: Student Record not updated !!")</script>';
    }

    echo '<script>window.location.href="manage-students.php"</script>';
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Student Profile</title>
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
                                    <label for="studentphoto">Current Photo</label><br />
                                    <?php if ($row['studentPhoto'] == "") { ?>
                                        <img src="../studentphoto/noimage.png" width="200" height="200">
                                    <?php } else { ?>
                                        <img src="../studentphoto/<?php echo htmlentities($row['studentPhoto']); ?>" width="200" height="200">
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
