<?php
session_start();
include('../includes/dbconn.php');

if (!isset($_SESSION['email'])) {
    header('Location: ../index1.php');
    exit();
}

$rid = mysqli_real_escape_string($connection, $_SESSION['rid']);

// Fetch teacher's gender
$teacher_query = "SELECT gender FROM teachers WHERE rid = '$rid'";
$teacher_result = mysqli_query($connection, $teacher_query);

if (!$teacher_result) {
    die("SQL Error (Fetching Teacher): " . mysqli_error($connection));
}

$rid = $_SESSION['rid']; // Get the teacher's ID from session

// ✅ Redirect F-ID teachers to another page
if (strpos($rid, 'F') === 0) {
    header("Location: flogin.php");
    exit();
}

$teacher_data = mysqli_fetch_assoc($teacher_result);
if (!$teacher_data) {
    die("Error: Teacher data not found.");
}

$teacher_gender = $teacher_data['gender'];

// Fetch outpass requests where student gender matches teacher gender
$query = "
    SELECT o.sid, s.name, s.department, s.current_year,
           o.reason, o.leave_date, o.return_date, o.destination, 
           o.sstatus, o.tstatus
    FROM outpass_requests o
    INNER JOIN students s ON o.sid = s.sid
    WHERE s.gender = '$teacher_gender'
      AND (o.sstatus IS NULL OR o.sstatus = '' OR o.sstatus = 'Pending')
      AND o.return_date >= CURDATE()
    ORDER BY o.leave_date DESC";

$result = mysqli_query($connection, $query);

if (!$result) {
    die("SQL Error (Fetching Outpass Requests): " . mysqli_error($connection));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<style>
        @media (max-width: 768px) {
    .sidebar {
        width: 100%;  /* Full width on small screens */
        height: auto;  /* Adjust height */
        position: relative;
    }
    .main-content {
        margin-left: 0;  /* Remove left margin */
        width: 100%;  /* Take full width */
        padding: 10px;  /* Add spacing */
    }
}
</style>
<?php include('topbar.php'); ?>
<body style="background-color: #f8f9fa;">
<br>
<br>
<br>
<div class="main-content">
<div class="container mt-4">
    <h4 class="mb-3">Outpass Requests</h4>
<hr>
<table class="table table-striped table table-bordered table-sm text-center align-middle">
    <thead class="thead-dark">
        <tr>
            <th>S.No</th>
            <th style="min-width: 100px;">Student ID</th>
            <th style="min-width: 120px;">Name</th>
            <th style="min-width: 40px;">Dept</th>
            <th>Year</th>
            <th style="min-width: 120px;">Reason</th>
            <th style="min-width: 130px;">L.Date</th>
            <th style="min-width: 120px;">R.Date</th>
            <th style="min-width: 120px;">Destination</th>
            <th style="min-width: 120px;">S.Status</th>
            <th style="min-width: 120px;">RT Status</th>
            <th style="min-width: 150px;">Action</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $sno = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        $sid = $row['sid'];
        $sstatus = htmlspecialchars($row['sstatus'] ?? 'Pending');
        $tstatus = htmlspecialchars($row['tstatus'] ?? 'Pending');

        $sstatusBadge = "badge badge-secondary";
        if ($sstatus == 'Approved') $sstatusBadge = "badge badge-success";
        elseif ($sstatus == 'Rejected') $sstatusBadge = "badge badge-danger";

        $tstatusBadge = "badge badge-secondary";
        if ($tstatus == 'Approved') $tstatusBadge = "badge badge-success";
        elseif ($tstatus == 'Rejected') $tstatusBadge = "badge badge-danger";

        echo "<tr id='row_$sid'>
                <td>{$sno}</td>
                <td>{$sid}</td>
                <td>{$row['name']}</td>
                <td>{$row['department']}</td>
                <td>{$row['current_year']}</td>
                <td>{$row['reason']}</td>
                <td>{$row['leave_date']}</td>
                <td>{$row['return_date']}</td>
                <td>{$row['destination']}</td>
                <td><span class='{$sstatusBadge}' id='sstatus_{$sid}'>{$sstatus}</span></td>
                <td><span class='{$tstatusBadge}' id='tstatus_{$sid}'>{$tstatus}</span></td>
                <td>";

                if ($tstatus == 'Pending' || $tstatus == '') {
                    echo "<button class='btn btn-success btn-sm action-btn' data-sid='{$sid}' data-action='Approved'>Approve</button>
                          <button class='btn btn-danger btn-sm action-btn' data-sid='{$sid}' data-action='Rejected'>Reject</button>";
                } else {
                    echo "<span class='text-muted '>Decision</span>";
                }                
            
        echo "</td></tr>";
        $sno++;
    }
    ?>
    </tbody>
    </table>

    <?php 
    if (mysqli_num_rows($result) == 0) {
        echo '<p class="text-center text-danger">No outpass requests</p>';
    }
    ?>
</div>
<script>
$(document).ready(function(){
    $(".action-btn").click(function(){
        var sid = $(this).data("sid");
        var tstatus = $(this).data("action");
        $('.approve-btn').click(function() {
    let requestId = $(this).data('id');
    updateStatus(requestId, 'Approved');
});

$('.reject-btn').click(function() {
    let requestId = $(this).data('id');
    updateStatus(requestId, 'Rejected');
});

        $.ajax({
            url: "update_outpass_status.php",
            type: "POST",
            data: { sid: sid, tstatus: tstatus },
            dataType: "json",
            success: function(response) {
                if (response.status === "success") {
                    alert("Outpass Status Updated Successfully!");

                    var tstatusBadge = (response.tstatus === "Approved") ? "badge badge-success" : "badge badge-danger";
                    var sstatusBadge = (response.sstatus === "Pending") ? "badge badge-secondary" : "badge badge-secondary";

                    $("#tstatus_" + sid).attr("class", tstatusBadge).text(response.tstatus);
                    $("#sstatus_" + sid).attr("class", sstatusBadge).text(response.sstatus);

                    $("#row_" + sid + " .action-btn").prop("disabled", true).addClass("disabled");
                } else {
                    alert("Error: " + response.message);
                }
            },
            error: function(xhr, status, error) {
                alert("AJAX Error: " + error);
            }
        });
    });
});
</script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
