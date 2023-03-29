<?php
include 'db_connect.php';
include 'db_functions.php';

$conn = connect_to_database();
$students = fetch_student_names($conn);

if (isset($_POST['submit_students'])) {
    $_SESSION['selectedStudentIds'] = $_POST['students'];

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students List</title>
</head>
<body>
    <h1>Students List</h1>
    <form id="studentForm" method="POST">
        <?php
        foreach ($students as $student_id => $student_name) {
            echo "<input type='checkbox' name='students[]' value='$student_id'> $student_name<br>";
        }
        ?>
        <input type="submit" name="submit_students" value="Submit">
    </form>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        echo "POST data: ";
        var_dump($_POST);
    }
    ?>
</body>
</html>
