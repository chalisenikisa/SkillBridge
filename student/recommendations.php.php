<?php
session_start();
include('includes/config.php');

if(strlen($_SESSION['login'])==0) {
    header('location:index.php');
    exit();
} else {
    $studentRegNo = $_SESSION['login'];

    // Get student info
    $studentQuery = mysqli_query($con, "SELECT department, semester FROM students WHERE StudentRegNo='$studentRegNo'");
    $studentData = mysqli_fetch_assoc($studentQuery);
    $department = $studentData['department'];
    $semester = $studentData['semester'];

    // Get courses student already enrolled in
    $enrolledCourses = [];
    $enrollQuery = mysqli_query($con, "
        SELECT courseId FROM enrollments 
        WHERE studentId = (SELECT id FROM students WHERE StudentRegNo='$studentRegNo')
    ");
    while($row = mysqli_fetch_assoc($enrollQuery)) {
        $enrolledCourses[] = $row['courseId'];
    }
    $enrolledCourseIds = implode(",", $enrolledCourses);
    if(empty($enrolledCourseIds)) $enrolledCourseIds = "0"; // No enrollments yet

    // Fetch recommended courses (same dept & semester but not enrolled yet)
    $recommendQuery = mysqli_query($con, "
        SELECT * FROM courses 
        WHERE department='$department' 
        AND semester='$semester' 
        AND courseId NOT IN ($enrolledCourseIds)
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
    <h2>Recommended Courses for You</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Course Name</th>
                <th>Department</th>
                <th>Semester</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        if(mysqli_num_rows($recommendQuery) > 0) {
            while($course = mysqli_fetch_assoc($recommendQuery)) {
                echo "<tr>
                    <td>{$course['courseName']}</td>
                    <td>{$course['department']}</td>
                    <td>{$course['semester']}</td>
                    <td>{$course['description']}</td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='4' class='text-center'>No recommendations available at this time.</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>
</body>
</html>
