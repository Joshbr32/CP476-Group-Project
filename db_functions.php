<?php
function fetch_student_names($conn, $selected_student_ids = null)
{
    if ($selected_student_ids === null) {
        $sql = "SELECT student_id, student_name FROM `Name Table`";
        $result = $conn->query($sql);
    } else {
        $sql =
            "SELECT student_id, student_name FROM `Name Table` WHERE student_id IN (" .
            str_repeat("?,", count($selected_student_ids) - 1) .
            "?)";
        $stmt = $conn->prepare($sql);

        $types = str_repeat("i", count($selected_student_ids));
        $stmt->bind_param($types, ...$selected_student_ids);

        $stmt->execute();
        $result = $stmt->get_result();
    }

    $data = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[$row["student_id"]] = $row["student_name"];
        }
    }

    return $data;
}

function fetch_courses_by_student_id($conn, $student_id)
{
    $sql = "SELECT * FROM `Course Table` WHERE student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $courses = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $courses[] = $row;
        }
    }

    return $courses;
}

function fetch_student_courses($conn, $selected_student_ids)
{
    $student_names = fetch_student_names($conn, $selected_student_ids);
    $data = [];

    foreach ($student_names as $student_id => $student_name) {
        $courses = fetch_courses_by_student_id($conn, $student_id);
        $data[] = [
            "student_id" => $student_id,
            "student_name" => $student_name,
            "courses" => $courses,
        ];
    }

    return $data;
}

function update_courses($conn, $courses_data)
{
    foreach ($courses_data as $student_id => $courses) {
        foreach ($courses as $course_code => $course_values) {
            $sql =
                "UPDATE `Course Table` SET test_1 = ?, test_2 = ?, test_3 = ?, final_exam = ? WHERE student_id = ? AND course_code = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param(
                "ddddis",
                $course_values["test_1"],
                $course_values["test_2"],
                $course_values["test_3"],
                $course_values["final_exam"],
                $student_id,
                $course_code
            );
            $stmt->execute();
        }
    }
}

function create_grade_table($conn, $student_courses)
{
    $query = "DROP TABLE IF EXISTS `Grade Table`;";
    $conn->query($query);

    $query = "CREATE TABLE `Grade Table` (
    `student_id` INT,
    `student_name` VARCHAR(255),
    `course_code` VARCHAR(255),
    `final_grade` DECIMAL(4,1)
  );";
    $conn->query($query);

    $stmt = $conn->prepare(
        "INSERT INTO `Grade Table` (student_id, student_name, course_code, final_grade) VALUES (?, ?, ?, ?);"
    );

    foreach ($student_courses as $student_course) {
        $student_id = $student_course["student_id"];
        $student_name = $student_course["student_name"];
        foreach ($student_course["courses"] as $course) {
            $final_grade =
                $course["Test_1"] * 0.2 +
                $course["Test_2"] * 0.2 +
                $course["Test_3"] * 0.2 +
                $course["Final_Exam"] * 0.4;
            $stmt->bind_param(
                "isss",
                $student_id,
                $student_name,
                $course["Course_Code"],
                $final_grade
            );
            $stmt->execute();
        }
    }
}

function drop_tables($conn, $table = null)
{
    if ($table === null) {
        $sql = "DROP TABLE IF EXISTS `Course Table`;";
        $sql .= "DROP TABLE IF EXISTS `Name Table`;";
        $sql .= "DROP TABLE IF EXISTS `Grade Table`;";
    } else {
        $sql = "DROP TABLE IF EXISTS `{$table}`;";
    }

    $result = $conn->multi_query($sql);
    while ($conn->next_result()) {
        continue;
    }
}

function create_tables($conn, $table = null)
{
    if ($table === null) {
        $sql = "CREATE TABLE `Name Table` (
            `Student_ID` int(9) NOT NULL,
            `Student_Name` varchar(255) NOT NULL,
            PRIMARY KEY (`Student_ID`)
        );";
        $sql .= "CREATE TABLE `Course Table` (
            `Student_ID` int(9) NOT NULL,
            `Course_Code` char(5) NOT NULL,
            `Test_1` decimal(4,1) NOT NULL, CHECK (`Test_1` >= 0 AND `Test_1` <= 100),
            `Test_2` decimal(4,1) NOT NULL, CHECK (`Test_2` >= 0 AND `Test_2` <= 100),
            `Test_3` decimal(4,1) NOT NULL, CHECK (`Test_3` >= 0 AND `Test_3` <= 100),
            `Final_Exam` decimal(4,1) NOT NULL, CHECK (`Final_Exam` >= 0 AND `Final_Exam` <= 100),
            PRIMARY KEY (`Student_ID`, `Course_Code`),
            FOREIGN KEY (`Student_ID`) REFERENCES `Name Table`(`Student_ID`)
        );";
    } else {
        if ($table === "Name Table") {
            $sql = "CREATE TABLE `Name Table` (
                `Student_ID` int(9) NOT NULL,
                `Student_Name` varchar(255) NOT NULL,
                PRIMARY KEY (`Student_ID`)
            );";
        } elseif ($table === "Course Table") {
            $sql = "CREATE TABLE `Course Table` (
                `Student_ID` int(9) NOT NULL,
                `Course_Code` char(5) NOT NULL,
                `Test_1` decimal(4,1) NOT NULL, CHECK (`Test_1` >= 0 AND `Test_1` <= 100),
                `Test_2` decimal(4,1) NOT NULL, CHECK (`Test_2` >= 0 AND `Test_2` <= 100),
                `Test_3` decimal(4,1) NOT NULL, CHECK (`Test_3` >= 0 AND `Test_3` <= 100),
                `Final_Exam` decimal(4,1) NOT NULL, CHECK (`Final_Exam` >= 0 AND `Final_Exam` <= 100),
                PRIMARY KEY (`Student_ID`, `Course_Code`),
                FOREIGN KEY (`Student_ID`) REFERENCES `Name_Table`(`Student_ID`)
            );";
        } else {
            return;
        }
    }

    if ($conn->multi_query($sql)) {
        while ($conn->next_result()) {
            continue;
        }
    } else {
        echo "Error creating tables: " . $conn->error;
    }
}

function upload_name_table($conn, $file_path)
{
    drop_tables($conn, `Name Table`);
    create_tables($conn, `Name Table`);
    $sql = "INSERT INTO `Name Table` (Student_ID, Student_Name) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        //echo "Error preparing the statement: " . $conn->error . "<br>";
        return false;
    }

    $file = fopen($file_path, "r");
    if (!$file) {
        //echo "Error opening the file.<br>";
        return false;
    }

    while (($line = fgets($file)) !== false) {
        $parts = explode(",", trim($line));
        if (count($parts) < 2) {
            //echo "Error: Invalid line format.<br>";
            continue;
        }

        $student_id = $parts[0];
        $student_name = $parts[1];

        $stmt->bind_param("is", $student_id, $student_name);

        try {
            $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            echo "<script>alert('Database error while uploading, please check your Name File to ensure it is formatted properly');</script>";
            return "Database error, please check your file to ensure it is formatted properly.";
        }
    }

    fclose($file);
    return true;
}

function upload_course_table($conn, $file_path)
{
  drop_tables($conn, `Course Table`);
  create_tables($conn, `Course Table`);
    $file = fopen($file_path, "r");

    while (!feof($file)) {
        $line = fgets($file);
        $line = trim($line);

        if ($line === "") {
            continue;
        }

        $data = explode(",", $line);
        $student_id = intval(trim($data[0]));
        $course_code = trim($data[1]);
        $test_1 = floatval(trim($data[2]));
        $test_2 = floatval(trim($data[3]));
        $test_3 = floatval(trim($data[4]));
        $final_exam = floatval(trim($data[5]));

        // Check if the Student_ID exists in the Name Table
        $sql =
            "SELECT COUNT(*) as count FROM `Name Table` WHERE Student_ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row["count"] == 0) {
            // If the Student_ID does not exist in the Name Table, skip this record and continue with the next one
            continue;
        }

        $sql =
            "INSERT INTO `Course Table` (`Student_ID`, `Course_Code`, `Test_1`, `Test_2`, `Test_3`, `Final_Exam`) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "isdddd",
            $student_id,
            $course_code,
            $test_1,
            $test_2,
            $test_3,
            $final_exam
        );
        try {
            $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            echo "<script>alert('Database error while uploading, please check your Course File to ensure it is formatted properly');</script>";
            return "Database error while uploading, please check your Course File to ensure it is formatted properly.";
        }
    }

    fclose($file);
}

?>
