<?php
session_start();
include('../includes/dbconn.php');

if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}

$email = $_SESSION['email'];
$query = "SELECT * FROM admin WHERE email = '$email'";
$result = mysqli_query($connection, $query);
$admin = mysqli_fetch_assoc($result);

$aid = $admin['aid'];
$name = $admin['name'];

$isPrincipal = strpos($aid, 'P') === 0;

if (!$isPrincipal) {
    echo "<script>alert('Access denied. Only Principal can access this page.'); window.location.href='plogin.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sid']) && isset($_POST['action'])) {
    $sid = $_POST['sid'];
    $action = $_POST['action'];

    $status = ($action === 'approve') ? 'Approved' : 'Rejected';
    $update = "UPDATE outpass_requests SET pstatus = '$status' WHERE sid = '$sid'";
    mysqli_query($connection, $update);
}

// Fetch outpass requests with approved status by faculty and department-wise for the Principal
$query = "
    SELECT o.sid, s.name, s.department, s.current_year,
           o.reason, o.leave_date, o.return_date, o.destination,
           o.tstatus, o.fstatus, o.astatus, o.sstatus, o.pstatus
    FROM outpass_requests o
    INNER JOIN students s ON o.sid = s.sid
    WHERE o.astatus = 'Approved' 
    AND o.sstatus = 'Pending' 
    AND o.leave_date >= CURDATE()  -- Ensure leave date is in the future
    ORDER BY s.department, o.leave_date DESC
";

$result = mysqli_query($connection, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Principal Outpass Requests</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<style>
   /* Sidebar and Content Styles */
   .sidebar {
       position: fixed;
       top: 0;
       bottom: 0;
       left: 0;
       width: 250px;
       background-color: #2c3e50;
       padding-top: 60px;
       z-index: 1000;
   }

   .main-content {
       margin-left: 250px;
       padding: 20px;
   }

   @media (max-width: 768px) {
       .sidebar {
           width: 100%;
           position: relative;
       }
       .main-content {
           margin-left: 0;
           width: 100%;
           padding: 10px;
       }
   }
</style>
<body style="background-color: #f8f9fa;">
<?php include('sidebar.php'); ?>
<br>
<div class="main-content">
    <h4>Welcome, <?php echo $name; ?></h4>
    <div class="alert alert-info text-center">This page is for: <strong>Principal</strong></div>
    <hr>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <table class="table table-bordered text-center">
            <thead class="thead-dark">
                <tr>
                    <th>S.No</th>
                    <th>SID</th>
                    <th>Name</th>
                    <th>Dept</th>
                    <th>Reason</th>
                    <th>Leave</th>
                    <th>Return</th>
                    <th>Destination</th>
                    <th>Faculty Status</th>
                    <th>HOD Status</th>
                    <th>Principal Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sno = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    $pstatus = $row['pstatus'];
                    $sid = $row['sid'];

                    // Badge class for principal status
                    $pBadge = 'badge badge-secondary';
                    if ($pstatus == 'Approved') $pBadge = 'badge badge-success';
                    elseif ($pstatus == 'Rejected') $pBadge = 'badge badge-danger';

                    echo "<tr id='row_$sid'>
                        <td>{$sno}</td>
                        <td>{$row['sid']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['department']}</td>
                        <td>{$row['reason']}</td>
                        <td>{$row['leave_date']}</td>
                        <td>{$row['return_date']}</td>
                        <td>{$row['destination']}</td>
                        <td><span class='badge badge-success'>{$row['fstatus']}</span></td>
                        <td><span class='badge badge-success'>{$row['astatus']}</span></td>
                        <td><span class='{$pBadge}' id='pstatus_$sid'>{$pstatus}</span></td>
                        <td>";

                    if ($pstatus == 'Pending') {
                        echo "<button class='btn btn-success btn-sm action-btn' data-sid='{$sid}' data-action='Approved'>Approve</button>
                              <button class='btn btn-danger btn-sm action-btn' data-sid='{$sid}' data-action='Rejected'>Reject</button>";
                    } else {
                        echo "<span class='text-muted '>Decision Made</span>";
                    }

                    echo "</td></tr>";
                    $sno++;
                }
                ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-center text-danger">No outpass requests.</p>
    <?php endif; ?>
</div>

<!-- Include jQuery at the top of your script if not included -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function(){
    $(".action-btn").click(function(){
        var sid = $(this).data("sid");
        var pstatus = $(this).data("action"); // 'Approve' or 'Reject' from button
        $('.approve-btn').click(function() {
    let requestId = $(this).data('id');
    updateStatus(requestId, 'Approved');
});

$('.reject-btn').click(function() {
    let requestId = $(this).data('id');
    updateStatus(requestId, 'Rejected');
});

        // AJAX request to update Principal's approval status
        $.ajax({
            url: "update_principal_outpass.php", // Script to update pstatus
            type: "POST",
            data: { sid: sid, astatus: pstatus }, // Passing sid and status
            dataType: "json",
            success: function(response) {
                if (response.status === "success") {
                    var badge = (pstatus === "Approved") ? "badge badge-success" : "badge badge-danger";
                    $("#pstatus_" + sid).attr("class", badge).text(pstatus); // Update status badge
                    $("#row_" + sid + " .action-btn").remove(); // Remove action buttons
                    $("#row_" + sid + " td:last").append("<span class='text-muted '>Decision Made</span>");
                    alert("Outpass Status Updated!");
                } else {
                    alert("Error: " + response.message); // Show server-side errors
                }
            },
            error: function(xhr, status, error) {
                alert("AJAX Error: " + error); // Handle AJAX errors
            }
        });
    });
});

</script>

</body>
</html>


