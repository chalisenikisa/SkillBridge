<?php
function getCourseRecommendations($con, $studentRegNo) {
    // Get student info
    $studentQuery = mysqli_query($con, "SELECT * FROM students WHERE StudentRegNo='$studentRegNo'");
    $student = mysqli_fetch_assoc($studentQuery);
    $student_id = $student['id'];   // assuming 'id' is PK
    $department = $student['department'];
    $semester = $student['semester'];

    // --- Content-Based Filtering ---
    $content_based = mysqli_query($con, "
        SELECT * FROM courses 
        WHERE department='$department' AND semester >= '$semester'
    ");

    // --- Collaborative Filtering ---
    // 1. Courses the current student already enrolled in
    $enrolled = mysqli_query($con, "SELECT course_id FROM enrollments WHERE student_id='$student_id'");
    $enrolled_courses = [];
    while($row = mysqli_fetch_assoc($enrolled)){
        $enrolled_courses[] = $row['course_id'];
    }
    $enrolled_list = implode(",", $enrolled_courses);
    if(empty($enrolled_list)) $enrolled_list = "0";

    // 2. Find similar students
    $similar_students = mysqli_query($con, "
        SELECT DISTINCT student_id FROM enrollments 
        WHERE course_id IN ($enrolled_list) AND student_id != '$student_id'
    ");

    // 3. Get their courses
    $cf_courses = [];
    while($s = mysqli_fetch_assoc($similar_students)){
        $sid = $s['student_id'];
        $result = mysqli_query($con, "
            SELECT course_id FROM enrollments 
            WHERE student_id='$sid' AND course_id NOT IN ($enrolled_list)
        ");
        while($c = mysqli_fetch_assoc($result)){
            $cf_courses[] = $c['course_id'];
        }
    }
    $cf_courses = array_unique($cf_courses);
    $cf_list = implode(",", $cf_courses);
    if(empty($cf_list)) $cf_list = "0";

    $collaborative = mysqli_query($con, "SELECT * FROM courses WHERE id IN ($cf_list)");

    return [
        'content_based' => $content_based,
        'collaborative' => $collaborative
    ];
}
?>

