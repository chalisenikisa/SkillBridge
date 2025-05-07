<?php
session_start();
$_SESSION = [];
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Logging Out</title>
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <link href="../assets/css/style.css" rel="stylesheet" />
    <script>
        setTimeout(function() {
            window.location.href = "index.php";
        }, 3000); // Redirect after 3 seconds
    </script>
</head>
<body>
    <?php include('../includes/header.php'); ?>
    
    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-md-3">
                    <?php include('../includes/sidebar.php'); ?>
                </div>

                <!-- Main content -->
                <div class="col-md-9">
                    <div class="alert alert-info mt-4">
                        You have successfully logged out. Redirecting to login page...
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('../includes/footer.php'); ?>
</body>
</html>
