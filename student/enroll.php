<?php
session_start();
include('includes/config.php');
error_reporting(0);

// Redirect if not logged in or pincode not set
if (strlen($_SESSION['login']) == 0 || strlen($_SESSION['pcode']) == 0) {
    header('location:student/index.php');
    exit;
}

// Handle enrollment form submission
if (isset($_POST['submit'])) {
    $studentregno = $_POST['studentregno'];
    $pincode = $_POST['Pincode'];
    $session = $_POST['session'];
    $dept = $_POST['department'];
    $level = $_POST['level'];
    $course = $_POST['course'];
    $sem = $_POST['sem'];

    $query = "INSERT INTO courseenrolls(studentRegno, pincode, session, department, level, course, semester)
              VALUES ('$studentregno', '$pincode', '$session', '$dept', '$level', '$course', '$sem')";

    $ret = mysqli_query($con, $query);

    if ($ret) {
        echo '<script>alert("Enrolled Successfully!"); window.location.href="enroll.php";</script>';
    } else {
        echo '<script>alert("Error: Enrollment Failed!"); window.location.href="enroll.php";</script>';
    }
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Course Enroll</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
</head>
<body>

<?php include('includes/header.php'); ?>

<div class="content-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-head-line">Course Enroll</h1>
            </div>
        </div>

        <div class="row">
            <!-- Sidebar (Add your menu items in includes/sidebar.php) -->
            <div class="col-md-3">
                <?php include('includes/sidebar.php'); ?>
            </div>

            <!-- Main content -->
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">Course Enroll</div>
                    <div class="panel-body">

<?php
$sql = mysqli_query($con, "SELECT * FROM students WHERE StudentRegno='" . $_SESSION['login'] . "'");
while ($row = mysqli_fetch_array($sql)) {
?>

<form method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="studentname">Student Name</label>
        <input type="text" class="form-control" id="studentname" name="studentname"
               value="<?php echo htmlentities($row['studentName']); ?>" readonly />
    </div>

    <div class="form-group">
        <label for="studentregno">Student Reg No</label>
        <input type="text" class="form-control" id="studentregno" name="studentregno"
               value="<?php echo htmlentities($row['StudentRegno']); ?>" readonly />
    </div>

    <div class="form-group">
        <label for="Pincode">Pincode</label>
        <input type="text" class="form-control" id="Pincode" name="Pincode"
               value="<?php echo htmlentities($row['pincode']); ?>" readonly required />
    </div>

    <div class="form-group">
        <label for="studentPhoto">Student Photo</label><br/>
        <?php if ($row['studentPhoto'] == "") { ?>
            <img src="studentphoto/noimage.png" width="200" height="200">
        <?php } else { ?>
            <img src="studentphoto/<?php echo htmlentities($row['studentPhoto']); ?>" width="200" height="200">
        <?php } ?>
    </div>

<?php } // End while ?>

    <div class="form-group">
        <label for="Session">Session</label>
        <select class="form-control" name="session" required>
            <option value="">Select Session</option>
            <?php
            $sql = mysqli_query($con, "SELECT * FROM session");
            while ($row = mysqli_fetch_array($sql)) {
                echo '<option value="' . htmlentities($row['id']) . '">' . htmlentities($row['session']) . '</option>';
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="Department">Department</label>
        <select class="form-control" name="department" required>
            <option value="">Select Department</option>
            <?php
            $sql = mysqli_query($con, "SELECT * FROM department");
            while ($row = mysqli_fetch_array($sql)) {
                echo '<option value="' . htmlentities($row['id']) . '">' . htmlentities($row['department']) . '</option>';
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="Level">Level</label>
        <select class="form-control" name="level" required>
            <option value="">Select Level</option>
            <?php
            $sql = mysqli_query($con, "SELECT * FROM level");
            while ($row = mysqli_fetch_array($sql)) {
                echo '<option value="' . htmlentities($row['id']) . '">' . htmlentities($row['level']) . '</option>';
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="Semester">Semester</label>
        <select class="form-control" name="sem" required>
            <option value="">Select Semester</option>
            <?php
            $sql = mysqli_query($con, "SELECT * FROM semester");
            while ($row = mysqli_fetch_array($sql)) {
                echo '<option value="' . htmlentities($row['id']) . '">' . htmlentities($row['semester']) . '</option>';
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="Course">Course</label>
        <select class="form-control" name="course" id="course" onBlur="courseAvailability()" required>
            <option value="">Select Course</option>
            <?php
            $sql = mysqli_query($con, "SELECT * FROM course");
            while ($row = mysqli_fetch_array($sql)) {
                echo '<option value="' . htmlentities($row['id']) . '">' . htmlentities($row['courseName']) . '</option>';
            }
            ?>
        </select>
        <span id="course-availability-status1" style="font-size:12px;"></span>
    </div>

    <button type="submit" name="submit" id="submit" class="btn btn-primary">Enroll</button>
</form>

                    </div>
                </div>
            </div>

            <!-- Right spacing -->
            <div class="col-md-3"></div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<!-- JavaScript -->
<script src="assets/js/jquery-1.11.1.js"></script>
<script src="assets/js/bootstrap.js"></script>
<script>
function courseAvailability() {
    $("#loaderIcon").show();
    $.ajax({
        url: "check_availability.php",
        data: 'cid=' + $("#course").val(),
        type: "POST",
        success: function(data) {
            $("#course-availability-status1").html(data);
            $("#loaderIcon").hide();
        },
        error: function () {}
    });
}
</script>
</body>
</html>
