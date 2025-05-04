<?php
session_start();
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
}

// Flash messages
if (!isset($_SESSION['msg'])) $_SESSION['msg'] = '';
if (!isset($_SESSION['delmsg'])) $_SESSION['delmsg'] = '';

// Handle Insert
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $sessionName = trim($_POST['session']);

    if (!empty($sessionName)) {
        $stmt = $con->prepare("INSERT INTO session (session) VALUES (?)");
        $stmt->bind_param("s", $sessionName);

        if ($stmt->execute()) {
            $_SESSION['msg'] = "Session Created Successfully!";
        } else {
            $_SESSION['msg'] = "Error: Session not created.";
        }
        $stmt->close();
    } else {
        $_SESSION['msg'] = "Session field cannot be empty.";
    }

    header("Location: session.php");
    exit();
}

// Handle Delete
if (isset($_GET['del']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $con->prepare("DELETE FROM session WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['delmsg'] = "Session Deleted Successfully!";
    } else {
        $_SESSION['delmsg'] = "Error deleting session.";
    }
    $stmt->close();

    header("Location: session.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Admin | Session</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <link href="../assets/css/style.css" rel="stylesheet" />
</head>

<body>
<?php include('includes/header.php'); ?>
<?php if ($_SESSION['alogin'] != "") include('includes/menubar.php'); ?>

<div class="content-wrapper">
    <div class="container">

        <div class="row">
            <div class="col-md-12">
                <h1 class="page-head-line">Add Session</h1>
            </div>
        </div>

        <!-- Flash Message -->
        <?php if ($_SESSION['msg']): ?>
            <div class="alert alert-info text-center"><?php echo htmlentities($_SESSION['msg']); $_SESSION['msg'] = ""; ?></div>
        <?php endif; ?>

        <!-- Add Session Form -->
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">Create Session</div>
                    <div class="panel-body">
                        <form method="post">
                            <div class="form-group">
                                <label for="session">Session</label>
                                <input type="text" class="form-control" id="session" name="session" placeholder="e.g. 2024-2025" required />
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary">Add Session</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Flash Message -->
        <?php if ($_SESSION['delmsg']): ?>
            <div class="alert alert-danger text-center"><?php echo htmlentities($_SESSION['delmsg']); $_SESSION['delmsg'] = ""; ?></div>
        <?php endif; ?>

        <!-- Session Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Manage Sessions</div>
                    <div class="panel-body">
                        <div class="table-responsive table-bordered">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Session</th>
                                        <th>Creation Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $result = mysqli_query($con, "SELECT * FROM session ORDER BY id DESC");
                                    $cnt = 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                        <tr>
                                            <td><?php echo $cnt++; ?></td>
                                            <td><?php echo htmlentities($row['session']); ?></td>
                                            <td><?php echo htmlentities($row['creationDate']); ?></td>
                                            <td>
                                                <a href="session.php?id=<?php echo $row['id']; ?>&del=delete"
                                                   onclick="return confirm('Are you sure you want to delete this session?');">
                                                    <button class="btn btn-danger btn-sm">Delete</button>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php }
                                    if (mysqli_num_rows($result) == 0): ?>
                                        <tr>
                                            <td colspan="4" class="text-center">No sessions found.</td>
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
