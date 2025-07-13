<?php
session_start();
include('../includes/dbconn.php');

if (isset($_POST['sid'], $_POST['status'])) {
    $sid = mysqli_real_escape_string($connection, $_POST['sid']);
    $status = mysqli_real_escape_string($connection, $_POST['status']);
    $date = gmdate('Y-m-d');

    // Check if attendance for this date and student already exists
    $check_query = "SELECT * FROM attendance WHERE sid='$sid' AND date='$date'";
    $result = mysqli_query($connection, $check_query);
    mysqli_query($conn, "INSERT IGNORE INTO attendance (sid, date, status) VALUES ('$sid', '$date', '$status')");
$check = mysqli_query($conn, "SELECT * FROM attendance WHERE sid = '$sid' AND date = '$date'");
if (mysqli_num_rows($check) == 0) {
    mysqli_query($conn, "INSERT INTO attendance (sid, date, status) VALUES ('$sid', '$date', '$status')");
} else {
    echo "Attendance already marked for student $sid on $date.";
}

    if (mysqli_num_rows($result) > 0) {
        // Update if record exists
        $update_query = "UPDATE attendance SET status='$status' WHERE sid='$sid' AND date='$date'";
        mysqli_query($connection, $update_query);
    } else {
        // Insert if no record exists
        mysqli_query($connection, "INSERT IGNORE INTO attendance (sid, date, status) VALUES ('$sid', '$date', '$status')");
        $check = mysqli_query($connection, "SELECT * FROM attendance WHERE sid = '$sid' AND date = '$date'");
if (mysqli_num_rows($check) == 0) {
    mysqli_query($connection, "INSERT INTO attendance (sid, date, status) VALUES ('$sid', '$date', '$status')");
} else {
    echo "Attendance already marked for student $sid on $date.";
}

    }

    header("Location: attendance.php");
    exit;
} else {
    echo "Invalid request!";
}
?>
