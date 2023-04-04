<?php
include "db_connect.php";
include "db_functions.php";

$conn = connect_to_database();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["save_changes"])) {
    update_courses($conn, $_POST["courses"]);
}

$selected_student_ids = $_SESSION["selectedStudentIds"] ?? [];
$student_courses = fetch_student_courses($conn, $selected_student_ids);
?>

<!DOCTYPE html>
<html lang="en">
<link href="style.css" rel="stylesheet">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Student Courses</title>
</head>
<body class="body">
    <h1 style="text-align: center;">Student Courses | Course Table</h1>
    <form id="updateForm" method="POST" onsubmit="return validateForm()">
        <table class="student_table" border="1">
            <tr>
                <th style="text-align: left;">Student ID</th>
                <th style="text-align: left;">Course Code</th>
                <th>Test 1</th>
                <th>Test 2</th>
                <th>Test 3</th>
                <th>Final Exam</th>
            </tr>
            <?php foreach ($student_courses as $student_course) {
                $student_id = $student_course["student_id"];
                $first_course = true;
                $course_count = count($student_course["courses"]);
                foreach ($student_course["courses"] as $course) {
                    echo "<tr>";
                    if ($first_course) {
                        echo "<td rowspan='$course_count'>" .
                            $student_id .
                            "</td>";
                        $first_course = false;
                    }
                    echo "<td>" . $course["Course_Code"] . "</td>";
                    echo "<td><input type='number' min='0' max='100' name='courses[{$student_id}][{$course['Course_Code']}][Test_1]' value='{$course['Test_1']}' /></td>";
                    echo "<td><input type='number' min='0' max='100' name='courses[{$student_id}][{$course['Course_Code']}][Test_2]' value='{$course['Test_2']}' /></td>";
                    echo "<td><input type='number' min='0' max='100' name='courses[{$student_id}][{$course['Course_Code']}][Test_3]' value='{$course['Test_3']}' /></td>";
                    echo "<td><input type='number' min='0' max='100' name='courses[{$student_id}][{$course['Course_Code']}][Final_Exam]' value='{$course['Final_Exam']}' /></td>";
                    echo "</tr>";
                }
            } ?>
        </table>
        <br>
        <div style="text-align: center;">
            <input class="table_buttons" type="submit" name="save_changes" value="Save Changes">
        </div>
    </form>
    <br>
    <div style="text-align: center;">
        <button class="table_buttons" onclick="window.location.href='students_list.php'">Back</button>
        <button class="table_buttons" onclick="window.location.href='display_final_grades.php'">Calculate grades</button>
    </div>
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
