<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $_SESSION['username'] = $_POST['username'];
        $_SESSION['password'] = $_POST['password'];

        header("Location: students_list.php");
        exit();
    } elseif (isset($_POST['students'])) {
        $_SESSION['selected_students'] = $_POST['students'];

        header("Location: display_student_courses.php");
        exit();
    }
}
