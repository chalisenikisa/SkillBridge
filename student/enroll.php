<?php
session_start();
include('includes/config.php');
error_reporting(0);

// Temporary debug to see session values — remove in production
// echo "Login: " . $_SESSION['login'] . "<br>";
// echo "Pincode: " . $_SESSION['pcode'];
// exit;

// Better session check using isset()
if (!isset($_SESSION['login']) || !isset($_SESSION['pcode']) || empty($_SESSION['login']) || empty($_SESSION['pcode'])) {
    header('location:index.php');
    exit();
}

if (isset($_POST['submit'])) {
    $studentregno = $_POST['studentregno'];
    $pincode = $_POST['Pincode'];
    $session = $_POST['session'];
    $dept = $_POST['department'];
    $level = $_POST['level'];
    $course = $_POST['course'];
    $sem = $_POST['sem'];

    $stmt = $con->prepare("INSERT INTO courseenrolls(studentRegno, pincode, session, department, level, course, semester) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $studentregno, $pincode, $session, $dept, $level, $course, $sem);
    
    if ($stmt->execute()) {
        echo '<script>alert("Enrolled Successfully!"); window.location.href="enroll.php";</script>';
    } else {
        echo '<script>alert("Error: Not Enrolled."); window.location.href="enroll.php";</script>';
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Course Enroll</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <link href="../assets/css/style.css" rel="stylesheet" />
</head>

<body>
    <?php include('includes/header.php'); ?>
    <?php if (!empty($_SESSION['login'])) include('includes/menubar.php'); ?>

    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="page-head-line">Course Enroll</h1>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">Enroll Form</div>
                        <div class="panel-body">
                            <?php
                            $sql = mysqli_query($con, "SELECT * FROM students WHERE StudentRegno='" . $_SESSION['login'] . "'");
                            if (mysqli_num_rows($sql) > 0) {
                                $row = mysqli_fetch_array($sql);
                            ?>
                                <form name="enroll" method="post">
                                    <div class="form-group">
                                        <label>Student Name</label>
                                        <input type="text" class="form-control" value="<?php echo htmlentities($row['studentName']); ?>" readonly />
                                    </div>
                                    <div class="form-group">
                                        <label>Student Reg No</label>
                                        <input type="text" class="form-control" name="studentregno" value="<?php echo htmlentities($row['StudentRegno']); ?>" readonly />
                                    </div>
                                    <div class="form-group">
                                        <label>Pincode</label>
                                        <input type="text" class="form-control" name="Pincode" value="<?php echo htmlentities($row['pincode']); ?>" readonly />
                                    </div>
                                    <div class="form-group">
                                        <label>Student Photo</label><br>
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
                                            $session_query = mysqli_query($con, "SELECT * FROM session");
                                            while ($s_row = mysqli_fetch_array($session_query)) {
                                            ?>
                                                <option value="<?php echo htmlentities($s_row['id']); ?>"><?php echo htmlentities($s_row['session']); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Department</label>
                                        <select class="form-control" name="department" required>
                                            <option value="">Select Department</option>
                                            <?php
                                            $dept_query = mysqli_query($con, "SELECT * FROM department");
                                            while ($d_row = mysqli_fetch_array($dept_query)) {
                                            ?>
                                                <option value="<?php echo htmlentities($d_row['id']); ?>"><?php echo htmlentities($d_row['department']); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Level</label>
                                        <select class="form-control" name="level" required>
                                            <option value="">Select Level</option>
                                            <?php
                                            $level_query = mysqli_query($con, "SELECT * FROM level");
                                            while ($l_row = mysqli_fetch_array($level_query)) {
                                            ?>
                                                <option value="<?php echo htmlentities($l_row['id']); ?>"><?php echo htmlentities($l_row['level']); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Semester</label>
                                        <select class="form-control" name="sem" required>
                                            <option value="">Select Semester</option>
                                            <?php
                                            $sem_query = mysqli_query($con, "SELECT * FROM semester");
                                            while ($sem_row = mysqli_fetch_array($sem_query)) {
                                            ?>
                                                <option value="<?php echo htmlentities($sem_row['id']); ?>"><?php echo htmlentities($sem_row['semester']); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Course</label>
                                        <select class="form-control" name="course" id="course" onblur="courseAvailability()" required>
                                            <option value="">Select Course</option>
                                            <?php
                                            $course_query = mysqli_query($con, "SELECT * FROM course");
                                            while ($c_row = mysqli_fetch_array($course_query)) {
                                            ?>
                                                <option value="<?php echo htmlentities($c_row['id']); ?>"><?php echo htmlentities($c_row['courseName']); ?></option>
                                            <?php } ?>
                                        </select>
                                        <span id="course-availability-status1" style="font-size:12px;"></span>
                                    </div>

                                    <button type="submit" name="submit" class="btn btn-primary">Enroll</button>
                                </form>
                            <?php } else {
                                echo "<div class='alert alert-danger'>Student record not found.</div>";
                            } ?>
                        </div>
                    </div>
                </div>
            </div> <!-- row -->
        </div> <!-- container -->
    </div> <!-- content-wrapper -->

    <?php include('includes/footer.php'); ?>

    <script src="../assets/js/jquery-1.11.1.js"></script>
    <script src="../assets/js/bootstrap.js"></script>
    <script>
        function courseAvailability() {
            jQuery.ajax({
                url: "check_availability.php",
                data: 'cid=' + $("#course").val(),
                type: "POST",
                success: function (data) {
                    $("#course-availability-status1").html(data);
                },
                error: function () { }
            });
        }
    </script>
</body>
</html>
