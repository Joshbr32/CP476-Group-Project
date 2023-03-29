<?php
function fetch_student_names($conn, $selected_student_ids = null) {
    if ($selected_student_ids === null || count($selected_student_ids) === 0) {
        $sql = "SELECT student_id, student_name FROM `Name Table`";
        $result = $conn->query($sql);
    } else {
        $sql = "SELECT student_id, student_name FROM `Name Table` WHERE student_id IN (" . str_repeat('?,', count($selected_student_ids) - 1) . "?)";
        $stmt = $conn->prepare($sql);

        $types = str_repeat("i", count($selected_student_ids));
        $stmt->bind_param($types, ...$selected_student_ids);

        $stmt->execute();
        $result = $stmt->get_result();
    }

    $data = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[$row['student_id']] = $row['student_name'];
        }
    }

    return $data;
}

function fetch_courses_by_student_id($conn, $student_id) {
    $sql = "SELECT * FROM `Course Table` WHERE student_id = ?";
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

function fetch_student_courses($conn, $selected_student_ids) {
    $student_names = fetch_student_names($conn, $selected_student_ids);
    $data = array();

    foreach ($student_names as $student_id => $student_name) {
        $courses = fetch_courses_by_student_id($conn, $student_id);
        foreach ($courses as $course) {
            $course_data = array(
                'student_id' => $student_id,
                'student_name' => $student_name,
            );
            $data[] = array_merge($course_data, $course);
        }
    }

    return $data;
}
?>
