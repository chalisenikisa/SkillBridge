<?php 
session_start();
include('includes/config.php');
error_reporting(0);

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
} else {

    // Add News
    if (isset($_POST['submit'])) {
        $ntitle = mysqli_real_escape_string($con, $_POST['newstitle']);
        $ndescription = mysqli_real_escape_string($con, $_POST['description']);

        $ret = mysqli_query($con, "INSERT INTO news (newstitle, newsDescription) VALUES ('$ntitle', '$ndescription')");
        if ($ret) {
            echo '<script>alert("News added successfully"); window.location.href="news.php";</script>';
        } else {
            echo '<script>alert("Something went wrong. Please try again."); window.location.href="news.php";</script>';
        }
    }

    // Delete News
    if (isset($_GET['del']) && isset($_GET['id'])) {
        $nid = mysqli_real_escape_string($con, $_GET['id']);
        mysqli_query($con, "DELETE FROM news WHERE id = '$nid'");
        echo '<script>alert("News deleted successfully."); window.location.href="news.php";</script>';
    }
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Admin | News</title>
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <link href="../assets/css/style.css" rel="stylesheet" />
</head>

<body>
<?php include('includes/header.php'); ?>
<?php if ($_SESSION['alogin'] != "") { ?>

<div class="content-wrapper">
    <div class="container">
        <!-- Page Heading -->
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-head-line">Manage News</h1>
            </div>
        </div>

        <!-- News Submission Form -->
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">Add News</div>
                    <div class="panel-body">
                        <form method="post">
                            <div class="form-group">
                                <label for="newstitle">News Title</label>
                                <input type="text" class="form-control" id="newstitle" name="newstitle" placeholder="News Title" required />
                            </div>

                            <div class="form-group">
                                <label for="description">News Description</label>
                                <textarea class="form-control" id="description" name="description" placeholder="News Description" required></textarea>
                            </div>

                            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- News Management Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Existing News</div>
                    <div class="panel-body">
                        <div class="table-responsive table-bordered">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>News Title</th>
                                        <th>Description</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
<?php
$sql = mysqli_query($con, "SELECT * FROM news ORDER BY postingDate DESC");
$cnt = 1;
while ($row = mysqli_fetch_array($sql)) {
?>
<tr>
    <td><?php echo $cnt; ?></td>
    <td><?php echo htmlentities($row['newstitle']); ?></td>
    <td><?php echo htmlentities($row['newsDescription']); ?></td>
    <td><?php echo htmlentities($row['postingDate']); ?></td>
    <td>
        <a href="news.php?id=<?php echo $row['id']; ?>&del=delete" onClick="return confirm('Are you sure you want to delete this news?')">
            <button class="btn btn-danger btn-sm">Delete</button>
        </a>
    </td>
</tr>
<?php
    $cnt++;
}
?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> <!-- End Panel -->
            </div>
        </div> <!-- End Row -->
    </div> <!-- End Container -->
</div> <!-- End Content Wrapper -->

<?php include('../includes/footer.php'); ?>

<!-- Scripts -->
<script src="../assets/js/jquery-1.11.1.js"></script>
<script src="../assets/js/bootstrap.js"></script>
</body>
</html>

<?php } } ?>
