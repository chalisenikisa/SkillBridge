<?php
session_start();
include('includes/config.php');

// Redirect to login if not authenticated
if (empty($_SESSION['alogin'])) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <title>Admin | Enroll History</title>
  <link href="../assets/css/bootstrap.css" rel="stylesheet" />
  <link href="../assets/css/font-awesome.css" rel="stylesheet" />
  <link href="../assets/css/style.css" rel="stylesheet" />
</head>
<body>
  <?php include('includes/header.php'); ?>
  

  <div class="content-wrapper">
    <div class="container">
      <div class="row mb-3">
        <div class="col-md-12">
          <h1 class="page-head-line">Enroll History</h1>
        </div>
      </div>

      <div class="panel panel-default">
        <div class="panel-heading">Enroll History</div>
        <div class="panel-body">
          <div class="table-responsive table-bordered">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Student Name</th>
                  <th>Reg No</th>
                  <th>Course Name</th>
                  <th>Session</th>
                  <th>Semester</th>
                  <th>Enrollment Date</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
<?php
$sql = "
  SELECT 
    ce.course          AS cid,
    c.courseName       AS courname,
    s.session          AS session,
    d.department       AS dept,
    sem.semester       AS sem,
    ce.enrollDate      AS edate,
    st.studentName     AS sname,
    st.StudentRegno    AS sregno
  FROM courseenrolls ce
  JOIN course     c   ON c.id             = ce.course
  JOIN session    s   ON s.id             = ce.session
  JOIN department d   ON d.id             = ce.department
  JOIN semester   sem ON sem.id           = ce.semester
  JOIN students   st  ON st.StudentRegno  = ce.studentRegno
  ORDER BY ce.enrollDate DESC
";
$result = mysqli_query($con, $sql);
$cnt = 1;
while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                  <td><?php echo (int)$cnt; ?></td>
                  <td><?php echo htmlentities($row['sname']); ?></td>
                  <td><?php echo htmlentities($row['sregno']); ?></td>
                  <td><?php echo htmlentities($row['courname']); ?></td>
                  <td><?php echo htmlentities($row['session']); ?></td>
                  <td><?php echo htmlentities($row['sem']); ?></td>
                  <td><?php echo htmlentities($row['edate']); ?></td>
                  <td>
                    <a href="print.php?id=<?php echo urlencode($row['cid']); ?>" target="_blank">
                      <button class="btn btn-primary btn-sm">
                        <i class="fa fa-print"></i> Print
                      </button>
                    </a>
                  </td>
                </tr>
<?php 
  $cnt++;
endwhile; ?>
              </tbody>
            </table>
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
