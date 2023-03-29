<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $names = $_POST["names"];

    // Connect to the database
    $servername = "localhost";
    $dbname = "CP476";
    $username = "cp476";
    $password = "4v8b3gVScQpwm4CV";


    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Clean and split the input names
    $names = explode(",", $names);
    foreach ($names as &$name) {
        $name = trim($conn->real_escape_string($name));
    }

    // Prepare the SQL query to get student_id
    $sql = "SELECT student_id, student_name FROM `Name table` WHERE student_name IN ('" . implode("','", $names) . "')";
    $result = $conn->query($sql);

    // Fetch student_id and query the Course Table
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $student_id = $row["student_id"];
            $student_name = $row["student_name"];

            echo "<h2>Courses for $student_name (ID: $student_id)</h2>";

            // Prepare and execute the SQL query for the Course Table
            $course_sql = "SELECT * FROM `Course Table` WHERE student_id = $student_id";
            $course_result = $conn->query($course_sql);

            // Display the courses
            if ($course_result->num_rows > 0) {
                echo "<ul>";
                while ($course_row = $course_result->fetch_assoc()) {
                    echo "<li>" . $course_row["course_name"] . " (ID: " . $course_row["course_id"] . ")</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>No courses found for this student.</p>";
            }
        }
    } else {
        echo "<p>No students found with the given names.</p>";
    }

    $conn->close();
}
?>
