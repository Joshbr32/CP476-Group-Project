<?php
function fetch_student_names($conn, $selected_student_ids = null) {
    if ($selected_student_ids === null) {
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
        $data[] = array(
            'student_id' => $student_id,
            'student_name' => $student_name,
            'courses' => $courses
        );
    }

    return $data;
}

function update_courses($conn, $courses_data) {
foreach ($courses_data as $student_id => $courses) {
    foreach ($courses as $course_code => $course_values) {
        $sql = "UPDATE `Course Table` SET test_1 = ?, test_2 = ?, test_3 = ?, final_exam = ? WHERE student_id = ? AND course_code = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ddddis", $course_values['test_1'], $course_values['test_2'], $course_values['test_3'], $course_values['final_exam'], $student_id, $course_code);
        $stmt->execute();
    }
}
}
?>
