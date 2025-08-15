<?php
session_start();
include('includes/config.php');
error_reporting(0);

if (strlen($_SESSION['login']) == 0) {
    header('location:index.php');
    exit();
} else {
    date_default_timezone_set('Asia/Kathmandu');
    $currentTime = date('d-m-Y h:i:s A', time());

    if (isset($_POST['submit'])) {
        $regno = $_SESSION['login'];
        $currentpass = $_POST['cpass'];
        $newpass = $_POST['newpass'];
        $cnfpass = $_POST['cnfpass'];

        $sql = mysqli_query($con, "SELECT password FROM students WHERE studentRegno='$regno'");
        $row = mysqli_fetch_array($sql);

        if ($row && password_verify($currentpass, $row['password'])) {
            if ($newpass === $cnfpass) {
                $newHashedPass = password_hash($newpass, PASSWORD_DEFAULT);
                $update = mysqli_query($con, "UPDATE students SET password='$newHashedPass', updationDate='$currentTime' WHERE studentRegno='$regno'");

                $_SESSION['msg'] = $update ? "Password changed successfully!" : "Error updating password!";
            } else {
                $_SESSION['msg'] = "New and confirm passwords do not match!";
            }
        } else {
            $_SESSION['msg'] = "Current password is incorrect!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Student | Change Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: linear-gradient(to bottom, #2a2b75, #226a8b);
            padding: 20px 0;
            border-radius: 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .sidebar a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            margin-bottom: 5px;
        }

        .sidebar a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 3px solid #33aa79;
        }

        .content {
            flex: 1;
            padding: 30px;
            background-color: #f5f7fa;
        }

        .page-head-line {
            color: #2a2b75;
            font-weight: 600;
            padding-bottom: 15px;
            border-bottom: 2px solid #33aa79;
            margin-bottom: 30px;
            font-size: 24px;
        }

        .password-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }

        .password-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(to right, #2a2b75, #33aa79);
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            font-weight: 500;
            color: #555;
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            height: 45px;
            border-radius: 4px;
            border: 1px solid #ddd;
            box-shadow: none;
            transition: all 0.3s ease;
            padding: 10px 15px;
            padding-right: 40px;
        }

        .form-control:focus {
            border-color: #33aa79;
            box-shadow: 0 0 8px rgba(51, 170, 121, 0.2);
        }

        .password-toggle {
            position: absolute;
            right: 10px;
            top: 40px;
            cursor: pointer;
            color: #777;
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

        .alert {
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 20px;
            border: none;
        }

        .alert-info {
            background-color: #e5f9ee;
            color: #27ae60;
        }

        .password-strength {
            height: 5px;
            margin-top: 5px;
            border-radius: 3px;
            background-color: #eee;
            position: relative;
            overflow: hidden;
        }

        .password-strength-meter {
            height: 100%;
            width: 0;
            border-radius: 3px;
            transition: all 0.3s ease;
        }

        .password-tips {
            margin-top: 10px;
            font-size: 12px;
            color: #777;
        }

        .password-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .password-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(to right, #2a2b75, #33aa79);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            color: white;
            font-size: 24px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .password-info h3 {
            margin: 0 0 10px 0;
            color: #2a2b75;
        }

        .password-info p {
            margin: 0;
            color: #666;
        }
    </style>

    <script type="text/javascript">
        function valid() {
            if (document.chngpwd.cpass.value == "") {
                alert("Current Password field is empty!");
                document.chngpwd.cpass.focus();
                return false;
            } else if (document.chngpwd.newpass.value == "") {
                alert("New Password field is empty!");
                document.chngpwd.newpass.focus();
                return false;
            } else if (document.chngpwd.cnfpass.value == "") {
                alert("Confirm Password field is empty!");
                document.chngpwd.cnfpass.focus();
                return false;
            } else if (document.chngpwd.newpass.value != document.chngpwd.cnfpass.value) {
                alert("Password and Confirm Password do not match!");
                document.chngpwd.cnfpass.focus();
                return false;
            }
            return true;
        }

        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(inputId + '-toggle');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function checkPasswordStrength(password) {
            const meter = document.getElementById('password-strength-meter');
            const tips = document.getElementById('password-tips');

            // Default strength
            let strength = 0;
            let color = '#ddd';
            let message = 'Password is too weak';

            if (password.length >= 8) strength += 1;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength += 1;
            if (password.match(/[0-9]/)) strength += 1;
            if (password.match(/[^a-zA-Z0-9]/)) strength += 1;

            switch (strength) {
                case 0:
                    color = '#ddd';
                    message = 'Password is too weak';
                    break;
                case 1:
                    color = '#ff4d4d';
                    message = 'Password is weak';
                    break;
                case 2:
                    color = '#ffaa00';
                    message = 'Password is moderate';
                    break;
                case 3:
                    color = '#2db94e';
                    message = 'Password is strong';
                    break;
                case 4:
                    color = '#33aa79';
                    message = 'Password is very strong';
                    break;
            }

            meter.style.width = (strength * 25) + '%';
            meter.style.backgroundColor = color;
            tips.textContent = message;
        }
    </script>
</head>

<body>
    <?php include('includes/header.php'); ?>

    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <a href="enroll.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'enroll.php' ? 'active' : ''; ?>">
                <i class="fas fa-book-open"></i> Enroll for Course
            </a>
            <a href="enroll-history.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'enroll-history.php' ? 'active' : ''; ?>">
                <i class="fas fa-history"></i> Enroll History
            </a>
            <a href="my-profile.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'my-profile.php' ? 'active' : ''; ?>">
                <i class="fas fa-user"></i> My Profile
            </a>
            <a href="change-password.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'change-password.php' ? 'active' : ''; ?>">
                <i class="fas fa-key"></i> Change Password
            </a>
            <a href="logout.php">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>

        <!-- Main Content -->
        <div class="content">
            <h2 class="page-head-line"><i class="fas fa-key me-2"></i> Change Password</h2>

            <?php if (!empty($_SESSION['msg'])): ?>
                <div class="alert alert-info">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo htmlentities($_SESSION['msg']);
                    unset($_SESSION['msg']); ?>
                </div>
            <?php endif; ?>

            <div class="password-card">
                <div class="password-header">
                    <div class="password-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="password-info">
                        <h3>Update Your Password</h3>
                        <p>Ensure your account is secure with a strong password</p>
                    </div>
                </div>

                <form name="chngpwd" method="post" onsubmit="return valid();">
                    <div class="form-group">
                        <label for="cpass"><i class="fas fa-unlock-alt me-2"></i>Current Password</label>
                        <input type="password" class="form-control" name="cpass" id="cpass" placeholder="Enter your current password" />
                        <i class="fas fa-eye password-toggle" id="cpass-toggle" onclick="togglePassword('cpass')"></i>
                    </div>

                    <div class="form-group">
                        <label for="newpass"><i class="fas fa-key me-2"></i>New Password</label>
                        <input type="password" class="form-control" name="newpass" id="newpass" placeholder="Enter your new password" onkeyup="checkPasswordStrength(this.value)" />
                        <i class="fas fa-eye password-toggle" id="newpass-toggle" onclick="togglePassword('newpass')"></i>
                        <div class="password-strength">
                            <div class="password-strength-meter" id="password-strength-meter"></div>
                        </div>
                        <div class="password-tips" id="password-tips">Password strength will be shown here</div>
                    </div>

                    <div class="form-group">
                        <label for="cnfpass"><i class="fas fa-check-circle me-2"></i>Confirm Password</label>
                        <input type="password" class="form-control" name="cnfpass" id="cnfpass" placeholder="Confirm your new password" />
                        <i class="fas fa-eye password-toggle" id="cnfpass-toggle" onclick="togglePassword('cnfpass')"></i>
                    </div>

                    <button type="submit" name="submit" class="btn btn-update">
                        <i class="fas fa-sync-alt me-2"></i> Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>

    <?php include('includes/footer.php'); ?>

    <script src="assets/js/jquery-1.11.1.js"></script>
    <script src="assets/js/bootstrap.js"></script>
</body>

</html>