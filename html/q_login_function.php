<?php
include("config.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_ID = $_POST['student_ID'];
    $password = $_POST['password'];

    $query = "SELECT * FROM q_student WHERE student_ID = '$student_ID' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        // Fetching student's name
        $row = mysqli_fetch_assoc($result);
        $student_name = $row['name'];

        // Setting session variables
        $_SESSION['student_ID'] = $student_ID;
        $_SESSION['student_name'] = $student_name;

        // Redirecting to dashboard
        header("Location: q_index.php");
        exit();
    } else {
        $error_message = "Invalid login credentials. Please try again.";
        header("Location: q_login.php");
    }
}

mysqli_close($conn);
?>