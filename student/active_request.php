<?php
session_start();
include('../includes/dbconn.php');

if (!isset($_SESSION['sid'])) {
    echo "<script>alert('Please log in first.'); window.location='index2.php';</script>";
    exit();
}

$sid = $_SESSION['sid'];
$current_datetime = date("Y-m-d H:i:s"); // Current Date & Time

// Fetch Outpass Requests
$query = $connection->prepare("SELECT id, leave_date, return_date, leave_time, return_time, reason, destination, sstatus, tstatus, astatus 
                               FROM outpass_requests 
                               WHERE sid = ? 
                               ORDER BY leave_date DESC");
$query->bind_param("s", $sid);
$query->execute();
$result = $query->get_result();

while ($row = $result->fetch_assoc()) {
    $id = $row['id']; // Unique Outpass ID
    $leave_date = htmlspecialchars($row['leave_date']);
    $return_date = htmlspecialchars($row['return_date']);
    $leave_time = htmlspecialchars($row['leave_time']);
    $return_time = htmlspecialchars($row['return_time']);
    $reason = htmlspecialchars($row['reason']);
    $destination = htmlspecialchars($row['destination']);
    $tstatus = htmlspecialchars($row['tstatus']);
    $astatus = htmlspecialchars($row['astatus']);
    $sstatus = htmlspecialchars($row['sstatus']);

    // Combine return date and return time to form the return datetime
    $return_datetime = $return_date . " " . $return_time;

    // Check if the outpass has expired
    if ($return_datetime < $current_datetime) {
        $finalStatus = "Outdated";
        $finalBadge = "badge badge-secondary"; // Grey color for outdated requests
        
        // Update the database status to "Outdated" if not already set
        if ($sstatus != "Outdated") {
            $update_query = $connection->prepare("UPDATE outpass_requests SET sstatus = 'Outdated' WHERE id = ?");
            $update_query->bind_param("i", $id);
            $update_query->execute();
        }
    } elseif ($tstatus == "Rejected" || $astatus == "Rejected by Admin") {
        $finalStatus = "Rejected";
        $finalBadge = "badge badge-danger";
    } elseif ($tstatus == "Approved" && $astatus == "Pending") {
        $finalStatus = "Pending | Waiting for Admin";
        $finalBadge = "badge badge-warning"; // Yellow
    } elseif ($astatus == "Approved by Admin") {
        $finalStatus = "Approved";
        $finalBadge = "badge badge-success";
    } else {
        $finalStatus = "Pending";
        $finalBadge = "badge badge-primary";
    }

    // Display the request in the table
    echo "<tr>
            <td>$leave_date</td>
            <td>$return_date</td>
            <td>$reason</td>
            <td>$destination</td>
            <td><span class='badge badge-info'>$tstatus</span></td>
            <td><span class='badge badge-info'>$astatus</span></td>
            <td><span class='$finalBadge'>$finalStatus</span></td>
          </tr>";
}
?>
