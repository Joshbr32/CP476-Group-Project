<?php
include 'db_connect.php';
include 'db_functions.php';

$conn = connect_to_database();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_changes'])) {
    update_courses($conn, $_POST['courses']);
}

$selected_student_ids = $_SESSION['selectedStudentIds'] ?? [];
$student_courses = fetch_student_courses($conn, $selected_student_ids);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Student Courses</title>
</head>
<body>
    <h1>Student Courses</h1>
    <form id="updateForm" method="POST" onsubmit="return validateForm()">
        <table border="1">
            <tr>
                <th>Student ID</th>
                <th>Course Code</th>
                <th>Test 1</th>
                <th>Test 2</th>
                <th>Test 3</th>
                <th>Final Exam</th>
            </tr>
            <?php
            foreach ($student_courses as $student_course) {
                $student_id = $student_course['student_id'];
                $first_course = true;
                $course_count = count($student_course['courses']);
                foreach ($student_course['courses'] as $course) {
                    echo "<tr>";
                    if ($first_course) {
                        echo "<td rowspan='$course_count'>" . $student_id . "</td>";
                        $first_course = false;
                    }
                    echo "<td>" . $course['course_code'] . "</td>";
                    echo "<td><input type='number' min='0' max='100' name='courses[{$student_id}][{$course['course_code']}][test_1]' value='{$course['test_1']}' /></td>";
                    echo "<td><input type='number' min='0' max='100' name='courses[{$student_id}][{$course['course_code']}][test_2]' value='{$course['test_2']}' /></td>";
                    echo "<td><input type='number' min='0' max='100' name='courses[{$student_id}][{$course['course_code']}][test_3]' value='{$course['test_3']}' /></td>";
                    echo "<td><input type='number' min='0' max='100' name='courses[{$student_id}][{$course['course_code']}][final_exam]' value='{$course['final_exam']}' /></td>";
                    echo "</tr>";
                }
            }
            ?>
        </table>
        <br>
        <input type="submit" name="save_changes" value="Save Changes">
    </form>
    <br>
    <button onclick="window.location.href='students_list.php'">Back</button>
    <button onclick="window.location.href='display_final_grades.php'">Calculate grades</button>

    <script>
    function validateForm() {
        let inputs = document.querySelectorAll('input[type="number"]');
        for (let input of inputs) {
            let value = parseFloat(input.value);
            if (value < 0 || value > 100 || !Number.isInteger(value)) {
                alert("All values must be whole numbers between 0 and 100.");
                return false;
            }
        }
        return true;
    }
</script>
</body>
</html>
