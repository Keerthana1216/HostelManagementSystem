<?php
include('../includes/dbconn.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sid = isset($_POST['sid']) ? trim($_POST['sid']) : '';
    $status = isset($_POST['status']) ? trim($_POST['status']) : '';

    if (empty($sid) || ($status !== "1" && $status !== "0")) {
        echo "Invalid input.";
        exit();
    }

    // Update attendance status using sid
    $query = "UPDATE attendance SET status = ? WHERE sid = ? AND date = CURDATE()";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "ss", $status, $sid);

    if (mysqli_stmt_execute($stmt)) {
        echo "Attendance updated successfully!";
    } else {
        echo "Error updating attendance.";
    }
}
?>
