<?php
session_start();
include('includes/config.php');

// Check if admin is logged in
if (!isset($_SESSION['alogin']) || strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
}

// Handle form submission
if (isset($_POST['submit'])) {
    $semester = $_POST['semester'];
    $ret = mysqli_query($con, "INSERT INTO semester (semester) VALUES ('$semester')");
    if ($ret) {
        $_SESSION['msg'] = "Semester Created Successfully !!";
    } else {
        $_SESSION['msg'] = "Something went wrong. Please try again.";
    }
    header("Location: semester.php");
    exit();
}

// Handle delete request
if (isset($_GET['del']) && isset($_GET['id'])) {
    $sid = intval($_GET['id']);
    mysqli_query($con, "DELETE FROM semester WHERE id = '$sid'");
    $_SESSION['delmsg'] = "Semester Deleted Successfully !!";
    header("Location: semester.php");
    exit();
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin | Semester</title>
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <link href="../assets/css/style.css" rel="stylesheet" />
</head>

<body>
<?php include('includes/header.php'); ?>
<?php include('includes/menubar.php'); ?>

<div class="content-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-head-line">Semester</h1>
            </div>
        </div>

        <!-- Add Semester Form -->
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">Add Semester</div>
                    <div class="panel-body">
                        <?php if (!empty($_SESSION['msg'])): ?>
                            <div class="alert alert-info"><?php echo htmlentities($_SESSION['msg']); $_SESSION['msg'] = ""; ?></div>
                        <?php endif; ?>
                        <form method="post">
                            <div class="form-group">
                                <label for="semester">Semester</label>
                                <input type="text" class="form-control" id="semester" name="semester" required />
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Display Semester Table -->
        <div class="row">
            <div class="col-md-12">
                <?php if (!empty($_SESSION['delmsg'])): ?>
                    <div class="alert alert-danger"><?php echo htmlentities($_SESSION['delmsg']); $_SESSION['delmsg'] = ""; ?></div>
                <?php endif; ?>
                <div class="panel panel-default">
                    <div class="panel-heading">Manage Semesters</div>
                    <div class="panel-body">
                        <div class="table-responsive table-bordered">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Semester</th>
                                        <th>Creation Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = mysqli_query($con, "SELECT * FROM semester");
                                    $cnt = 1;
                                    while ($row = mysqli_fetch_array($sql)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $cnt++; ?></td>
                                        <td><?php echo htmlentities($row['semester']); ?></td>
                                        <td><?php echo htmlentities($row['creationDate']); ?></td>
                                        <td>
                                            <a href="semester.php?id=<?php echo $row['id']; ?>&del=delete"
                                               onclick="return confirm('Are you sure you want to delete?');">
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

<?php include('includes/footer.php'); ?>

<!-- Scripts -->
<script src="../assets/js/jquery-1.11.1.js"></script>
<script src="../assets/js/bootstrap.js"></script>

</body>
</html>
