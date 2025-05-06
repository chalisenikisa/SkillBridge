<?php
session_start();
include('includes/config.php');

// Redirect if not logged in
if (!isset($_SESSION['alogin']) || strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
}

// Handle Insert
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $sessionName = trim($_POST['session']);

    if (!empty($sessionName)) {
        $stmt = $con->prepare("INSERT INTO session (session) VALUES (?)");
        $stmt->bind_param("s", $sessionName);

        $_SESSION['msg'] = $stmt->execute() ? "Session Created Successfully!" : "Error: Session not created.";
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

    $_SESSION['delmsg'] = $stmt->execute() ? "Session Deleted Successfully!" : "Error deleting session.";
    $stmt->close();

    header("Location: session.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Admin | Session Management</title>
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
<?php if ($_SESSION['alogin'] != "") include('includes/sidebar.php'); ?>

<div class="main-content">
    <div class="container">

        <div class="row">
            <div class="col-md-12">
                <h1 class="page-head-line">Session Management</h1>
            </div>
        </div>

        <!-- Flash Message -->
        <?php if (!empty($_SESSION['msg'])): ?>
            <div class="alert alert-info text-center"><?php echo htmlentities($_SESSION['msg']); unset($_SESSION['msg']); ?></div>
        <?php endif; ?>

        <!-- Add Session Form -->
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">Add New Session</div>
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
        <?php if (!empty($_SESSION['delmsg'])): ?>
            <div class="alert alert-danger text-center"><?php echo htmlentities($_SESSION['delmsg']); unset($_SESSION['delmsg']); ?></div>
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
                                    $result = $con->query("SELECT * FROM session ORDER BY id DESC");
                                    $cnt = 1;
                                    while ($row = $result->fetch_assoc()):
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
                                    <?php endwhile; ?>
                                    <?php if ($result->num_rows === 0): ?>
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
