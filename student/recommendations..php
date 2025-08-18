<?php
function getCourseRecommendations($con, $studentRegNo) {
    // Get student info
    $studentQuery = mysqli_query($con, "SELECT * FROM students WHERE StudentRegNo='$studentRegNo'");
    $student = mysqli_fetch_assoc($studentQuery);
    if (!$student) return ['content_based' => [], 'collaborative' => []];

    $student_id = $student['StudentRegno'];   // match DB col name exactly
    $department = $student['department'];     
    $semester   = $student['semester'];       

    // --- Content-Based Filtering ---
    $content_based = mysqli_query($con, "
        SELECT * FROM course 
        WHERE department='$department' AND semester >= '$semester'
    ");

    // --- Collaborative Filtering ---
    $enrolled = mysqli_query($con, "SELECT course FROM courseenrolls WHERE studentRegno='$student_id'");
    $enrolled_courses = [];
    while($row = mysqli_fetch_assoc($enrolled)){
        $enrolled_courses[] = $row['course'];
    }

    $cf_courses = [];
    if (count($enrolled_courses) > 0) {
        $enrolled_list = implode(",", $enrolled_courses);

        // Find similar students
        $similar_students = mysqli_query($con, "
            SELECT DISTINCT studentRegno FROM courseenrolls 
            WHERE course IN ($enrolled_list) AND studentRegno != '$student_id'
        ");

        // Get their courses
        while($s = mysqli_fetch_assoc($similar_students)){
            $sid = $s['studentRegno'];
            $result = mysqli_query($con, "
                SELECT course FROM courseenrolls 
                WHERE studentRegno='$sid' AND course NOT IN ($enrolled_list)
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

    // helper to fetch arrays
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
?>
