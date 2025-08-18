<?php
// recommendations.php
function showRecommendations($con, $studentRegNo) {
    // --- Fetch Student Info ---
    $studentQuery = mysqli_query($con, "SELECT * FROM students WHERE StudentRegNo='$studentRegNo'");
    $student = mysqli_fetch_assoc($studentQuery);

    if (!$student) {
        echo "<p>No student found.</p>";
        return;
    }

    $department = $student['Department'];
    $semester   = $student['Semester'];

    // --- Content-Based Filtering ---
    $content_sql = "
        SELECT * FROM course 
        WHERE Department='$department' 
          AND Semester >= '$semester'
          AND id NOT IN (SELECT course_id FROM courseenrolls WHERE studentRegNo='$studentRegNo')
    ";
    $content_result = mysqli_query($con, $content_sql);

    // --- Collaborative Filtering ---
    $enrolled_result = mysqli_query($con, "SELECT course_id FROM courseenrolls WHERE studentRegNo='$studentRegNo'");
    $enrolled_courses = [];
    while ($row = mysqli_fetch_assoc($enrolled_result)) {
        $enrolled_courses[] = $row['course_id'];
    }

    $cf_courses = [];
    if (count($enrolled_courses) > 0) {
        $enrolled_list = implode(",", $enrolled_courses);

        // Find similar students
        $similar_students = mysqli_query($con, "
            SELECT DISTINCT studentRegNo FROM courseenrolls
            WHERE course_id IN ($enrolled_list) AND studentRegNo != '$studentRegNo'
        ");

        // Get their courses
        while ($s = mysqli_fetch_assoc($similar_students)) {
            $sid = $s['studentRegNo'];
            $result = mysqli_query($con, "
                SELECT course_id FROM courseenrolls
                WHERE studentRegNo='$sid' AND course_id NOT IN ($enrolled_list)
            ");
            while ($c = mysqli_fetch_assoc($result)) {
                $cf_courses[] = $c['course_id'];
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
            echo "<li>" . htmlentities($row['courseName']) . " (Sem " . $row['Semester'] . ")</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No new content-based recommendations.</p>";
    }

    echo "<h4>👥 Collaborative Recommendations</h4>";
    if (count($collaborative) > 0) {
        echo "<ul>";
        foreach ($collaborative as $row) {
            echo "<li>" . htmlentities($row['courseName']) . " (Sem " . $row['Semester'] . ")</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No collaborative recommendations yet.</p>";
    }
}
?>
