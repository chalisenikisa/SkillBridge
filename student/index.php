<?php
session_start();
error_reporting(0);
include("includes/config.php");

if (isset($_POST['submit'])) {
    $regno = $_POST['regno'];
    $password = $_POST['password'];

    $query = $con->prepare("SELECT * FROM students WHERE StudentRegno = ?");
    $query->bind_param("s", $regno);
    $query->execute();
    $result = $query->get_result();
    $num = $result->fetch_assoc();

    if ($num && password_verify($password, $num['password'])) {
        $_SESSION['login'] = $regno;
        $_SESSION['id'] = $num['studentRegno'];
        $_SESSION['sname'] = $num['studentName'];

        $uip = $_SERVER['REMOTE_ADDR'];
        $status = 1;
        $log = $con->prepare("INSERT INTO userlog(studentRegno, userip, status) VALUES (?, ?, ?)");
        $log->bind_param("ssi", $_SESSION['login'], $uip, $status);
        $log->execute();

        header("Location: stuent-dashboard.php");
        exit();
    } else {
        $_SESSION['errmsg'] = "Invalid Reg no or Password";
        header("Location: index.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Student Login | SkillBridge</title>
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <link href="../assets/css/style.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            line-height: 1.6;
            padding-top: 0;
        }

        .menu-section {
            background: linear-gradient(to right, #33aa79, #226a8b);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 15px 0;
        }

        #menu-top a {
            padding: 15px 20px;
            transition: all 0.3s ease;
            color: #fff;
            text-decoration: none;
            font-weight: 500;
        }

        #menu-top a:hover,
        #menu-top a.active {
            background-color: rgba(255, 255, 255, 0.1);
            border-bottom: 3px solid #2a2b75;
        }

        .login-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-top: 40px;
            margin-bottom: 40px;
            position: relative;
            overflow: hidden;
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(to right, #33aa79, #2a2b75);
        }

        .page-head-line {
            color: #33aa79;
            font-weight: 600;
            padding-bottom: 15px;
            border-bottom: 2px solid #2a2b75;
            margin-bottom: 30px;
            font-size: 24px;
            text-align: center;
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

        .btn-login {
            background: linear-gradient(to right, #33aa79, #2a2b75);
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

        .btn-login:hover {
            background: linear-gradient(to right, #2a2b75, #33aa79);
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
        }

        .login-image {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
        }

        .login-image img {
            max-width: 80%;
            filter: drop-shadow(0 5px 10px rgba(0, 0, 0, 0.1));
            transition: all 0.5s ease;
        }

        .login-image img:hover {
            transform: scale(1.05);
        }

        .alert {
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .news-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            height: 100%;
            overflow: hidden;
        }

        .news-header {
            background: linear-gradient(to right, #33aa79, #2a2b75);
            color: white;
            padding: 15px;
            font-weight: 600;
            border-radius: 8px 8px 0 0;
        }

        .news-content {
            padding: 15px;
            max-height: 300px;
            overflow-y: auto;
        }

        .news-content ul {
            padding-left: 20px;
        }

        .news-content li {
            margin-bottom: 10px;
            border-bottom: 1px dashed #eee;
            padding-bottom: 10px;
        }

        .news-content a {
            color: #33aa79;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .news-content a:hover {
            color: #2a2b75;
            text-decoration: underline;
        }

        .error-message {
            color: #dc3545;
            background-color: rgba(220, 53, 69, 0.1);
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-weight: 500;
        }
    </style>
</head>

<body>

    <?php include('includes/header.php'); ?>

    <section class="menu-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ul id="menu-top" class="nav navbar-nav navbar-right">
                        <li><a href="index.php" class="active"><i class="fa fa-graduation-cap"></i> Student Login</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="login-container">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="page-head-line">Student Portal Login</h4>
                        </div>
                    </div>

                    <!-- Error Message -->
                    <?php if (!empty($_SESSION['errmsg'])): ?>
                        <div class="error-message">
                            <i class="fa fa-exclamation-circle"></i> <?php echo htmlentities($_SESSION['errmsg']); ?>
                            <?php $_SESSION['errmsg'] = ""; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Login Form -->
                    <form method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="login-image">
                                    <img src="../assets/img/student.png" class="img-responsive" alt="Student Login">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><i class="fa fa-id-card"></i> Registration Number</label>
                                    <input type="text" name="regno" class="form-control" placeholder="Enter your registration number" required />
                                </div>

                                <div class="form-group">
                                    <label><i class="fa fa-lock"></i> Password</label>
                                    <input type="password" name="password" class="form-control" placeholder="Enter your password" required />
                                </div>

                                <div class="form-group">
                                    <button type="submit" name="submit" class="btn btn-login btn-block">
                                        <i class="fa fa-sign-in"></i> Login to Dashboard
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="news-container">
                    <div class="news-header">
                        <i class="fa fa-newspaper-o"></i> Latest News & Updates
                    </div>
                    <div class="news-content">
                        <ul>
                            <?php
                            $sql = mysqli_query($con, "SELECT * FROM news ORDER BY postingDate DESC");
                            while ($row = mysqli_fetch_array($sql)) {
                                echo '<li><a href="news-details.php?nid=' . htmlentities($row['id']) . '">' .
                                    htmlentities($row['newstitle']) . ' <span class="text-muted"><small>- ' .
                                    htmlentities($row['postingDate']) . '</small></span></a></li>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('includes/footer.php'); ?>

    <script src="../assets/js/jquery-1.11.1.js"></script>
    <script src="../assets/js/bootstrap.js"></script>
</body>

</html>