<?php
include "db_connect.php";
include "db_functions.php";

$conn = connect_to_database();
$student_names = fetch_student_names($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION["selectedStudentIds"] = $_POST["students"];
    header("Location: display_student_courses.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<link href="style.css" rel="stylesheet">
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
<body class="body">
  <h1 style="text-align: center;">Students List</h1>
  <form id="studentForm" method="POST" onsubmit="return validateForm();">
    <table class="student_table" border="1">
      <tr>
        <th style="text-align: left;">Student ID</th>
        <th style="text-align: left;">Student Name</th>
        <th style="text-align: center;">Select</th>
      </tr>
      <?php foreach ($student_names as $student_id => $student_name) {
          echo "<tr>";
          echo "<td>$student_id</td>";
          echo "<td>$student_name</td>";
          echo "<td><input type='checkbox' name='students[]' value='$student_id'></td>";
          echo "</tr>";
      } ?>
    </table>
    <div style="text-align: center;">
      <input class="upload_button" type="submit" value="Submit">
    </div>
  </form>
  <br>
  <div style="text-align: center;">
    <button class="upload_button" onclick="selectAll()">Select All</button>
    <button class="upload_button" onclick="selectNone()">Select None</button>
  </div>
</body>
</html>
