<?php
include('../includes/dbconn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sid = mysqli_real_escape_string($connection, $_POST['sid']);
    $tstatus = mysqli_real_escape_string($connection, $_POST['tstatus']);

    // Only update latest valid request that is NOT outdated
    $query = "
        SELECT * FROM outpass_requests 
        WHERE sid = '$sid' 
          AND return_date >= CURDATE()
          AND (sstatus IS NULL OR sstatus = '' OR sstatus = 'Pending')
        ORDER BY leave_date DESC 
        LIMIT 1
    ";
    $res = mysqli_query($connection, $query);

    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $id = $row['id']; // assuming your outpass_requests table has `id` as primary key

        $update = "
            UPDATE outpass_requests 
            SET tstatus = '$tstatus', sstatus = '$tstatus' 
            WHERE id = '$id'
        ";
        if (mysqli_query($connection, $update)) {
            echo json_encode([
                'status' => 'success',
                'sid' => $sid,
                'tstatus' => $tstatus,
                'sstatus' => $tstatus
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update status.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No valid current request found to update.']);
    }
}
?>
