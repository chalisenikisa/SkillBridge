<?php
session_start();
include('includes/config.php');
error_reporting(0);

// Redirect to login if not authenticated
if (empty($_SESSION['login'])) {
    header('Location:index.php');
    exit();
}

$msg = '';
$err = '';

// Handle form submission
if (isset($_POST['submit'])) {
    $studentName = trim($_POST['studentname']);
    $cgpa        = trim($_POST['cgpa']);
    $regno       = $_SESSION['login'];

    if (!empty($_FILES['photo']['name'])) {
        $photoName = basename($_FILES['photo']['name']);
        $targetDir = 'studentphoto/';
        $targetFile = $targetDir . $photoName;
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
            $stmt = $con->prepare(
                "UPDATE students 
                 SET studentName = ?, studentPhoto = ?, cgpa = ? 
                 WHERE StudentRegno = ?"
            );
            $stmt->bind_param("ssis", $studentName, $photoName, $cgpa, $regno);
        } else {
            $err = "Failed to upload photo.";
        }
    } else {
        $stmt = $con->prepare(
            "UPDATE students 
             SET studentName = ?, cgpa = ? 
             WHERE StudentRegno = ?"
        );
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
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <title>Student | My Profile</title>
  <link href="assets/css/bootstrap.css" rel="stylesheet" />
  <link href="assets/css/font-awesome.css" rel="stylesheet" />
  <link href="assets/css/style.css" rel="stylesheet" />
</head>
<body>
  <?php include('includes/header.php'); ?>

  <div class="content-wrapper">
    <div class="container-fluid">
      <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
          <?php include('includes/sidebar.php'); ?>
        </div>

        <!-- Main content -->
        <div class="col-md-9">
          <h2 class="page-head-line">My Profile</h2>

          <?php
          $stmt = $con->prepare("SELECT studentName, StudentRegno, pincode, cgpa, studentPhoto FROM students WHERE StudentRegno = ?");
          $stmt->bind_param("s", $_SESSION['login']);
          $stmt->execute();
          $stmt->bind_result($currName, $currRegno, $currPin, $currCgpa, $currPhoto);
          $stmt->fetch();
          $stmt->close();
          ?>

          <?php if ($msg): ?>
            <div class="alert alert-success"><?php echo htmlentities($msg); ?></div>
          <?php elseif ($err): ?>
            <div class="alert alert-danger"><?php echo htmlentities($err); ?></div>
          <?php endif; ?>

          <div class="panel panel-default">
            <div class="panel-heading">Update Profile</div>
            <div class="panel-body">
              <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                  <label>Student Name</label>
                  <input type="text" name="studentname" class="form-control" 
                         value="<?php echo htmlentities($currName); ?>" required />
                </div>

                <div class="form-group">
                  <label>Registration No</label>
                  <input type="text" class="form-control" 
                         value="<?php echo htmlentities($currRegno); ?>" readonly />
                </div>

                <div class="form-group">
                  <label>Pincode</label>
                  <input type="text" class="form-control" 
                         value="<?php echo htmlentities($currPin); ?>" readonly />
                </div>

                <div class="form-group">
                  <label>CGPA</label>
                  <input type="text" name="cgpa" class="form-control" 
                         value="<?php echo htmlentities($currCgpa); ?>" required />
                </div>

                <div class="form-group">
                  <label>Current Photo</label><br>
                  <?php if ($currPhoto): ?>
                    <img src="studentphoto/<?php echo htmlentities($currPhoto); ?>" 
                         alt="Profile Photo" width="150" class="img-thumbnail">
                  <?php else: ?>
                    <img src="studentphoto/noimage.png" 
                         alt="No Photo" width="150" class="img-thumbnail">
                  <?php endif; ?>
                </div>

                <div class="form-group">
                  <label>Upload New Photo</label>
                  <input type="file" name="photo" class="form-control" accept="image/*" />
                </div>

                <button type="submit" name="submit" class="btn btn-primary">Update</button>
              </form>
            </div>
          </div>

        </div> <!-- End main content col -->
      </div> <!-- End row -->
    </div> <!-- End container -->
  </div> <!-- End content-wrapper -->

  <?php include('includes/footer.php'); ?>

  <script src="assets/js/jquery-1.11.1.js"></script>
  <script src="assets/js/bootstrap.js"></script>
</body>
</html>
