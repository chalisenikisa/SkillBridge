<?php
session_start();
include('includes/config.php');
error_reporting(0);

if (empty($_SESSION['login'])) {
    header('Location:index.php');
    exit();
}

$msg = '';
$err = '';

if (isset($_POST['submit'])) {
    $studentName = trim($_POST['studentname']);
    $cgpa = trim($_POST['cgpa']);
    $regno = $_SESSION['login'];

    if (!empty($_FILES['photo']['name'])) {
        $photoName = basename($_FILES['photo']['name']);
        $targetDir = 'studentphoto/';
        $targetFile = $targetDir . $photoName;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
            $stmt = $con->prepare("UPDATE students SET studentName = ?, studentPhoto = ?, cgpa = ? WHERE StudentRegno = ?");
            $stmt->bind_param("ssis", $studentName, $photoName, $cgpa, $regno);
        } else {
            $err = "Failed to upload photo.";
        }
    } else {
        $stmt = $con->prepare("UPDATE students SET studentName = ?, cgpa = ? WHERE StudentRegno = ?");
        $stmt->bind_param("sis", $studentName, $cgpa, $regno);
    }

    if (empty($err) && isset($stmt)) {
        if ($stmt->execute()) {
            $msg = "Profile updated successfully!";
        } else {
            $err = "Error updating profile. Please try again.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Student | My Profile</title>
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

        .profile-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }

        .profile-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(to right, #2a2b75, #33aa79);
        }

        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .profile-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 30px;
            border: 3px solid #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .profile-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-info h3 {
            margin: 0 0 10px 0;
            color: #2a2b75;
        }

        .profile-info p {
            margin: 5px 0;
            color: #666;
        }

        .form-group {
            margin-bottom: 25px;
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
        }

        .form-control:focus {
            border-color: #33aa79;
            box-shadow: 0 0 8px rgba(51, 170, 121, 0.2);
        }

        .form-control[readonly] {
            background-color: #f8f9fa;
            cursor: not-allowed;
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

        .alert-success {
            background-color: #e5f9ee;
            color: #27ae60;
        }

        .alert-danger {
            background-color: #ffe5e5;
            color: #d63031;
        }

        .custom-file-upload {
            border: 1px dashed #ddd;
            border-radius: 4px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
        }

        .custom-file-upload:hover {
            border-color: #33aa79;
            background-color: #f0f0f0;
        }

        .custom-file-upload i {
            font-size: 24px;
            color: #33aa79;
            margin-bottom: 10px;
        }

        .custom-file-upload p {
            margin: 0;
            color: #666;
        }

        .photo-preview {
            margin-bottom: 20px;
            text-align: center;
        }

        .photo-preview img {
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 200px;
            max-height: 200px;
        }
    </style>
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
            
            <a href="recommendations.php" class="active">
                <i class="fas fa-lightbulb"></i> Recommended Courses
            </a>
            <a href="logout.php">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>

        <!-- Main content -->
        <div class="content">
            <h2 class="page-head-line"><i class="fas fa-user-circle me-2"></i> My Profile</h2>

            <?php
            $stmt = $con->prepare("SELECT studentName, StudentRegno, pincode, cgpa, studentPhoto FROM students WHERE StudentRegno = ?");
            $stmt->bind_param("s", $_SESSION['login']);
            $stmt->execute();
            $stmt->bind_result($currName, $currRegno, $currPin, $currCgpa, $currPhoto);
            $stmt->fetch();
            $stmt->close();
            ?>

            <?php if ($msg): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo htmlentities($msg); ?>
                </div>
            <?php elseif ($err): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?php echo htmlentities($err); ?>
                </div>
            <?php endif; ?>

            <div class="profile-card">
                <div class="profile-header">
                    <div class="profile-image">
                        <?php if ($currPhoto): ?>
                            <img src="studentphoto/<?php echo htmlentities($currPhoto); ?>" alt="Profile Photo">
                        <?php else: ?>
                            <img src="studentphoto/noimage.png" alt="No Photo">
                        <?php endif; ?>
                    </div>
                    <div class="profile-info">
                        <h3><?php echo htmlentities($currName); ?></h3>
                        <p><i class="fas fa-id-card me-2"></i> <strong>Registration:</strong> <?php echo htmlentities($currRegno); ?></p>
                        <p><i class="fas fa-map-marker-alt me-2"></i> <strong>Pincode:</strong> <?php echo htmlentities($currPin); ?></p>
                        <p><i class="fas fa-graduation-cap me-2"></i> <strong>CGPA:</strong> <?php echo htmlentities($currCgpa); ?></p>
                    </div>
                </div>

                <form method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-user me-2"></i>Student Name</label>
                                <input type="text" name="studentname" class="form-control" value="<?php echo htmlentities($currName); ?>" required />
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-id-card me-2"></i>Registration No</label>
                                <input type="text" class="form-control" value="<?php echo htmlentities($currRegno); ?>" readonly />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-map-marker-alt me-2"></i>Pincode</label>
                                <input type="text" class="form-control" value="<?php echo htmlentities($currPin); ?>" readonly />
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-graduation-cap me-2"></i>CGPA</label>
                                <input type="text" name="cgpa" class="form-control" value="<?php echo htmlentities($currCgpa); ?>" required />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-image me-2"></i>Update Profile Photo</label>
                        <div class="custom-file-upload" id="file-upload-wrapper" onclick="document.getElementById('file-upload').click();">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Click to upload a new photo</p>
                            <input type="file" name="photo" id="file-upload" style="display: none;" accept="image/*" onchange="updateFileName(this)" />
                            <p id="file-name">No file selected</p>
                        </div>
                    </div>

                    <button type="submit" name="submit" class="btn btn-update">
                        <i class="fas fa-sync-alt me-2"></i> Update Profile
                    </button>
                </form>
            </div>
        </div>
    </div>

    <?php include('includes/footer.php'); ?>

    <script src="assets/js/jquery-1.11.1.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script>
        function updateFileName(input) {
            const fileName = input.files[0] ? input.files[0].name : 'No file selected';
            document.getElementById('file-name').textContent = fileName;
        }
    </script>
</body>

</html>