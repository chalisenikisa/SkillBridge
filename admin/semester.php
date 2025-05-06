<?php
session_start();
include('includes/config.php');

// Redirect if admin not logged in
if (!isset($_SESSION['alogin']) || strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $semester = trim($_POST['semester']);

    if (!empty($semester)) {
        $stmt = $con->prepare("INSERT INTO semester (semester) VALUES (?)");
        $stmt->bind_param("s", $semester);

        $_SESSION['msg'] = $stmt->execute() ? "Semester Created Successfully!" : "Something went wrong. Please try again.";
        $stmt->close();
    } else {
        $_SESSION['msg'] = "Semester field cannot be empty.";
    }

    header("Location: semester.php");
    exit();
}

// Handle delete request
if (isset($_GET['del']) && isset($_GET['id'])) {
    $sid = intval($_GET['id']);
    $stmt = $con->prepare("DELETE FROM semester WHERE id = ?");
    $stmt->bind_param("i", $sid);

    $_SESSION['delmsg'] = $stmt->execute() ? "Semester Deleted Successfully!" : "Failed to delete semester.";
    $stmt->close();

    header("Location: semester.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin | Semester</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <link href="../assets/css/style.css" rel="stylesheet" />

    <style>
        .main-content {
            margin-left: 220px;
            padding: 20px;
        }
    </style>
</head>
<body>

<?php include('includes/header.php'); ?>
<?php include('includes/sidebar.php'); ?>

<div class="main-content">
    <div class="container">

        <div class="row">
            <div class="col-md-12">
                <h1 class="page-head-line">Semester Management</h1>
            </div>
        </div>

        <!-- Display message -->
        <?php if (!empty($_SESSION['msg'])): ?>
            <div class="alert alert-info">
                <?php echo htmlentities($_SESSION['msg']); unset($_SESSION['msg']); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['delmsg'])): ?>
            <div class="alert alert-danger">
                <?php echo htmlentities($_SESSION['delmsg']); unset($_SESSION['delmsg']); ?>
            </div>
        <?php endif; ?>

        <!-- Add Semester Form -->
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">Add New Semester</div>
                    <div class="panel-body">
                        <form method="post">
                            <div class="form-group">
                                <label for="semester">Semester Name</label>
                                <input type="text" class="form-control" id="semester" name="semester" required />
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary">Add Semester</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Semester Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Manage Semesters</div>
                    <div class="panel-body">
                        <div class="table-responsive table-bordered">
                            <table class="table table-striped">
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
                                    $result = $con->query("SELECT * FROM semester ORDER BY id DESC");
                                    $cnt = 1;
                                    while ($row = $result->fetch_assoc()):
                                    ?>
                                    <tr>
                                        <td><?php echo $cnt++; ?></td>
                                        <td><?php echo htmlentities($row['semester']); ?></td>
                                        <td><?php echo htmlentities($row['creationDate']); ?></td>
                                        <td>
                                            <a href="semester.php?id=<?php echo $row['id']; ?>&del=delete"
                                               onclick="return confirm('Are you sure you want to delete this semester?');">
                                                <button class="btn btn-danger btn-sm">Delete</button>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                    <?php if ($result->num_rows === 0): ?>
                                        <tr>
                                            <td colspan="4" class="text-center">No semesters found.</td>
                                        </tr>
                                    <?php endif; ?>
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
