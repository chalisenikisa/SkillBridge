<?php
session_start();
include('includes/config.php');

if (strlen($_SESSION['login']) == 0) {
    header('location:index.php');
    exit();
} else {
    $studentRegNo = $_SESSION['login'];

    // Get student info (only student name for display; no dept/sem found in your table)
    $studentQuery = mysqli_query($con, "SELECT studentName FROM students WHERE StudentRegno='$studentRegNo'");
    $studentData = mysqli_fetch_assoc($studentQuery);
    $studentName = $studentData['studentName'];

    // Get courses student already enrolled in
    $enrolledCourses = [];
    $enrollQuery = mysqli_query($con, "
        SELECT course FROM courseenrolls
        WHERE studentRegno = '$studentRegNo'
    ");
    while ($row = mysqli_fetch_assoc($enrollQuery)) {
        $enrolledCourses[] = $row['course'];
    }
    $enrolledCourseIds = implode(",", $enrolledCourses);
    if (empty($enrolledCourseIds)) $enrolledCourseIds = "0"; // No enrollments yet

    // Recommend courses = all courses student has NOT yet enrolled in
    $recommendQuery = mysqli_query($con, "
        SELECT id, courseName, department, semester
        FROM course
        WHERE id NOT IN ($enrolledCourseIds)
    ");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Course Recommendations</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2>Recommended Courses for <?php echo htmlentities($studentName); ?></h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Course Name</th>
                <th>Department</th>
                <th>Semester</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        if (mysqli_num_rows($recommendQuery) > 0) {
            while ($course = mysqli_fetch_assoc($recommendQuery)) {
                echo "<tr>
                    <td>".htmlentities($course['courseName'])."</td>
                    <td>".htmlentities($course['department'])."</td>
                    <td>".htmlentities($course['semester'])."</td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='3' class='text-center'>No recommendations available at this time.</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>
</body>
</html>
