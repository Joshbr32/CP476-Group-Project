<?php
include 'db_connect.php';
include 'db_functions.php';

$conn = connect_to_database();

$selected_student_ids = $_SESSION['selectedStudentIds'] ?? [];
$student_courses = fetch_student_courses($conn, $selected_student_ids);

function calculate_final_grade($test1, $test2, $test3, $final_exam) {
  return ($test1 * 0.2) + ($test2 * 0.2) + ($test3 * 0.2) + ($final_exam * 0.4);
}

if (isset($_POST['upload_to_database'])) {
  create_grade_table($conn, $student_courses);
  echo "<script>alert('Grades uploaded to database successfully');</script>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Display Final Grades</title>
</head>
<body>
  <h1>Final Grades</h1>
  <table border="1">
    <tr>
      <th>Student ID</th>
      <th>Student Name</th>
      <th>Course Code</th>
      <th>Final Grade</th>
    </tr>
    <?php
    foreach ($student_courses as $student_course) {
      $student_id = $student_course['student_id'];
      $student_name = $student_course['student_name'];
      $rowspan = count($student_course['courses']);
      $first_row = true;

      foreach ($student_course['courses'] as $course) {
        $final_grade = calculate_final_grade($course['test_1'], $course['test_2'], $course['test_3'], $course['final_exam']);
        if ($first_row) {
          echo "<td rowspan='{$rowspan}'>" . $student_id . "</td>";
          echo "<td rowspan='{$rowspan}'>" . $student_name . "</td>";
          $first_row = false;
        }
        echo "<td>" . $course['course_code'] . "</td>";
        echo "<td>" . number_format($final_grade, 1) . "</td>";
        echo "</tr>";
      }
    }
    ?>
  </table>
  <br>
  <button onclick="window.location.href='display_student_courses.php'">Back</button>
  <form method="POST" style="display: inline;">
    <input type="submit" name="upload_to_database" value="Upload to Database">
  </form>
</body>
</html>
