<?php
session_start();
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
} else {
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Course Enrollment Print</title>
    <style>
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, .15);
            font-size: 16px;
            line-height: 18px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
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

        .invoice-box table tr.top table td {
            padding-bottom: 10px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 30px;
            color: #333;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.details td,
        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td,
            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }

        img.photo {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 4px;
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <?php
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $cid = intval($_GET['id']);

            $sql = mysqli_query($con, "SELECT 
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
                students.StudentRegno AS sregno,
                students.creationdate AS studentregdate
                FROM courseenrolls 
                JOIN course ON course.id = courseenrolls.course 
                JOIN session ON session.id = courseenrolls.session 
                JOIN department ON department.id = courseenrolls.department 
                JOIN level ON level.id = courseenrolls.level 
                JOIN students ON students.StudentRegno = courseenrolls.StudentRegno 
                JOIN semester ON semester.id = courseenrolls.semester 
                WHERE courseenrolls.course = '$cid'");

            if (mysqli_num_rows($sql) > 0) {
                while ($row = mysqli_fetch_array($sql)) {
        ?>
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <?php
                                $photo = htmlentities($row['photo']);
                                $imgPath = $photo && file_exists("../studentphoto/$photo") ? "../studentphoto/$photo" : "../studentphoto/noimage.png";
                                ?>
                                <img class="photo" src="<?php echo $imgPath; ?>" width="200" height="200">
                            </td>
                            <td>
                                <b>Reg No:</b> <?php echo htmlentities($row['sregno']); ?><br>
                                <b>Student Name:</b> <?php echo htmlentities($row['studentname']); ?><br>
                                <b>Student Reg Date:</b> <?php echo htmlentities($row['studentregdate']); ?><br>
                                <b>Enroll Date:</b> <?php echo htmlentities($row['edate']); ?><br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="heading"><td colspan="2">Course Details</td></tr>
            <tr class="details">
                <td>Course Code</td>
                <td><?php echo htmlentities($row['ccode']); ?></td>
            </tr>
            <tr class="details">
                <td>Course Name</td>
                <td><?php echo htmlentities($row['courname']); ?></td>
            </tr>
            <tr class="details">
                <td>Course Unit</td>
                <td><?php echo htmlentities($row['cunit']); ?></td>
            </tr>

            <tr class="heading"><td colspan="2">Other Details</td></tr>
            <tr class="item">
                <td>Session</td>
                <td><?php echo htmlentities($row['session']); ?></td>
            </tr>
            <tr class="item">
                <td>Department</td>
                <td><?php echo htmlentities($row['dept']); ?></td>
            </tr>
            <tr class="item">
                <td>Level</td>
                <td><?php echo htmlentities($row['level']); ?></td>
            </tr>
            <tr class="item">
                <td>CGPA</td>
                <td><?php echo htmlentities($row['scgpa']); ?></td>
            </tr>
            <tr class="item last">
                <td>Semester</td>
                <td><?php echo htmlentities($row['sem']); ?></td>
            </tr>
        </table>
        <br><hr><br>
        <?php
                } // end while
            } else {
                echo "<p style='text-align:center;color:red;'>No record found for this Course ID.</p>";
            }
        } else {
            echo "<p style='text-align:center;color:red;'>Invalid Course ID.</p>";
        }
        ?>
    </div>
</body>
</html>

<?php } ?>
