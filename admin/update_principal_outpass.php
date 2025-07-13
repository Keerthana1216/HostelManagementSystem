<?php
session_start();
include('../includes/dbconn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sid']) && isset($_POST['astatus'])) {
    $sid = $_POST['sid'];
    $astatus = $_POST['astatus']; // Principal status: 'Approved' or 'Rejected'

    // Ensure only pending requests are updated
    $updateQuery = "UPDATE outpass_requests SET pstatus = '$astatus' WHERE sid = '$sid' AND pstatus = 'Pending'"; 
    if (mysqli_query($connection, $updateQuery)) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update status"]);
    }
}
?>
