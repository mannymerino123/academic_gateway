<?php
    include("config.php");

    // Initialize error message variable
    $error_message = '';

    // Process registration form data
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $student_ID = $_POST['student_ID'];
        $name = $_POST['name'];
        $password = $_POST['password'];

        $query = "INSERT INTO q_student (student_ID, name, password) VALUES ('$student_ID', '$name', '$password')";

        if (mysqli_query($conn, $query)) {
            header("Location: q_login.php");
            exit();
        } else {
            $error_message = "Error: " . $query . "<br>" . mysqli_error($conn);
        }
    }

    // Close the connection
    mysqli_close($conn);
?>