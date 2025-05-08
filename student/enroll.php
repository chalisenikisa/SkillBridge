<?php
session_start();
include('includes/config.php');

if (!isset($_SESSION['login']) || strlen($_SESSION['login']) == 0) {
    header('location:index.php');
    exit();
}

if (isset($_POST['submit'])) {
    $studentregno = $_POST['studentregno'];
    $pincode = $_POST['Pincode'];
    $sessionid = $_POST['session'];
    $deptid = $_POST['department'];
    $levelid = $_POST['level'];
    $courseid = $_POST['course'];
    $semid = $_POST['sem'];

    $stmt = $con->prepare("INSERT INTO courseenrolls (studentRegno, pincode, session, department, level, course, semester) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $studentregno, $pincode, $sessionid, $deptid, $levelid, $courseid, $semid);

    if ($stmt->execute()) {
        echo "<script>alert('Enrolled successfully');</script>";
    } else {
        echo "<script>alert('Error occurred');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Course Enrollment</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
</head>
<body>
<?php include('includes/header.php'); ?>
<?php if (!empty($_SESSION['login'])) include('includes/sidebar.php'); ?>

<div class="content-wrapper">
    <div class="container">
        <h1 class="page-head-line">Course Enroll</h1>

        <div class="panel panel-default">
            <div class="panel-heading">Enroll Form</div>
            <div class="panel-body">
                <?php
                $sql = mysqli_query($con, "SELECT * FROM students WHERE StudentRegno='" . $_SESSION['login'] . "'");
                if ($row = mysqli_fetch_array($sql)) {
                ?>
                <form method="post">
                    <div class="form-group">
                        <label>Student Name</label>
                        <input class="form-control" type="text" value="<?php echo htmlentities($row['studentName']); ?>" readonly />
                    </div>
                    <div class="form-group">
                        <label>Student Reg No</label>
                        <input class="form-control" type="text" name="studentregno" value="<?php echo htmlentities($row['StudentRegno']); ?>" readonly />
                    </div>
                    <div class="form-group">
                        <label>Pincode</label>
                        <input class="form-control" type="text" name="Pincode" value="<?php echo htmlentities($row['pincode']); ?>" readonly />
                    </div>
                    <div class="form-group">
                        <label>Photo</label><br />
                        <?php if ($row['studentPhoto'] == "") { ?>
                            <img src="studentphoto/noimage.png" width="200" height="200">
                        <?php } else { ?>
                            <img src="studentphoto/<?php echo htmlentities($row['studentPhoto']); ?>" width="200" height="200">
                        <?php } ?>
                    </div>

                    <div class="form-group">
                        <label>Session</label>
                        <select class="form-control" name="session" required>
                            <option value="">Select Session</option>
                            <?php
                            $res = mysqli_query($con, "SELECT * FROM session");
                            while ($sess = mysqli_fetch_array($res)) {
                                echo "<option value='" . $sess['id'] . "'>" . $sess['session'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Department</label>
                        <select class="form-control" name="department" required>
                            <option value="">Select Department</option>
                            <?php
                            $res = mysqli_query($con, "SELECT * FROM department");
                            while ($dept = mysqli_fetch_array($res)) {
                                echo "<option value='" . $dept['id'] . "'>" . $dept['department'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Level</label>
                        <select class="form-control" name="level" required>
                            <option value="">Select Level</option>
                            <?php
                            $res = mysqli_query($con, "SELECT * FROM level");
                            while ($lev = mysqli_fetch_array($res)) {
                                echo "<option value='" . $lev['id'] . "'>" . $lev['level'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Semester</label>
                        <select class="form-control" name="sem" required>
                            <option value="">Select Semester</option>
                            <?php
                            $res = mysqli_query($con, "SELECT * FROM semester");
                            while ($sem = mysqli_fetch_array($res)) {
                                echo "<option value='" . $sem['id'] . "'>" . $sem['semester'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Course</label>
                        <select class="form-control" name="course" id="course" required onblur="courseAvailability()">
                            <option value="">Select Course</option>
                            <?php
                            $res = mysqli_query($con, "SELECT * FROM course");
                            while ($c = mysqli_fetch_array($res)) {
                                echo "<option value='" . $c['id'] . "'>" . $c['courseName'] . "</option>";
                            }
                            ?>
                        </select>
                        <span id="course-availability-status1" style="font-size:12px;"></span>
                    </div>

                    <button type="submit" name="submit" class="btn btn-primary">Enroll</button>
                </form>
                <?php } else {
                    echo "<div class='alert alert-danger'>Student not found</div>";
                } ?>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
<script src="assets/js/jquery-1.11.1.js"></script>
<script src="assets/js/bootstrap.js"></script>
<script>
function courseAvailability() {
    jQuery.ajax({
        url: "check_availability.php",
        data: 'cid=' + $("#course").val(),
        type: "POST",
        success: function(data) {
            $("#course-availability-status1").html(data);
        }
    });
}
</script>
</body>
</html>
