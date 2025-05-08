<?php
session_start();
include('includes/config.php');
error_reporting(0);

// Redirect to login if not authenticated
if (empty($_SESSION['login'])) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <title>Enroll History</title>
  <link href="assets/css/bootstrap.css" rel="stylesheet" />
  <link href="assets/css/font-awesome.css" rel="stylesheet" />
  <link href="assets/css/style.css" rel="stylesheet" />

  <style>
    body {
      font-family: Arial, sans-serif;
    }
    .page-head-line {
      font-size: 24px;
      font-weight: bold;
      margin-bottom: 20px;
      border-bottom: 2px solid #eee;
      padding-bottom: 10px;
    }
    .sidebar {
      background-color: #f8f9fa;
      padding: 15px;
      height: 100%;
      border-right: 1px solid #ddd;
    }
    .content-wrapper {
      padding: 20px 0;
      background-color: #fff;
    }
  </style>
</head>
<body>
  <?php include('includes/header.php'); ?>

  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <div class="col-md-3 sidebar">
        <?php include('includes/sidebar.php'); ?>
      </div>

      <!-- Main Content -->
      <div class="col-md-9">
        <h1 class="page-head-line">Enroll History</h1>

        <div class="panel panel-default">
          <div class="panel-heading">Your Enrollments</div>
          <div class="panel-body">
            <div class="table-responsive table-bordered">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Course Name</th>
                    <th>Session</th>
                    <th>Department</th>
                    <th>Level</th>
                    <th>Semester</th>
                    <th>Enrollment Date</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $stmt = $con->prepare(
                    "SELECT 
                       c.courseName, s.session, d.department, l.level, sem.semester, ce.enrollDate, ce.course 
                     FROM courseenrolls ce
                     JOIN course     c   ON c.id   = ce.course
                     JOIN session    s   ON s.id   = ce.session
                     JOIN department d   ON d.id   = ce.department
                     JOIN level      l   ON l.id   = ce.level
                     JOIN semester   sem ON sem.id = ce.semester
                     WHERE ce.studentRegno = ?
                     ORDER BY ce.enrollDate DESC"
                  );
                  $stmt->bind_param("s", $_SESSION['login']);
                  $stmt->execute();
                  $result = $stmt->get_result();
                  $cnt = 1;
                  while ($row = $result->fetch_assoc()):
                  ?>
                  <tr>
                    <td><?php echo $cnt; ?></td>
                    <td><?php echo htmlentities($row['courseName']); ?></td>
                    <td><?php echo htmlentities($row['session']); ?></td>
                    <td><?php echo htmlentities($row['department']); ?></td>
                    <td><?php echo htmlentities($row['level']); ?></td>
                    <td><?php echo htmlentities($row['semester']); ?></td>
                    <td><?php echo htmlentities($row['enrollDate']); ?></td>
                    <td>
                      <a href="print.php?id=<?php echo urlencode($row['course']); ?>" target="_blank">
                        <button class="btn btn-primary btn-sm">
                          <i class="fa fa-print"></i> Print
                        </button>
                      </a>
                    </td>
                  </tr>
                  <?php 
                    $cnt++;
                  endwhile;
                  $stmt->close();
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div> <!-- End Main Content -->
    </div> <!-- End Row -->
  </div> <!-- End Container -->

  <?php include('includes/footer.php'); ?>

  <!-- Scripts -->
  <script src="assets/js/jquery-1.11.1.js"></script>
  <script src="assets/js/bootstrap.js"></script>
</body>
</html>
