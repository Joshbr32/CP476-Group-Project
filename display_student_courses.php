<?php
include 'db_connect.php';
include 'db_functions.php';

$conn = connect_to_database();

if (isset($_GET['selected_students'])) {
    $selected_student_ids = array_map('intval', explode(',', $_GET['selected_students']));
} else {
    $selected_student_ids = json_decode($_GET['selectedStudentIds']);
}

$student_courses = fetch_student_courses($conn, $selected_student_ids);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Student Courses</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            const selectedStudentIds = JSON.parse(localStorage.getItem('selectedStudentIds'));
            fetch_student_courses(selectedStudentIds);
        });

        function fetch_student_courses(studentIds) {
            $.ajax({
                url: 'fetch_student_courses.php',
                type: 'POST',
                data: {
                    studentIds: studentIds
                },
                success: function(response) {
                    $("#courses").html(response);
                }
            });
        }
    </script>
</head>
<body>
    <h1>Student Courses</h1>
    <div id="courses"></div>
    <button onclick="window.location.href='students_list.php'">Back</button>
</body>
</html>
