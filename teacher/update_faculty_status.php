<?php
include('../includes/dbconn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sid = mysqli_real_escape_string($connection, $_POST['sid']);
    $fstatus = mysqli_real_escape_string($connection, $_POST['fstatus']);

    $updateQuery = "UPDATE outpass_requests SET fstatus = '$fstatus' WHERE sid = '$sid' AND tstatus = 'Approved'";
    
    if (mysqli_query($connection, $updateQuery)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($connection)]);
    }
}
?>
