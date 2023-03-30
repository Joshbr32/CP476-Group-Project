<?php
include 'db_connect.php';
include 'db_functions.php';

$conn = connect_to_database();
$student_names = fetch_student_names($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $_SESSION['selectedStudentIds'] = $_POST['students'];
  header('Location: display_student_courses.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Students List</title>
  <script>
  function validateForm() {
    const form = document.getElementById('studentForm');
    const checkboxes = form.querySelectorAll("input[type='checkbox']");
    const checked = Array.from(checkboxes).some(checkbox => checkbox.checked);

    if (!checked) {
      alert('Please select at least 1 student');
      return false;
    }
    return true;
  }

  function selectAll() {
    const checkboxes = document.querySelectorAll("input[type='checkbox']");
    for (let checkbox of checkboxes) {
      checkbox.checked = true;
    }
  }

  function selectNone() {
    const checkboxes = document.querySelectorAll("input[type='checkbox']");
    for (let checkbox of checkboxes) {
      checkbox.checked = false;
    }
  }
  </script>
</head>
<body>
  <h1>Students List</h1>
  <form id="studentForm" method="POST" onsubmit="return validateForm();">
    <?php
    foreach ($student_names as $student_id => $student_name) {
      echo "<input type='checkbox' name='students[]' value='$student_id'> $student_name<br>";
    }
    ?>
    <input type="submit" value="Submit">
  </form>
  <br>
  <button onclick="selectAll()">Select All</button>
  <button onclick="selectNone()">Select None</button>
</body>
</html>
