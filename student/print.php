<?php
session_start();
include('includes/config.php');
error_reporting(0);

if (strlen($_SESSION['login']) == 0) {
    header('location:index.php');
    exit();
}

$cid = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $con->prepare("
    SELECT 
        course.courseName AS courname,
        course.courseCode AS ccode,
        course.courseUnit AS cunit,
        session.session AS session,
        department.department AS dept,
        level.level AS level,
        courseenrolls.enrollDate AS edate,
        semester.semester AS sem,
        students.studentName AS studentname,
        students.studentPhoto AS photo,
        students.cgpa AS scgpa,
        students.creationdate AS studentregdate
    FROM courseenrolls
    JOIN course ON course.id = courseenrolls.course
    JOIN session ON session.id = courseenrolls.session
    JOIN department ON department.id = courseenrolls.department
    JOIN level ON level.id = courseenrolls.level
    JOIN students ON students.StudentRegno = courseenrolls.StudentRegno
    JOIN semester ON semester.id = courseenrolls.semester
    WHERE courseenrolls.StudentRegno = ? AND courseenrolls.course = ?
");

$stmt->bind_param("si", $_SESSION['login'], $cid);
$stmt->execute();
$result = $stmt->get_result();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Course Enrollment Print</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0,0,0,0.15);
            font-size: 16px;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box img {
            max-width: 150px;
            height: auto;
            border-radius: 5px;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr td {
                display: block;
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <?php while ($row = $result->fetch_assoc()) { ?>
            <table cellpadding="0" cellspacing="0">
                <tr class="top">
                    <td colspan="2">
                        <table>
                            <tr>
                                <td class="title">
                                    <?php if (empty($row['photo'])) { ?>
                                        <img src="studentphoto/noimage.png" alt="No image">
                                    <?php } else { ?>
                                        <img src="studentphoto/<?php echo htmlentities($row['photo']); ?>" alt="Student photo">
                                    <?php } ?>
                                </td>
                                <td>
                                    <b>Reg No:</b> <?php echo htmlentities($_SESSION['login']); ?><br>
                                    <b>Student Name:</b> <?php echo htmlentities($row['studentname']); ?><br>
                                    <b>Reg Date:</b> <?php echo htmlentities($row['studentregdate']); ?><br>
                                    <b>Enroll Date:</b> <?php echo htmlentities($row['edate']); ?><br>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr class="heading"><td colspan="2">Course Details</td></tr>
                <tr class="item"><td>Course Code</td><td><?php echo htmlentities($row['ccode']); ?></td></tr>
                <tr class="item"><td>Course Name</td><td><?php echo htmlentities($row['courname']); ?></td></tr>
                <tr class="item"><td>Course Unit</td><td><?php echo htmlentities($row['cunit']); ?></td></tr>

                <tr class="heading"><td colspan="2">Other Details</td></tr>
                <tr class="item"><td>Session</td><td><?php echo htmlentities($row['session']); ?></td></tr>
                <tr class="item"><td>Department</td><td><?php echo htmlentities($row['dept']); ?></td></tr>
                <tr class="item"><td>Level</td><td><?php echo htmlentities($row['level']); ?></td></tr>
                <tr class="item"><td>CGPA</td><td><?php echo htmlentities($row['scgpa']); ?></td></tr>
                <tr class="item"><td>Semester</td><td><?php echo htmlentities($row['sem']); ?></td></tr>
            </table>
        <?php } ?>
    </div>
</body>
</html>
