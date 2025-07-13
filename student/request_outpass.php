<?php
session_start();
include('../includes/dbconn.php');

// Check if the student is logged in
if (!isset($_SESSION['email'])) {
    header('Location: ../index2.php');
    exit();
}

$sid = $_SESSION['sid']; // Get Student ID
$current_datetime = date('Y-m-d H:i:s'); // Get current date & time

// Fetch Student's Outpass Requests Using Prepared Statement
$query = $connection->prepare("SELECT leave_date, return_date, leave_time, return_time, reason, destination, sstatus, tstatus, astatus 
                               FROM outpass_requests 
                               WHERE sid = ? 
                               ORDER BY leave_date DESC");
$query->bind_param("s", $sid);
$query->execute();
$result = $query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HMS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .container { max-width: 900px; margin: auto; margin-top: 50px; }
        .table thead { background-color: #343a40; color: white; }
        .badge { font-size: 14px; padding: 6px 10px; }
    </style>
</head>
<body>
<?php include('topbar.php'); ?>

<div class="container mt-4">
    <h4 class="mb-3">My Outpass Requests</h4>

    <?php if ($result->num_rows > 0) { ?>
    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Leave Date</th>
                <th>Return Date</th>
                <th>Reason</th>
                <th>Destination</th>
                <th>RT Status</th>
                <th>Faculty Status</th>
                <th>HOD Status</th>
                <th>Admin Status</th>
                <th>Final Status</th>
            </tr>
        </thead>
        <tbody>
        <?php
        // Loop through the results and determine final status for each request
        while ($row = $result->fetch_assoc()) {
            $leave_date = htmlspecialchars($row['leave_date']);
            $return_date = htmlspecialchars($row['return_date']);
            $leave_time = htmlspecialchars($row['leave_time']);
            $return_time = htmlspecialchars($row['return_time']);
            $reason = htmlspecialchars($row['reason']);
            $destination = htmlspecialchars($row['destination']);
            $tstatus = htmlspecialchars($row['tstatus']);
            $fstatus = htmlspecialchars($row['fstatus']);
            $hstatus = htmlspecialchars($row['hstatus']);
            $astatus = htmlspecialchars($row['astatus']);
            $sstatus = htmlspecialchars($row['sstatus']);

            // Combine return date and return time to form the return datetime
            $return_datetime = $return_date . " " . $return_time;

            // Determine Badge Colors
            $tstatusBadge = ($tstatus == 'Approved') ? "badge badge-success" : (($tstatus == 'Rejected') ? "badge badge-danger" : "badge badge-primary");
            $astatusBadge = ($astatus == 'Approved') ? "badge badge-success" : (($astatus == 'Rejected') ? "badge badge-danger" : "badge badge-secondary");

            // Final Status Logic
            if ($return_datetime < $current_datetime) {
                // If the return date and time have passed, mark as "Outdated"
                $finalStatus = "Outdated Request";
                $finalBadge = "badge badge-secondary"; // Outdated requests have a secondary badge
            } else {
                // If the return date and time have not passed, show the current status
                if ($tstatus == "Rejected" || $astatus == "Rejected") {
                    $finalStatus = "Rejected";
                    $finalBadge = "badge badge-danger";
                } elseif ($tstatus == "Approved" && $astatus == "Pending") {
                    $finalStatus = "Pending";
                    $finalBadge = "badge badge-warning";
                } elseif ($astatus == "Approved") {
                    $finalStatus = "Approved";
                    $finalBadge = "badge badge-success";
                } else {
                    $astatus = "Pending";
                    $finalStatus = "Pending";                   
                    $finalBadge = "badge badge-primary";
                }
            }

            // Output each row of the table
            echo "<tr>
                    <td>$leave_date</td>
                    <td>$return_date</td>
                    <td>$reason</td>
                    <td>$destination</td>
                    <td><span class='$tstatusBadge'>$tstatus</span></td>
                    <td><span class='$fstatusBadge'>$fstatus</span></td>
                    <td><span class='$hstatusBadge'>$hstatus</span></td>
                    <td><span class='$astatusBadge'>$astatus</span></td>
                    <td><span class='$finalBadge'>$finalStatus</span></td>
                  </tr>";
        }
        ?>
        </tbody>
    </table>
    <?php } else { ?>
        <p class="text-center text-danger">No outpass requests found</p>
    <?php } ?>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
