<?php
session_start();
include('../includes/dbconn.php');

// Set timezone
date_default_timezone_set('Asia/Kolkata');

// Check if student is logged in
if (!isset($_SESSION['email'])) {
    header('Location: ../index2.php');
    exit();
}

$sid = $_SESSION['sid'];
$current_datetime = new DateTime();

// Fetch Student's Outpass Requests
$query = $connection->prepare("SELECT leave_date, return_date, leave_time, return_time, reason, destination, sstatus, tstatus, fstatus, astatus, pstatus 
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
        a.btn:hover {
        background-color: #5a5a6e;
        border-color: #343a40;
        }
        @media (max-width: 768px) {
        .sidebar {
            width: 100%;
            height: auto;
            position: relative;
        }
        .main-content {
            margin-left: 0;
            width: 100%;
            padding: 10px;
        }
    }
     /* Align title and date in a straight line */
     .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 100px; /* Ensure there's enough space below the topbar */
        }

        .header-container h4 {
            margin: 0;
        }

        /* Ensure that the topbar doesn't overlap with the content */
        .topbar {
            z-index: 9999; /* Ensure it's on top */
            position: relative;
        }
    </style>
</head>
<body>
<?php include('topbar.php'); ?>

<div class="main-content">           
<div class="container mt-3">
<nav class="">
    <h3>My Outpass Requests</h3>
    </nav>
     <!-- Display success message -->
     <?php if (isset($_SESSION['success_message'])): ?>
    <div id="successAlert" class="alert alert-success alert-dismissible fade show text-center" role="alert">
        <?php echo $_SESSION['success_message']; ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <!-- Display error message -->
    <?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
        <?php echo $_SESSION['error_message']; ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <?php if ($result->num_rows > 0) { ?>
        
<div class="container mt-3">
    <hr>
    <table class="table table-striped mt-3">
        <thead class="thead-dark">
            <tr>
                <th>Leave Date</th>
                <th>Return Date</th>
                <th>Reason</th>
                <th>Destination</th>
                <th>RT Status</th>
                <th>Faculty Status</th>
                <th>HOD Status</th>
                <th>Principal Status</th>
                <th>Final Status</th>
            </tr>
        </thead>
        <tbody>
        <?php
        while ($row = $result->fetch_assoc()) {
            $leave_date = htmlspecialchars($row['leave_date']);
            $return_date = htmlspecialchars($row['return_date']);
            $return_time = !empty($row['return_time']) ? htmlspecialchars($row['return_time']) : '23:59:59';
            $reason = htmlspecialchars($row['reason']);
            $destination = htmlspecialchars($row['destination']);
            
            $tstatus = !empty($row['tstatus']) ? htmlspecialchars($row['tstatus']) : 'Pending';
            $fstatus = !empty($row['fstatus']) ? htmlspecialchars($row['fstatus']) : 'Pending';
            $astatus = !empty($row['astatus']) ? htmlspecialchars($row['astatus']) : 'Pending';
            $pstatus = !empty($row['pstatus']) ? htmlspecialchars($row['pstatus']) : 'Pending';
            $sstatus = !empty($row['sstatus']) ? htmlspecialchars($row['sstatus']) : 'Pending';
        
            $finalStatus = $sstatus;
        
            $return_datetime = new DateTime("$return_date $return_time");
        
            // Badge Classes
            if (!function_exists('getBadgeClass')) {
                function getBadgeClass($status) {
                    switch (strtolower($status)) {
                        case 'approved':
                            return 'badge badge-success';
                        case 'pending':
                            return 'badge badge-primary';
                        case 'rejected':
                            return 'badge badge-danger';
                        default:
                            return 'badge badge-secondary';
                    }
                }
            }
            
        
            // Final Status Logic
            if ($tstatus == "Rejected" || $fstatus == "Rejected" || $astatus == "Rejected" || $pstatus == "Rejected") {
                $finalStatus = "Rejected";
                $sstatus = "Rejected";
            } elseif ($return_datetime < $current_datetime) {
                $finalStatus = "Outdated Request";
                $sstatus = "Outdated Request";
            } elseif ($tstatus == "Approved" && $fstatus == "Approved" && $astatus == "Approved" && $pstatus == "Approved") {
                $finalStatus = "Approved";
                $sstatus = "Approved";
            } else {
                $finalStatus = "Pending";
                $sstatus = "Pending";
            }
        
            // Update DB
            $updateQuery = $connection->prepare("UPDATE outpass_requests 
                                                 SET final_status = ?, sstatus = ?, tstatus = ?, fstatus = ?, astatus = ?, pstatus = ? 
                                                 WHERE sid = ? AND leave_date = ? AND return_date = ?");
            $updateQuery->bind_param("sssssssss", $finalStatus, $sstatus, $tstatus, $fstatus, $astatus, $pstatus, $sid, $leave_date, $return_date);
            $updateQuery->execute();
        
            // Table row output
            echo "<tr>
                    <td>$leave_date</td>
                    <td>$return_date</td>
                    <td>$reason</td>
                    <td>$destination</td>
                    <td><span class='".getBadgeClass($tstatus)."'>$tstatus</span></td>
                    <td><span class='".getBadgeClass($fstatus)."'>$fstatus</span></td>
                    <td><span class='".getBadgeClass($astatus)."'>$astatus</span></td>
                    <td><span class='".getBadgeClass($pstatus)."'>$pstatus</span></td>
                    <td><span class='".getBadgeClass($finalStatus)."'>$finalStatus</span></td>
                </tr>";
        }
              
        ?>
        </tbody>
    </table>
    <?php } else { ?>
        <p class="text-center text-danger">No outpass requests found.</p>
    <?php } ?>
</div>
    </div>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    // Hide success message after 5 seconds
    setTimeout(() => {
        let successAlert = document.getElementById('successAlert');
        if (successAlert) {
            successAlert.style.display = 'none';
        }
    }, 5000);
</script>

</body>
</html>
