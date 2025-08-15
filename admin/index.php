<?php
session_start();
include("includes/config.php");

// Clear previous error message
$_SESSION['errmsg'] = "";

function dd($data)
{
    echo '<pre>';
    print_r($data); // or var_dump($data) for more detailed info
    echo '</pre>';
    die;
}



// Handle login form submission
if (isset($_POST['submit'])) {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Use prepared statements to prevent SQL injection
    $stmt = $con->prepare("SELECT id, password FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['alogin'] = $username;
        $_SESSION['id'] = $admin['id'];
        header("Location: dashboard.php");
        exit();
    } else {
        $_SESSION['errmsg'] = "Invalid username or password";
        header("Location: index.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head`>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Login | SkillBridge</title>
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
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

        .login-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-top: 20px;
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

        .btn-login {
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

        .btn-login:hover {
            background: linear-gradient(to right, #33aa79, #2a2b75);
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

        footer {
            background: linear-gradient(to right, #2a2b75, #226a8b);
            color: rgba(255, 255, 255, 0.8);
            padding: 20px 0;
            text-align: center;
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
                            <li><a href="index.php" class="active"><i class="fa fa-lock"></i> Admin Login</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <div class="content-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="login-container">
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <h4 class="page-head-line">Admin Panel Login</h4>
                                </div>
                            </div>

                            <!-- Error Message -->
                            <?php if (!empty($_SESSION['errmsg'])): ?>
                                <div class="alert alert-danger">
                                    <i class="fa fa-exclamation-circle"></i> <?php echo htmlentities($_SESSION['errmsg']); ?>
                                </div>
                            <?php endif; ?>

                            <!-- Login Form -->
                            <form method="post" name="admin">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><i class="fa fa-user"></i> Username</label>
                                            <input type="text" name="username" class="form-control" placeholder="Enter your username" required />
                                        </div>

                                        <div class="form-group">
                                            <label><i class="fa fa-key"></i> Password</label>
                                            <input type="password" name="password" class="form-control" placeholder="Enter your password" required />
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" name="submit" class="btn btn-login">
                                                <i class="fa fa-sign-in"></i> Login to Dashboard
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="login-image">
                                            <img src="../assets/img/admin.png" class="img-responsive" alt="Admin Login">
                                        </div>
                                    </div>
                                </div>
                            </form>
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