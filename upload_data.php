<?php
include 'db_connect.php';
include 'db_functions.php';

$conn = connect_to_database();

drop_tables($conn);
create_tables($conn);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['upload_name_table']) && isset($_FILES['name_txt'])) {

        $file = $_FILES['name_txt']['tmp_name'];
        upload_name_table($conn, $file);
        $_SESSION['upload_name_success'] = true;

    } elseif (isset($_POST['upload_course_table']) && isset($_FILES['course_txt'])) {

        if (isset($_SESSION['upload_name_success'])) {

            $file = $_FILES['course_txt']['tmp_name'];
            upload_course_table($conn, $file);
            $_SESSION['upload_course_success'] = true;

        } else {
            $_SESSION['upload_name_first'] = true;
        }
    }
}

$upload_name_success = isset($_SESSION['upload_name_success']);
$upload_course_success = isset($_SESSION['upload_course_success']);
$upload_name_first = isset($_SESSION['upload_name_first']);

if (isset($_SESSION['upload_name_success']) && isset($_SESSION['upload_course_success'])) {
    unset($_SESSION['upload_name_success']);
    unset($_SESSION['upload_course_success']);
    unset($_SESSION['initiated']);
}
if (isset($_SESSION['upload_name_first'])) {
    unset($_SESSION['upload_name_first']);
}
?>

<!DOCTYPE html>
<html lang="en">
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
    </script>
</head>
<body>
    <h1>Upload Data</h1>
    <h2>Upload Name Table</h2>
    <form action="upload_data.php" method="post" enctype="multipart/form-data">
        <input type="file" name="name_txt" accept=".txt">
        <input type="submit" name="upload_name_table" value="Upload Name Table">
    </form>
    <?php if ($upload_name_success) echo "<p>Name Table has been successfully uploaded.</p>"; ?>

    <h2>Upload Course Table</h2>
    <form action="upload_data.php" method="post" enctype="multipart/form-data" onsubmit="return checkNameFileUploaded();">
        <input type="file" name="course_txt" accept=".txt">
        <input type="submit" name="upload_course_table" value="Upload Course Table">
    </form>
    <?php if ($upload_course_success) echo "<p>Course Table has been successfully uploaded.</p>"; ?>

    <?php if ($upload_name_success && $upload_course_success): ?>
        <form action="students_list.php" method="get">
            <input type="submit" value="Go to Students List">
        </form>
    <?php endif; ?>
</body>
</html>
