<?php
session_start();
include('includes/config.php');
error_reporting(0);

if (strlen($_SESSION['login']) == 0 || strlen($_SESSION['pcode']) == 0) {
    header('location:index.php');
    exit;
}

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
<html>
<head>
    <meta charset="utf-8" />
    <title>Course Enroll</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
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
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .alert-success {
            background-color: #dff0d8;
            color: #3c763d;
        }
        .alert-danger {
            background-color: #f2dede;
            color: #a94442;
        }
    </style>
</head>
<body>

<?php include('includes/header.php'); ?>

<div class="wrapper">
    <!-- Sidebar -->
    <div class="sidebar">
        <?php include('includes/sidebar.php'); ?>
    </div>

    <!-- Main Content -->
    <div class="content">
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
                            while ($s = mysqli_fetch_array($result)) {
                                echo '<option value="' . htmlentities($s['id']) . '">' . htmlentities($s['session']) . '</option>';
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
                            while ($d = mysqli_fetch_array($result)) {
                                echo '<option value="' . htmlentities($d['id']) . '">' . htmlentities($d['department']) . '</option>';
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
                            while ($l = mysqli_fetch_array($result)) {
                                echo '<option value="' . htmlentities($l['id']) . '">' . htmlentities($l['level']) . '</option>';
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
                            while ($sm = mysqli_fetch_array($result)) {
                                echo '<option value="' . htmlentities($sm['id']) . '">' . htmlentities($sm['semester']) . '</option>';
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
                            while ($c = mysqli_fetch_array($result)) {
                                echo '<option value="' . htmlentities($c['id']) . '">' . htmlentities($c['courseName']) . '</option>';
                            }
                            ?>
                        </select>
                        <span id="course-availability-status1" style="font-size:12px;"></span>
                    </div>

                    <button type="submit" name="submit" class="btn btn-primary">Enroll</button>
                </form>

            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<!-- Scripts -->
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
