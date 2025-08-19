<?php
session_start();
include("includes/config.php");



function showRecommendations($con, $studentRegNo)
{
    $studentQuery = mysqli_query($con, "SELECT * FROM students WHERE StudentRegno='$studentRegNo'");
    $student = mysqli_fetch_assoc($studentQuery);

    if (!$student) {
        echo "<p>No student found.</p>";
        return;
    }

    $semester = $student['semester'];
    $department = $student['department'];

    // --- Content-Based Filtering ---
    // Join courseenrolls to exclude already enrolled courses
    $content_sql = "
        SELECT * FROM course 
        WHERE id NOT IN (
            SELECT course FROM courseenrolls WHERE studentRegno='$studentRegNo'
        )
    ";
    $content_result = mysqli_query($con, $content_sql);

    // --- Collaborative Filtering ---
    $enrolled_result = mysqli_query($con, "SELECT course FROM courseenrolls WHERE studentRegno='$studentRegNo'");
    $enrolled_courses = [];
    while ($row = mysqli_fetch_assoc($enrolled_result)) {
        $enrolled_courses[] = $row['course'];
    }

    $cf_courses = [];
    if (count($enrolled_courses) > 0) {
        $enrolled_list = implode(",", $enrolled_courses);

        // Find similar students
        $similar_students = mysqli_query($con, "
            SELECT DISTINCT studentRegno FROM courseenrolls
            WHERE course IN ($enrolled_list) AND studentRegno != '$studentRegNo'
        ");

        // Get their courses
        while ($s = mysqli_fetch_assoc($similar_students)) {
            $sid = $s['studentRegno'];
            $result = mysqli_query($con, "
                SELECT course FROM courseenrolls
                WHERE studentRegno='$sid' AND course NOT IN ($enrolled_list)
            ");
            while ($c = mysqli_fetch_assoc($result)) {
                $cf_courses[] = $c['course'];
            }
        }
    }

    $cf_courses = array_unique($cf_courses);
    $collaborative = [];
    if (count($cf_courses) > 0) {
        $cf_list = implode(",", $cf_courses);
        $cf_result = mysqli_query($con, "SELECT * FROM course WHERE id IN ($cf_list)");
        while ($row = mysqli_fetch_assoc($cf_result)) {
            $collaborative[] = $row;
        }
    }

    // --- Display Section ---
    echo "<h3>🎯 Recommended Courses for You</h3>";

    echo "<h4>📌 Content-Based Recommendations</h4>";
    if (mysqli_num_rows($content_result) > 0) {
        echo "<ul>";
        while ($row = mysqli_fetch_assoc($content_result)) {
            echo "<li>" . htmlentities($row['courseName']) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No new content-based recommendations.</p>";
    }

    echo "<h4>👥 Collaborative Recommendations</h4>";
    if (count($collaborative) > 0) {
        echo "<ul>";
        foreach ($collaborative as $row) {
            echo "<li>" . htmlentities($row['courseName']) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No collaborative recommendations yet.</p>";
    }
}

showRecommendations($con, $_SESSION['login']);