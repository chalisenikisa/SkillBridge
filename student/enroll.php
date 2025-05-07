<?php
session_start();
include('includes/config.php');
error_reporting(0);

// Redirect if not logged in or pincode not set
if (strlen($_SESSION['login']) == 0 || strlen($_SESSION['pcode']) == 0) {
    header('location:index.php');
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
        $msg = "Enrolled Successfully!";
    } else {
        $err = "Error: Enrollment Failed!";
    }
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Student | Course Enroll</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
</head>
<body>

<?php include('includes/header.php'); ?>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                <?php include('includes/sidebar.php'); ?>
            </div>

            <!-- Main content -->
            <div class="col-md-9">
                <h2 class="page-head-line">Course Enroll</h2>

                <?php if (isset($msg)) : ?>
                    <div class="alert alert-success"><?php echo htmlentities($msg); ?></div>
                <?php elseif (isset($err)) : ?>
                    <div class="alert alert-danger"><?php echo htmlentities($err); ?></div>
                <?php endif; ?>

                <div class="panel panel-default">
                    <div class="panel-heading">Course Enrollment Form</div>
                    <div class="panel-body">

                        <?php
                        $sql = mysqli_query($con, "SELECT * FROM students WHERE StudentRegno='" . $_SESSION['login'] . "'");
                        $row = mysqli_fetch_array($sql);
                        ?>

                        <form method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Student Name</label>
                                <input type="text" class="form-control" name="studentname"
                                    value="<?php echo htmlentities($row['studentName']); ?>" readonly />
                            </div>

                            <div class="form-group">
                                <label>Student Reg No</label>
                                <input type="text" class="form-control" name="studentregno"
                                    value="<?php echo htmlentities($row['StudentRegno']); ?>" readonly />
                            </div>

                            <div class="form-group">
                                <label>Pincode</label>
                                <input type="text" class="form-control" name="Pincode"
                                    value="<?php echo htmlentities($row['pincode']); ?>" readonly required />
                            </div>

                            <div class="form-group">
                                <label>Student Photo</label><br />
                                <?php if (empty($row['studentPhoto'])) : ?>
                                    <img src="studentphoto/noimage.png" width="150" class="img-thumbnail">
                                <?php else : ?>
                                    <img src="studentphoto/<?php echo htmlentities($row['studentPhoto']); ?>" width="150" class="img-thumbnail">
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label>Session</label>
                                <select class="form-control" name="session" required>
                                    <option value="">Select Session</option>
                                    <?php
                                    $result = mysqli_query($con, "SELECT * FROM session");
                                    while ($session = mysqli_fetch_array($result)) {
                                        echo '<option value="' . htmlentities($session['id']) . '">' . htmlentities($session['session']) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Department</label>
                                <select class="form-control" name="department" required>
                                    <option value="">Select Department</option>
                                    <?php
                                    $result = mysqli_query($con, "SELECT * FROM department");
                                    while ($dept = mysqli_fetch_array($result)) {
                                        echo '<option value="' . htmlentities($dept['id']) . '">' . htmlentities($dept['department']) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Level</label>
                                <select class="form-control" name="level" required>
                                    <option value="">Select Level</option>
                                    <?php
                                    $result = mysqli_query($con, "SELECT * FROM level");
                                    while ($level = mysqli_fetch_array($result)) {
                                        echo '<option value="' . htmlentities($level['id']) . '">' . htmlentities($level['level']) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Semester</label>
                                <select class="form-control" name="sem" required>
                                    <option value="">Select Semester</option>
                                    <?php
                                    $result = mysqli_query($con, "SELECT * FROM semester");
                                    while ($sem = mysqli_fetch_array($result)) {
                                        echo '<option value="' . htmlentities($sem['id']) . '">' . htmlentities($sem['semester']) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Course</label>
                                <select class="form-control" name="course" id="course" onBlur="courseAvailability()" required>
                                    <option value="">Select Course</option>
                                    <?php
                                    $result = mysqli_query($con, "SELECT * FROM course");
                                    while ($course = mysqli_fetch_array($result)) {
                                        echo '<option value="' . htmlentities($course['id']) . '">' . htmlentities($course['courseName']) . '</option>';
                                    }
                                    ?>
                                </select>
                                <span id="course-availability-status1" style="font-size:12px;"></span>
                            </div>

                            <button type="submit" name="submit" class="btn btn-primary">Enroll</button>
                        </form>

                    </div> <!-- panel-body -->
                </div> <!-- panel -->
            </div> <!-- main content col -->
        </div> <!-- row -->
    </div> <!-- container -->
</div> <!-- content-wrapper -->

<?php include('includes/footer.php'); ?>

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
