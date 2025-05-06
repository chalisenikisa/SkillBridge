<?php
session_start();
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Admin | Enroll History</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <link href="../assets/css/style.css" rel="stylesheet" />

    <style>
        .main-content {
            margin-left: 220px; /* Same width as sidebar */
            padding: 20px;
        }
    </style>
</head>

<body>
    <?php include('includes/header.php'); ?>

    <?php if ($_SESSION['alogin'] != "")?>

    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="page-head-line">Enroll History</h1>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <!-- Bordered Table -->
                    <div class="panel panel-default">
                        <div class="panel-heading">User Login History</div>
                        <div class="panel-body">
                            <div class="table-responsive table-bordered">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Student Reg. No</th>
                                            <th>IP Address</th>
                                            <th>Login Time</th>
                                            <th>Logout Time</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = mysqli_query($con, "SELECT * FROM userlog ORDER BY id DESC");
                                        $cnt = 1;
                                        while ($row = mysqli_fetch_assoc($sql)) {
                                            ?>
                                            <tr>
                                                <td><?php echo $cnt++; ?></td>
                                                <td><?php echo htmlentities($row['studentRegno']); ?></td>
                                                <td><?php echo htmlentities($row['userip']); ?></td>
                                                <td><?php echo htmlentities($row['loginTime']); ?></td>
                                                <td><?php echo $row['logout'] ? htmlentities($row['logout']) : '<span class="text-muted">N/A</span>'; ?></td>
                                                <td>
                                                    <?php
                                                    if ($row['status'] == 1) {
                                                        echo '<span class="text-success">Success</span>';
                                                    } else {
                                                        echo '<span class="text-danger">Failed</span>';
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        if (mysqli_num_rows($sql) == 0) {
                                            echo '<tr><td colspan="6" class="text-center text-muted">No login records found.</td></tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- End Bordered Table -->
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
