<?php
    include("config.php");
    session_start();

    
    if (!isset($_SESSION['student_ID'])) {
        header("Location: q_index.php");
        exit();
    }
    $studentID = $_SESSION['student_ID'];


    $Attempts_left = 5;

    $currentAttemptSql = "SELECT MIN(num_attempts) AS min_attempts FROM q_registrar WHERE student_ID = '$studentID' AND status = 'active'";
    $resultCA = mysqli_query($conn, $currentAttemptSql);
    $rowCA = mysqli_fetch_assoc($resultCA);
    
    // Handling the case where min_attempts is NULL
    $minAttempts = isset($rowCA['min_attempts']) ? $rowCA['min_attempts'] : null;
    
    if($minAttempts === null) {
        $Attempts_left = 5;
    } else {
        switch ($minAttempts) {
            case 5:
                $Attempts_left -= 1;
                break;
            case 4:
                $ATsql = "UPDATE q_registrar SET status = 'expired', s_stat = 'served' WHERE student_ID = '$studentID' AND num_attempts = 5";
                mysqli_query($conn, $ATsql);
                $Attempts_left -= 2;
                break;
            case 3:
                $ATsql1 = "UPDATE q_registrar SET status = 'expired', s_stat = 'served' WHERE student_ID = '$studentID' AND num_attempts = 4";
                mysqli_query($conn, $ATsql1);
                $Attempts_left -= 3;
                break;
            case 2:
                $ATsql2 = "UPDATE q_registrar SET status = 'expired', s_stat = 'served' WHERE student_ID = '$studentID' AND num_attempts = 3";
                mysqli_query($conn, $ATsql2);
                $Attempts_left -= 4;
                break;
            case 1:
                $ATsql4 = "UPDATE q_registrar SET status = 'expired', s_stat = 'served' WHERE student_ID = '$studentID' AND num_attempts = 2";
                mysqli_query($conn, $ATsql4);
                $Attempts_left -= 5;
                header("Location: q_registrar.php");
                exit();
                break;
            default:
            echo "<script>alert('YOU CANNOT QUEUE ANYMORE');</script>";
                break;
        }
    }
    
    





        
    if (isset($_SESSION['last_queue_time'])) {
        $currentTime = time();
        $lastQueueTime = $_SESSION['last_queue_time'];
        $queueDelay = 120; 
        $timeSinceLastQueue = $currentTime - $lastQueueTime;

        if ($timeSinceLastQueue < $queueDelay) {
            $timeLeft = $queueDelay - $timeSinceLastQueue;
            echo "<script>alert('You must wait " . $timeLeft . " seconds before queuing again. " ."\\n"."You have " . $Attempts_left . " attempt left.');</script>";
            echo "<script>window.location.href = 'q_registrar.php';</script>";
            exit();
        }      
    }





    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['QUEUE'])) {
        // Store the student ID in a variable for later use
        $dateTime = date("Y-m-d H:i:s"); // Format the date-time as per your database column

        // Get the current queue number
        $sqlQueue = "SELECT MAX(priority_queue_number) as max_queue FROM q_registrar";
        $resultQueue = mysqli_query($conn, $sqlQueue);
        $rowQueue = mysqli_fetch_assoc($resultQueue);
        $currentQueueNumber = $rowQueue['max_queue'];

        // If the current queue is null, set it to 3000
        if ($currentQueueNumber === null) {
            $currentQueueNumber = 3000;
        }

        // Increment the queue number by 1
        $nextQueueNumber = $currentQueueNumber + 1;

        // If the next queue number reaches 3999, reset it to 3001
        if ($nextQueueNumber == 3999) {
            $resetSql = "UPDATE q_registrar SET priority_queue_number = 3000";
            mysqli_query($conn, $resetSql);
            $nextQueueNumber = 3001; // Reset to 3001
        }

        // Update the session variable with the current time
        $_SESSION['last_queue_time'] = time();

        // Insert the queue information into the database
        $sql = "INSERT INTO q_registrar (student_ID, priority_queue_number, time, status, s_stat, num_attempts) 
                VALUES ('$studentID', '$nextQueueNumber', '$dateTime', 'active', 'waiting',$Attempts_left )";

        // Execute the SQL query
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Reserve successful! Queue Number: $nextQueueNumber');</script>";
            echo "<script>window.location.reload();</script>"; 
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
?>
