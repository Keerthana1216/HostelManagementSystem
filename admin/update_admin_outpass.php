<?php
include('../includes/dbconn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sid = mysqli_real_escape_string($connection, $_POST['sid']);
    $astatus = mysqli_real_escape_string($connection, $_POST['astatus']);

    $updateQuery = "UPDATE outpass_requests SET astatus = '$astatus' WHERE sid = '$sid' AND fstatus = 'Approved'";
    
    if (mysqli_query($connection, $updateQuery)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($connection)]);
    }
}
?>
