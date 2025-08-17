<?php
function getCourseRecommendations($con, $studentRegNo) {
    // Get student info
    $studentQuery = mysqli_query($con, "SELECT * FROM students WHERE StudentRegNo='$studentRegNo'");
    $student = mysqli_fetch_assoc($studentQuery);
    if (!$student) return ['content_based' => [], 'collaborative' => []];

    // Use the actual PK from your students table
    $student_id = $student['StudentRegNo'];   
    $department = $student['Department'];     // check exact column name (maybe DeptId?)
    $semester = $student['Semester'];         // check exact column name

    // --- Content-Based Filtering ---
    $content_based = mysqli_query($con, "
        SELECT * FROM course 
        WHERE Department='$department' AND Semester >= '$semester'
    ");

    // --- Collaborative Filtering ---
    $enrolled = mysqli_query($con, "SELECT course_id FROM courseenrolls WHERE studentRegNo='$student_id'");
    $enrolled_courses = [];
    while($row = mysqli_fetch_assoc($enrolled)){
        $enrolled_courses[] = $row['course_id'];
    }

    $cf_courses = [];
    if (count($enrolled_courses) > 0) {
        $enrolled_list = implode(",", $enrolled_courses);

        // Find similar students
        $similar_students = mysqli_query($con, "
            SELECT DISTINCT studentRegNo FROM courseenrolls 
            WHERE course_id IN ($enrolled_list) AND studentRegNo != '$student_id'
        ");

        // Get their courses
        while($s = mysqli_fetch_assoc($similar_students)){
            $sid = $s['studentRegNo'];
            $result = mysqli_query($con, "
                SELECT course_id FROM courseenrolls 
                WHERE studentRegNo='$sid' AND course_id NOT IN ($enrolled_list)
            ");
            while($c = mysqli_fetch_assoc($result)){
                $cf_courses[] = $c['course_id'];
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
