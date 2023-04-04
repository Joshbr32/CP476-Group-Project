<?php
include "db_connect.php";
include "db_functions.php";

$conn = connect_to_database();

$selected_student_ids = $_SESSION["selectedStudentIds"] ?? [];
$student_courses = fetch_student_courses($conn, $selected_student_ids);

function calculate_final_grade($test1, $test2, $test3, $final_exam)
{
    return $test1 * 0.2 + $test2 * 0.2 + $test3 * 0.2 + $final_exam * 0.4;
}

if (isset($_POST["upload_to_database"])) {
    create_grade_table($conn, $student_courses);
    echo "<script>alert('Grades uploaded to database successfully');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<link href="style.css" rel="stylesheet">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Display Final Grades</title>
</head>
<body class="body">
  <h1 style="text-align: center;">Final Grades | Grade Table</h1>
  <table class="student_table" border="1">
    <tr>
      <th style="text-align: left;">Student ID</th>
      <th style="text-align: left;">Student Name</th>
      <th style="text-align: left;">Course Code</th>
      <th style="text-align: left;">Final Grade</th>
    </tr>
    <?php foreach ($student_courses as $student_course) {
        $student_id = $student_course["student_id"];
        $student_name = $student_course["student_name"];
        $rowspan = count($student_course["courses"]);
        $first_row = true;

        foreach ($student_course["courses"] as $course) {
            $final_grade = calculate_final_grade(
                $course["Test_1"],
                $course["Test_2"],
                $course["Test_3"],
                $course["Final_Exam"]
            );
            if ($first_row) {
                echo "<td rowspan='{$rowspan}'>" . $student_id . "</td>";
                echo "<td rowspan='{$rowspan}'>" . $student_name . "</td>";
                $first_row = false;
            }
            echo "<td>" . $course["Course_Code"] . "</td>";
            echo "<td>" . number_format($final_grade, 1) . "</td>";
            echo "</tr>";
        }
    } ?>
  </table>
  <br>
  <div style="text-align: center;">
    <button class="table_buttons" onclick="window.location.href='display_student_courses.php'">Back</button>
    <form method="POST" style="display: inline;">
      <input class="table_buttons" type="submit" name="upload_to_database" value="Upload to Database">
    </form>
  </div>
</body>
</html>
