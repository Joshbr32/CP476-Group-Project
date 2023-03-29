<?php
include 'db_connect.php';
$conn = connect_to_database();
fetch_student_names($conn);

$conn->close();

?>
