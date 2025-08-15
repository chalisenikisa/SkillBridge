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
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: linear-gradient(to bottom, #2a2b75, #226a8b);
            padding: 20px 0;
            border-radius: 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .sidebar a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            margin-bottom: 5px;
        }

        .sidebar a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 3px solid #33aa79;
        }

        .content {
            flex: 1;
            padding: 30px;
            background-color: #f5f7fa;
        }

        .page-head-line {
            color: #2a2b75;
            font-weight: 600;
            padding-bottom: 15px;
            border-bottom: 2px solid #33aa79;
            margin-bottom: 30px;
            font-size: 24px;
        }

        .enroll-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }

        .enroll-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(to right, #2a2b75, #33aa79);
        }

        .enroll-card-header {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .enroll-card-header h3 {
            margin: 0;
            color: #2a2b75;
            font-weight: 600;
            font-size: 18px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            font-weight: 500;
            color: #555;
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            height: 45px;
            border-radius: 4px;
            border: 1px solid #ddd;
            box-shadow: none;
            transition: all 0.3s ease;
            padding: 10px 15px;
        }

        .form-control:focus {
            border-color: #33aa79;
            box-shadow: 0 0 8px rgba(51, 170, 121, 0.2);
        }

        .form-control[readonly] {
            background-color: #f8f9fa;
            cursor: not-allowed;
        }

        .student-photo {
            width: 150px;
            height: 150px;
            border-radius: 8px;
            overflow: hidden;
            margin: 0 auto 20px;
            border: 3px solid #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .student-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .btn-enroll {
            background: linear-gradient(to right, #2a2b75, #33aa79);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 4px;
            font-weight: 500;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-enroll:hover {
            background: linear-gradient(to right, #33aa79, #2a2b75);
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
        }

        .alert {
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 20px;
            border: none;
        }

        .alert-danger {
            background-color: #ffe5e5;
            color: #d63031;
        }

        .course-availability {
            margin-top: 5px;
            font-size: 13px;
            font-weight: 500;
        }
    </style>
</head>

<body>
    <?php include('includes/header.php'); ?>
    <?php if (!empty($_SESSION['login']))  ?>

    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <a href="enroll.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'enroll.php' ? 'active' : ''; ?>">
                <i class="fas fa-book-open"></i> Enroll for Course
            </a>
            <a href="enroll-history.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'enroll-history.php' ? 'active' : ''; ?>">
                <i class="fas fa-history"></i> Enroll History
            </a>
            <a href="my-profile.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'my-profile.php' ? 'active' : ''; ?>">
                <i class="fas fa-user"></i> My Profile
            </a>
            <a href="change-password.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'change-password.php' ? 'active' : ''; ?>">
                <i class="fas fa-key"></i> Change Password
            </a>
            <a href="logout.php">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>

        <!-- Main Content -->
        <div class="content">
            <h2 class="page-head-line"><i class="fas fa-book-open me-2"></i> Course Enrollment</h2>

            <div class="enroll-card">
                <div class="enroll-card-header">
                    <h3><i class="fas fa-edit me-2"></i> Enroll Form</h3>
                </div>
                <?php
                $sql = mysqli_query($con, "SELECT * FROM students WHERE StudentRegno='" . $_SESSION['login'] . "'");
                if ($row = mysqli_fetch_array($sql)) {
                ?>
                    <form method="post">
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="student-photo">
                                    <?php if ($row['studentPhoto'] == "") { ?>
                                        <img src="studentphoto/noimage.png" alt="Student Photo">
                                    <?php } else { ?>
                                        <img src="studentphoto/<?php echo htmlentities($row['studentPhoto']); ?>" alt="Student Photo">
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><i class="fas fa-user me-2"></i>Student Name</label>
                                            <input class="form-control" type="text" value="<?php echo htmlentities($row['studentName']); ?>" readonly />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><i class="fas fa-id-card me-2"></i>Student Reg No</label>
                                            <input class="form-control" type="text" name="studentregno" value="<?php echo htmlentities($row['StudentRegno']); ?>" readonly />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label><i class="fas fa-map-marker-alt me-2"></i>Pincode</label>
                                    <input class="form-control" type="text" name="Pincode" value="<?php echo htmlentities($row['pincode']); ?>" readonly />
                                </div>
                            </div>
                        </div>

                        <h4 class="mb-3" style="color: #2a2b75; font-weight: 500; padding-bottom: 10px; border-bottom: 1px solid #eee;">Course Selection</h4>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><i class="fas fa-calendar-alt me-2"></i>Session</label>
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
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><i class="fas fa-building me-2"></i>Department</label>
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
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><i class="fas fa-layer-group me-2"></i>Level</label>
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
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><i class="fas fa-clock me-2"></i>Semester</label>
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
                            </div>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-book me-2"></i>Course</label>
                            <select class="form-control" name="course" id="course" required onblur="courseAvailability()">
                                <option value="">Select Course</option>
                                <?php
                                $res = mysqli_query($con, "SELECT * FROM course");
                                while ($c = mysqli_fetch_array($res)) {
                                    echo "<option value='" . $c['id'] . "'>" . $c['courseName'] . "</option>";
                                }
                                ?>
                            </select>
                            <div id="course-availability-status1" class="course-availability"></div>
                        </div>

                        <button type="submit" name="submit" class="btn btn-enroll">
                            <i class="fas fa-check-circle me-2"></i> Enroll Now
                        </button>
                    </form>
                <?php } else {
                    echo "<div class='alert alert-danger'><i class='fas fa-exclamation-circle me-2'></i> Student not found</div>";
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

        // Add active class to current menu item
        document.addEventListener('DOMContentLoaded', function() {
            const currentPage = window.location.pathname.split('/').pop();
            const menuItems = document.querySelectorAll('.sidebar a');

            menuItems.forEach(item => {
                const href = item.getAttribute('href');
                if (href === currentPage) {
                    item.classList.add('active');
                }
            });
        });
    </script>
</body>

</html>