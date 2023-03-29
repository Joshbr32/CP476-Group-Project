<?php
function fetch_student_names($conn) {
    $sql = "SELECT student_id, student_name FROM `Name Table`";
    $result = $conn->query($sql);
    $data = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[$row['student_id']] = $row['student_name'];
        }
    }
    return $data;
}

function fetch_courses_by_student_id($conn, $student_id) {
    $sql = "SELECT * FROM `CourseTable` WHERE student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $courses = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $courses[] = $row;
        }
    }

    return $courses;
}

function fetch_student_courses($conn) {
    $student_names = fetch_student_names($conn);
    $data = array();

    foreach ($student_names as $student_id => $student_name) {
        $courses = fetch_courses_by_student_id($conn, $student_id);
        $data[] = array(
            'student_id' => $student_id,
            'student_name' => $student_name,
            'courses' => $courses
        );
    }

    return $data;
}
?>
