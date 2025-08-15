<?php
session_start();
include("includes/config.php");

// Redirect if admin is not logged in
if (!isset($_SESSION['alogin'])) {
    header('Location: index.php');
    exit();
}

// Handle password change
if (isset($_POST['submit'])) {
    $currentPassword = $_POST['currentpassword'];
    $newPassword = $_POST['newpassword'];
    $confirmPassword = $_POST['confirmpassword'];

    $query = mysqli_query($con, "SELECT * FROM admin WHERE username='" . $_SESSION['alogin'] . "'");
    $num = mysqli_fetch_array($query);

    if ($num) {
        $storedHash = $num['password'];

        if (password_verify($currentPassword, $storedHash)) {
            if ($newPassword == $confirmPassword) {
                $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $updateQuery = mysqli_query($con, "UPDATE admin SET password='$newHashedPassword' WHERE username='" . $_SESSION['alogin'] . "'");

                if ($updateQuery) {
                    $_SESSION['msg'] = "Password updated successfully!";
                    header("Location: logout.php"); // Log out after successful password change
                    exit();
                } else {
                    $_SESSION['errmsg'] = "Error updating password!";
                }
            } else {
                $_SESSION['errmsg'] = "New password and confirm password do not match!";
            }
        } else {
            $_SESSION['errmsg'] = "Current password is incorrect!";
        }
    } else {
        $_SESSION['errmsg'] = "No admin user found!";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin - Change Password</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    <link href="../assets/css/style.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            line-height: 1.6;
        }

        .navbar-brand {
            padding: 15px;
        }

        .menu-section {
            background: linear-gradient(to right, #2a2b75, #226a8b);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        #menu-top a {
            padding: 15px 20px;
            transition: all 0.3s ease;
        }

        #menu-top a:hover,
        #menu-top a.active {
            background-color: rgba(255, 255, 255, 0.1);
            border-bottom: 3px solid #33aa79;
        }

        .content-wrapper {
            display: flex;
            margin-top: 20px;
            min-height: 600px;
            padding-bottom: 60px;
        }

        .sidebar {
            width: 250px;
            background: linear-gradient(to bottom, #2a2b75, #226a8b);
            padding: 20px 0;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-right: 20px;
        }

        .sidebar a {
            display: block;
            color: #fff;
            padding: 12px 20px;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .sidebar a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 3px solid #33aa79;
        }

        .main-content {
            flex: 1;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 30px;
            position: relative;
            overflow: hidden;
        }

        .main-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(to right, #2a2b75, #33aa79);
        }

        .page-head-line {
            color: #2a2b75;
            font-weight: 600;
            padding-bottom: 15px;
            border-bottom: 2px solid #33aa79;
            margin-bottom: 30px;
            font-size: 24px;
        }

        .form-control {
            height: 45px;
            border-radius: 4px;
            border: 1px solid #ddd;
            box-shadow: none;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #33aa79;
            box-shadow: 0 0 8px rgba(51, 170, 121, 0.2);
        }

        .form-group label {
            font-weight: 500;
            color: #555;
            margin-bottom: 8px;
        }

        .btn-update {
            background: linear-gradient(to right, #2a2b75, #33aa79);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 4px;
            font-weight: 500;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-update:hover {
            background: linear-gradient(to right, #33aa79, #2a2b75);
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
        }

        .password-field {
            position: relative;
        }

        .password-field .form-control {
            padding-right: 40px;
        }

        .password-toggle {
            position: absolute;
            right: 10px;
            top: 40px;
            cursor: pointer;
            color: #777;
        }

        .alert {
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 20px;
            border: none;
        }

        .alert-danger {
            background-color: #ffe5e5;
            color: #d63031;
        }

        .alert-success {
            background-color: #e5f9ee;
            color: #27ae60;
        }
    </style>
</head>

<body>
    <?php include('includes/header.php'); ?>

    <div class="container">
        <div class="content-wrapper">
            <!-- Sidebar -->

            <!-- Main content -->
            <div class="main-content">
                <h4 class="page-head-line"><i class="fas fa-key me-2"></i> Change Your Password</h4>

                <?php if (!empty($_SESSION['errmsg'])): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo htmlentities($_SESSION['errmsg']);
                        unset($_SESSION['errmsg']); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($_SESSION['msg'])): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        <?php echo htmlentities($_SESSION['msg']);
                        unset($_SESSION['msg']); ?>
                    </div>
                <?php endif; ?>

                <form method="post" name="changePassword">
                    <div class="form-group password-field">
                        <label><i class="fas fa-lock me-2"></i>Enter Current Password:</label>
                        <input type="password" name="currentpassword" class="form-control" required />
                        <span class="password-toggle" onclick="togglePassword(this)">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>

                    <div class="form-group password-field">
                        <label><i class="fas fa-key me-2"></i>Enter New Password:</label>
                        <input type="password" name="newpassword" class="form-control" required />
                        <span class="password-toggle" onclick="togglePassword(this)">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>

                    <div class="form-group password-field">
                        <label><i class="fas fa-check-circle me-2"></i>Confirm New Password:</label>
                        <input type="password" name="confirmpassword" class="form-control" required />
                        <span class="password-toggle" onclick="togglePassword(this)">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>

                    <button type="submit" name="submit" class="btn btn-update">
                        <i class="fas fa-sync-alt me-2"></i> Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>

    <?php include('includes/footer.php'); ?>
    <script src="../assets/js/jquery-1.11.1.js"></script>
    <script src="../assets/js/bootstrap.js"></script>
    <script>
        function togglePassword(element) {
            const passwordInput = element.parentElement.querySelector('input');
            const icon = element.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>