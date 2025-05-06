<?php
session_start();
include('includes/config.php');

// Check if admin is logged in
if (!isset($_SESSION['alogin']) || strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
}

// Handle course creation
if (isset($_POST['submit'])) {
    $coursecode = mysqli_real_escape_string($con, $_POST['coursecode']);
    $coursename = mysqli_real_escape_string($con, $_POST['coursename']);
    $courseunit = mysqli_real_escape_string($con, $_POST['courseunit']);
    $seatlimit = mysqli_real_escape_string($con, $_POST['seatlimit']);

    $ret = mysqli_query($con, "INSERT INTO course (courseCode, courseName, courseUnit, noofSeats) VALUES ('$coursecode', '$coursename', '$courseunit', '$seatlimit')");

    if ($ret) {
        $_SESSION['msg'] = "Course Created Successfully !!";
    } else {
        $_SESSION['msg'] = "Error: Course not created!";
    }
    header('Location: course.php');
    exit();
}

// Handle deletion
if (isset($_GET['del']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    mysqli_query($con, "DELETE FROM course WHERE id = '$id'");
    $_SESSION['delmsg'] = "Course deleted successfully !!";
    header('Location: course.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Course</title>
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <link href="../assets/css/style.css" rel="stylesheet" />
</head>

<body>
    <?php include('includes/header.php'); ?>

    <!-- Sidebar -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                
            </div>

            <div class="col-md-9">
                <div class="content-wrapper">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <h1 class="page-head-line">Course</h1>
                            </div>
                        </div>

                        <!-- Display message -->
                        <?php if (!empty($_SESSION['msg'])): ?>
                            <div class="alert alert-info text-center"><?php echo htmlentities($_SESSION['msg']); $_SESSION['msg'] = ""; ?></div>
                        <?php endif; ?>
                        <?php if (!empty($_SESSION['delmsg'])): ?>
                            <div class="alert alert-danger text-center"><?php echo htmlentities($_SESSION['delmsg']); $_SESSION['delmsg'] = ""; ?></div>
                        <?php endif; ?>

                        <!-- Add Course Form -->
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">Add Course</div>
                                    <div class="panel-body">
                                        <form method="post">
                                            <div class="form-group">
                                                <label for="coursecode">Course Code</label>
                                                <input type="text" class="form-control" id="coursecode" name="coursecode" placeholder="Course Code" required />
                                            </div>
                                            <div class="form-group">
                                                <label for="coursename">Course Name</label>
                                                <input type="text" class="form-control" id="coursename" name="coursename" placeholder="Course Name" required />
                                            </div>
                                            <div class="form-group">
                                                <label for="courseunit">Course Unit</label>
                                                <input type="text" class="form-control" id="courseunit" name="courseunit" placeholder="Course Unit" required />
                                            </div>
                                            <div class="form-group">
                                                <label for="seatlimit">Seat Limit</label>
                                                <input type="text" class="form-control" id="seatlimit" name="seatlimit" placeholder="Seat Limit" required />
                                            </div>
                                            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Course Table -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">Manage Courses</div>
                                    <div class="panel-body">
                                        <div class="table-responsive table-bordered">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Course Code</th>
                                                        <th>Course Name</th>
                                                        <th>Course Unit</th>
                                                        <th>Seat Limit</th>
                                                        <th>Creation Date</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $sql = mysqli_query($con, "SELECT * FROM course");
                                                    $cnt = 1;
                                                    while ($row = mysqli_fetch_array($sql)) {
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $cnt++; ?></td>
                                                            <td><?php echo htmlentities($row['courseCode']); ?></td>
                                                            <td><?php echo htmlentities($row['courseName']); ?></td>
                                                            <td><?php echo htmlentities($row['courseUnit']); ?></td>
                                                            <td><?php echo htmlentities($row['noofSeats']); ?></td>
                                                            <td><?php echo htmlentities($row['creationDate']); ?></td>
                                                            <td>
                                                                <a href="edit-course.php?id=<?php echo $row['id']; ?>">
                                                                    <button class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</button>
                                                                </a>
                                                                <a href="course.php?id=<?php echo $row['id']; ?>&del=delete" onclick="return confirm('Are you sure you want to delete?');">
                                                                    <button class="btn btn-danger btn-sm">Delete</button>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('includes/footer.php'); ?>

    <!-- Scripts -->
    <script src="../assets/js/jquery-1.11.1.js"></script>
    <script src="../assets/js/bootstrap.js"></script>
</body>
</html>
