<?php
session_start();
include('includes/config.php');

if(strlen($_SESSION['alogin']) == 0) {   
    header('location:index.php');
    exit();
}

if(isset($_POST['submit'])) {
    $department = mysqli_real_escape_string($con, $_POST['department']);
    $ret = mysqli_query($con, "INSERT INTO department(department) VALUES('$department')");
    
    if($ret) {
        echo '<script>alert("Department Created Successfully !!")</script>';
        echo '<script>window.location.href="department.php"</script>';
    } else {
        echo '<script>alert("Error: Department not created")</script>';
        echo '<script>window.location.href="department.php"</script>';
    }
}

// Delete department
if(isset($_GET['del']) && isset($_GET['id'])) {
    $deptid = intval($_GET['id']);       
    mysqli_query($con, "DELETE FROM department WHERE id = '$deptid'");
    echo '<script>alert("Department deleted !!")</script>';
    echo '<script>window.location.href="department.php"</script>';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin | Department</title>
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <link href="../assets/css/style.css" rel="stylesheet" />
</head>

<body>
<?php include('includes/header.php'); ?>
<?php if($_SESSION['alogin'] != "") { include('includes/sidebar.php'); } ?>

<div class="content-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-head-line">Department Management</h1>
            </div>
        </div>

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
                                <input type="text" class="form-control" id="department" name="department" placeholder="Enter department name" required />
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
                            <table class="table table-striped">
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
                                    while($row = mysqli_fetch_array($sql)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $cnt; ?></td>
                                        <td><?php echo htmlentities($row['department']); ?></td>
                                        <td><?php echo htmlentities($row['creationDate']); ?></td>
                                        <td>
                                            <a href="department.php?id=<?php echo $row['id']; ?>&del=delete" onClick="return confirm('Are you sure you want to delete this department?')">
                                                <button class="btn btn-danger btn-sm">Delete</button>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php $cnt++; } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include('../includes/footer.php'); ?>

<script src="../assets/js/jquery-1.11.1.js"></script>
<script src="../assets/js/bootstrap.js"></script>

</body>
</html>
