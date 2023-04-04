<?php
include "db_connect.php";
include "db_functions.php";

$conn = connect_to_database();

if (!isset($_SESSION["tables_dropped"])) {
    drop_tables($conn);
    $_SESSION["tables_dropped"] = true;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["upload_name_table"]) && isset($_FILES["name_txt"])) {
        $file = $_FILES["name_txt"]["tmp_name"];
        $upload_name_table_result = upload_name_table($conn, $file);
        if ($upload_name_table_result) {
            $_SESSION["upload_name_success"] = true;
        } else {
            echo "Error: Failed to upload Name Table.<br>";
        }
    } elseif (
        isset($_POST["upload_course_table"]) &&
        isset($_FILES["course_txt"])
    ) {
        if (isset($_SESSION["upload_name_success"])) {
            $file = $_FILES["course_txt"]["tmp_name"];
            upload_course_table($conn, $file);
            $_SESSION["upload_course_success"] = true;
        } else {
            $_SESSION["upload_name_first"] = true;
        }
    }
} else {
    if (isset($_SESSION["upload_name_success"])) {
        unset($_SESSION["upload_name_success"]);
    }
}

$upload_name_success = isset($_SESSION["upload_name_success"]);
$upload_course_success = isset($_SESSION["upload_course_success"]);
$upload_name_first = isset($_SESSION["upload_name_first"]);

if (
    isset($_SESSION["upload_name_success"]) &&
    isset($_SESSION["upload_course_success"])
) {
    unset($_SESSION["upload_name_success"]);
    unset($_SESSION["upload_course_success"]);
}
if (isset($_SESSION["upload_name_first"])) {
    unset($_SESSION["upload_name_first"]);
}
?>


<!DOCTYPE html>
<html lang="en">
<link href="style.css" rel="stylesheet">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Upload Data</title>
  <script>
  function checkNameFileUploaded() {
    <?php if (!$upload_name_success): ?>
    alert("Please upload the Name Table first.");
    return false;
    <?php else: ?>
    return true;
    <?php endif; ?>
  }
  function validateNameUpload() {
    if (document.querySelector('input[name="name_txt"]').files.length === 0) {
      alert("Please select a file to upload for the Name Table.");
      return false;
    }
    return true;
  }

  function validateCourseUpload() {
    if (document.querySelector('input[name="course_txt"]').files.length === 0) {
      alert("Please select a file to upload for the Course Table.");
      return false;
    }
    return true;
  }
  </script>
</head>
<body class="body">
  <h1 style="text-align: center;">Upload Data</h1>
<div class="upload_box">
  <h2>Upload Name Table</h2>
  <form action="upload_data.php" method="post" enctype="multipart/form-data" onsubmit="return validateNameUpload();">
    <input type="file" name="name_txt" accept=".txt">
    <input class="upload_button" type="submit" name="upload_name_table" value="Upload Name Table">
  </form>
  <?php if ($upload_name_success) {
      echo "<p>Name Table has been successfully uploaded.</p>";
  } ?>

  <h2>Upload Course Table</h2>
  <form action="upload_data.php" method="post" enctype="multipart/form-data" onsubmit="return checkNameFileUploaded() && validateCourseUpload();">
    <input type="file" name="course_txt" accept=".txt">
    <input class="upload_button" type="submit" name="upload_course_table" value="Upload Course Table">
  </form>
  <?php if ($upload_course_success) {
      echo "<p>Course Table has been successfully uploaded.</p>";
  } ?>

  <?php if ($upload_name_success && $upload_course_success): ?>
    <form style="text-align: center;" action="students_list.php" method="get">
      <input class="upload_button" type="submit" value="Go to Students List">
    </form>
  <?php endif; ?>
</div>
</body>
</html>
