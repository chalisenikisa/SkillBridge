<?php
// Enable error reporting (helpful during testing)
error_reporting(E_ALL);
ini_set('display_errors', 1);

function getCourseRecommendations($con, $studentRegNo) {
    // Get student info
    $studentQuery = mysqli_query($con, "SELECT * FROM students WHERE StudentRegNo='$studentRegNo'");
    $student = mysqli_fetch_assoc($studentQuery);
    if (!$student) return ['content_based' => [], 'collaborative' => []];

    // Match your DB column names exactly!
    $student_id = $student['StudentRegNo'];
    $department = $student['department'];     
    $semester   = $student['semester'];       

    // --- Content-Based Filtering ---
    $content_based = mysqli_query($con, "
        SELECT * FROM course 
        WHERE department='$department' AND semester >= '$semester'
    ");

    // --- Collaborative Filtering ---
    $enrolled = mysqli_query($con, "SELECT course FROM courseenrolls WHERE studentRegNo='$student_id'");
    $enrolled_courses = [];
    while($row = mysqli_fetch_assoc($enrolled)){
        $enrolled_courses[] = $row['course'];
    }

    $cf_courses = [];
    if (count($enrolled_courses) > 0) {
        $enrolled_list = implode(",", $enrolled_courses);

        // Find similar students
        $similar_students = mysqli_query($con, "
            SELECT DISTINCT studentRegNo FROM courseenrolls 
            WHERE course IN ($enrolled_list) AND studentRegNo != '$student_id'
        ");

        // Get their courses
        while($s = mysqli_fetch_assoc($similar_students)){
            $sid = $s['studentRegNo'];
            $result = mysqli_query($con, "
                SELECT course FROM courseenrolls 
                WHERE studentRegNo='$sid' AND course NOT IN ($enrolled_list)
            ");
            while($c = mysqli_fetch_assoc($result)){
                $cf_courses[] = $c['course'];
            }
        }
    }

    $cf_courses = array_unique($cf_courses);
    if (count($cf_courses) > 0) {
        $cf_list = implode(",", $cf_courses);
        $collaborative = mysqli_query($con, "SELECT * FROM course WHERE id IN ($cf_list)");
    } else {
        $collaborative = false;
    }

    // Helper to fetch arrays
    function fetchAll($result) {
        $rows = [];
        if ($result) {
            while($row = mysqli_fetch_assoc($result)){
                $rows[] = $row;
            }
        }
        return $rows;
    }

    return [
        'content_based' => fetchAll($content_based),
        'collaborative' => fetchAll($collaborative)
    ];
}

// Display function
function showRecommendations($con, $studentRegNo) {
    $recs = getCourseRecommendations($con, $studentRegNo);

    echo "<h3>📘 Content-Based Recommendations</h3>";
    if (count($recs['content_based']) > 0) {
        echo "<ul>";
        foreach ($recs['content_based'] as $course) {
            echo "<li><b>{$course['coursename']}</b> (Semester {$course['semester']})</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No content-based recommendations available.</p>";
    }

    echo "<h3>👥 Collaborative Recommendations</h3>";
    if (count($recs['collaborative']) > 0) {
        echo "<ul>";
        foreach ($recs['collaborative'] as $course) {
            echo "<li><b>{$course['coursename']}</b> (Semester {$course['semester']})</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No collaborative recommendations available.</p>";
    }
}
?>
