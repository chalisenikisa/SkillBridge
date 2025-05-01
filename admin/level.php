<?php
session_start();
include('includes/config.php');

// Redirect if not logged in
if (!isset($_SESSION['alogin']) || strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
}

// Handle level creation
if (isset($_POST['submit'])) {
    $level = trim($_POST['level']);

    if (!empty($level)) {
        $stmt = $con->prepare("INSERT INTO level(level) VALUES (?)");
        $stmt->bind_param("s", $level);

        if ($stmt->execute()) {
            $_SESSION['msg'] = "Level Created Successfully!";
        } else {
            $_SESSION['msg'] = "Error: Level not created.";
        }

        $stmt->close();
    } else {
        $_SESSION['msg'] = "Level field cannot be empty.";
    }
}

// Handle deletion
if (isset($_GET['del']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $con->prepare("DELETE FROM level WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $_SESSION['delmsg'] = "Level deleted!";
    $stmt->close();
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin | Level</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
</head>
<body>
<?php include('includes/header.php'); ?>
<?php if ($_SESSION['alogin']) include('includes/menubar.php'); ?>

<div class="content-wrapper">
    <div class="container">

        <div class="row">
            <div class="col-md-12">
                <h1 class="page-head-line">Manage Levels</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3"></div>

            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">Add New Level</div>
                    <div class="panel-body">
                        <?php if (!empty($_SESSION['msg'])): ?>
                            <div class="alert alert-info text-center">
                                <?php 
                                    echo htmlentities($_SESSION['msg']); 
                                    $_SESSION['msg'] = "";
                                ?>
                            </div>
                        <?php endif; ?>

                        <form method="post">
                            <div class="form-group">
                                <label for="level">Level</label>
                                <input type="text" class="form-control" name="level" id="level" placeholder="Enter level" required />
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary">Create Level</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($_SESSION['delmsg'])): ?>
            <div class="alert alert-danger text-center">
                <?php 
                    echo htmlentities($_SESSION['delmsg']); 
                    $_SESSION['delmsg'] = "";
                ?>
            </div>
        <?php endif; ?>

        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Existing Levels</div>
                <div class="panel-body">
                    <div class="table-responsive table-bordered">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Level</th>
                                    <th>Creation Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $result = mysqli_query($con, "SELECT * FROM level ORDER BY id DESC");
                            $cnt = 1;
                            while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                <tr>
                                    <td><?php echo $cnt++; ?></td>
                                    <td><?php echo htmlentities($row['level']); ?></td>
                                    <td><?php echo htmlentities($row['creationDate']); ?></td>
                                    <td>
                                        <a href="level.php?id=<?php echo $row['id']; ?>&del=delete" onclick="return confirm('Are you sure you want to delete this level?');">
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

<?php include('includes/footer.php'); ?>

<script src="assets/js/jquery-1.11.1.js"></script>
<script src="assets/js/bootstrap.js"></script>
</body>
</html>
