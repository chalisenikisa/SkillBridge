<?php
session_start();
include('includes/config.php');

if (!isset($_SESSION['alogin']) || strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
}

$id = intval($_GET['id']);
date_default_timezone_set('Asia/Kathmandu');
$currentTime = date('d-m-Y h:i:s A', time());

if (isset($_POST['submit'])) {
    $coursecode = mysqli_real_escape_string($con, $_POST['coursecode']);
    $coursename = mysqli_real_escape_string($con, $_POST['coursename']);
    $courseunit = mysqli_real_escape_string($con, $_POST['courseunit']);
    $seatlimit = mysqli_real_escape_string($con, $_POST['seatlimit']);

    $ret = mysqli_query($con, "UPDATE course SET courseCode='$coursecode', courseName='$coursename', courseUnit='$courseunit', noofSeats='$seatlimit', updationDate='$currentTime' WHERE id='$id'");

    if ($ret) {
        echo '<script>alert("Course Updated Successfully !!")</script>';
    } else {
        echo '<script>alert("Error: Course not Updated !!")</script>';
    }
    echo '<script>window.location.href="course.php"</script>';
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Admin | Edit Course</title>
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
                <h1 class="page-head-line">Edit Course</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">Course Details</div>
                    <div class="panel-body">

                        <form method="post">
                            <?php
                            $sql = mysqli_query($con, "SELECT * FROM course WHERE id='$id'");
                            if ($row = mysqli_fetch_array($sql)) {
                            ?>
                                <p><b>Last Updated at:</b> <?php echo htmlentities($row['updationDate']); ?></p>

                                <div class="form-group">
                                    <label for="coursecode">Course Code</label>
                                    <input type="text" class="form-control" id="coursecode" name="coursecode" value="<?php echo htmlentities($row['courseCode']); ?>" required />
                                </div>

                                <div class="form-group">
                                    <label for="coursename">Course Name</label>
                                    <input type="text" class="form-control" id="coursename" name="coursename" value="<?php echo htmlentities($row['courseName']); ?>" required />
                                </div>

                                <div class="form-group">
                                    <label for="courseunit">Course Unit</label>
                                    <input type="text" class="form-control" id="courseunit" name="courseunit" value="<?php echo htmlentities($row['courseUnit']); ?>" required />
                                </div>

                                <div class="form-group">
                                    <label for="seatlimit">Seat Limit</label>
                                    <input type="number" class="form-control" id="seatlimit" name="seatlimit" value="<?php echo htmlentities($row['noofSeats']); ?>" required />
                                </div>

                                <button type="submit" name="submit" class="btn btn-primary"><i class="fa fa-refresh"></i> Update</button>
                            <?php
                            } else {
                                echo "<p class='text-danger'>No course found with the given ID.</p>";
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
