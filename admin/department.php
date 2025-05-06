<?php
session_start();
include('includes/config.php');

// Check if admin is logged in
if (!isset($_SESSION['alogin']) || strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
}

// Handle new department insertion
if (isset($_POST['submit'])) {
    $department = mysqli_real_escape_string($con, $_POST['department']);
    $ret = mysqli_query($con, "INSERT INTO department(department) VALUES('$department')");

    if ($ret) {
        $_SESSION['msg'] = "Department Created Successfully !!";
    } else {
        $_SESSION['msg'] = "Error: Department not created";
    }
    header('Location: department.php');
    exit();
}

// Handle deletion
if (isset($_GET['del']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    mysqli_query($con, "DELETE FROM department WHERE id = '$id'");
    $_SESSION['delmsg'] = "Department deleted !!";
    header('Location: department.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin | Department</title>
    <meta charset="utf-8" />
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <link href="../assets/css/style.css" rel="stylesheet" />
</head>

<body>
<?php include('includes/header.php'); ?>
<?php if ($_SESSION['alogin'] != "") include('includes/sidebar.php'); ?>

<div class="content-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-head-line">Department</h1>
            </div>
        </div>

        <!-- Success or Error Messages -->
        <?php if (!empty($_SESSION['msg'])): ?>
            <div class="alert alert-info text-center"><?php echo htmlentities($_SESSION['msg']); $_SESSION['msg'] = ""; ?></div>
        <?php endif; ?>
        <?php if (!empty($_SESSION['delmsg'])): ?>
            <div class="alert alert-danger text-center"><?php echo htmlentities($_SESSION['delmsg']); $_SESSION['delmsg'] = ""; ?></div>
        <?php endif; ?>

        <!-- Add Department Form -->
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">Add Department</div>
                    <div class="panel-body">
                        <form method="post">
                            <div class="form-group">
                                <label for="department">Department Name</label>
                                <input type="text" class="form-control" id="department" name="department" placeholder="Enter Department Name" required />
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Department Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Manage Departments</div>
                    <div class="panel-body">
                        <div class="table-responsive table-bordered">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Department</th>
                                        <th>Creation Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $sql = mysqli_query($con, "SELECT * FROM department");
                                $cnt = 1;
                                while ($row = mysqli_fetch_array($sql)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $cnt++; ?></td>
                                        <td><?php echo htmlentities($row['department']); ?></td>
                                        <td><?php echo htmlentities($row['creationDate']); ?></td>
                                        <td>
                                            <a href="department.php?id=<?php echo $row['id']; ?>&del=delete" onclick="return confirm('Are you sure you want to delete this department?');">
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
