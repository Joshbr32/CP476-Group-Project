<?php
require __DIR__ . "/db/db_connection.php";

// Dynamically displays a table records of one user or all users
function displayTable()
{
  $conn = connectToDB("StudentGradesDB");

  // Check if the search bar was used
  if (
    isset($_POST["submit"]) &&
    array_key_exists("search", $_POST) &&
    $_POST["search"] !== ""
  ) {
    $id = $_POST["search"];
  } else {
    $id = null;
  }

  $sql = getSQLForTableData($id);

  $stmt = mysqli_prepare($conn, $sql);
  if ($id !== null) {
    mysqli_stmt_bind_param($stmt, "i", $id);
  }
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);


  // Check if there are any rows in the result set
  if (mysqli_num_rows($result) > 0) {
    // Build the table
    echo "<table>";
    echo "<tr><th>Student Name</th><th>Student ID</th><th>Course Code</th><th>Test 1</th><th>Test 2</th><th>Test 3</th><th>Final Exam</th><th>Final Grade</th><th></th></tr>";

    // Loop through each row in the result set and add it to the table
    while ($row = mysqli_fetch_assoc($result)) {
      echo "<tr>";
      echo "<form class='handleUpdate' action='" .
        htmlspecialchars($_SERVER["PHP_SELF"]) .
        "' method='POST'>";
      echo "<td>" . $row["Student_Name"] . "</td>";
      echo "<td>" . $row["Student_ID"] . "</td>";
      echo "<td>" . $row["Course_Code"] . "</td>";
      echo "<td><input type='number' step='0.1' name='Test_1' max='100' min='0' value='" .
        $row["Test_1"] .
        "' ></td>";
      echo "<td><input type='number' step='0.1' name='Test_2' max='100' min='0' value='" .
        $row["Test_2"] .
        "' ></td>";
      echo "<td><input type='number' step='0.1' name='Test_3' max='100' min='0' value='" .
        $row["Test_3"] .
        "' ></td>";
      echo "<td><input type='number' step='0.1' name='Final_Exam' max='100' min='0' value='" .
        $row["Final_Exam"] .
        "' ></td>";
      echo "<td>" .
        ($row["Test_1"] / 100) * 20 +
        ($row["Test_2"] / 100) * 20 +
        ($row["Test_3"] / 100) * 20 +
        ($row["Final_Exam"] / 100) * 40 .
        "</td>";
      echo "<td><input type='submit' name='Save' value='Save'></td>";
      echo "<input type='hidden' value='" .
        $row["Student_ID"] .
        "' name='Student_ID'>";
      echo "<input type='hidden' value='" .
        $row["Course_Code"] .
        "' name='Course_Code'>";
      echo "</form>";
      echo "</tr>";
    }

    // End the table
    echo "</table>";
  } else {
    echo "No data found in table.";
  }

  $conn->close();
}

// Function to generate query statement for table data
// If id is set then we will be adding a where clause to select only that students grades
function getSQLForTableData($id = null)
{
  $sql = "
      SELECT Students.Student_Name, Students.Student_ID, Courses.Course_Code, 
      Courses.Test_1, Courses.Test_2, Courses.Test_3, Courses.Final_Exam
      FROM StudentGradesDB.Course_Table AS Courses
      INNER JOIN StudentGradesDB.Name_Table AS Students
      ON Students.Student_ID=Courses.Student_ID 
      ";

  if ($id !== null) {
    $sql .= "
       WHERE Students.Student_ID=?
      ";
  }

  return $sql;
}

// Check if values were submitted and Send them to db
if (array_key_exists("Save", $_POST) && isset($_POST["Save"])) {
  sendGradeUpdatesToDB();
}

function sendGradeUpdatesToDB()
{
  $conn = connectToDB("StudentGradesDB");

  // Data pre-validated through text field restrictions
  $test_1 = $_POST["Test_1"];
  $test_2 = $_POST["Test_2"];
  $test_3 = $_POST["Test_3"];
  $final_exam = $_POST["Final_Exam"];
  $student_id = $_POST["Student_ID"];
  $course_code = $_POST["Course_Code"];

  // Prepare the SQL statement with placeholders
  $sql =
    "
  UPDATE StudentGradesDB.Course_Table AS Courses
  SET Courses.Test_1=?, Courses.Test_2=?, Courses.Test_3=?, Courses.Final_Exam=?
  WHERE Courses.Course_Code=? AND Courses.Student_ID=?
  ";

  // Prepare the statement
  $stmt = mysqli_prepare($conn, $sql);

  // Bind parameters to the prepared statement
  mysqli_stmt_bind_param($stmt, "ddddsi", $test_1, $test_2, $test_3, $final_exam, $course_code, $student_id);

  // Execute the statement
  $result = mysqli_stmt_execute($stmt);

  // Close the connection
  $conn->close();

  return $result;
}
?>

<!doctype html>
<html>

<head>
  <title>Grade Editor</title>
  <link rel="stylesheet" href="/Project//CP476-Internet-Computing-Group-Project//public//css//home.css">
</head>

<body>
  <h1 class="heading"> Grade Editor </h1>

  <!-- Search bar -->
  <form class="handleUserSearch" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="search">Search:</label>
    <input type="text" name="search" id="search" pattern="\d{9}" placeholder="Enter a Student ID">
    <input type="submit" name="submit" value="Submit">
  </form>

  <!-- Table -->
  <?php displayTable(); ?>
</body>

</html>