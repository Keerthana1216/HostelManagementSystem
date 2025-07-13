<?php
session_start();
include('../includes/dbconn.php');

if (!isset($_SESSION['email'])) {
    header('Location: ../index1.php');
    exit();
}

$rid = mysqli_real_escape_string($connection, $_SESSION['rid']);

// Check if it's a faculty (Fxxx)
if (strpos($rid, 'F') !== 0) {
    header("Location: rlogin.php");
    exit();
}

// Fetch faculty details (department + current_year)
$faculty_query = "SELECT department, current_year FROM teachers WHERE rid = '$rid'";
$faculty_result = mysqli_query($connection, $faculty_query);

if (!$faculty_result || mysqli_num_rows($faculty_result) === 0) {
    die("Faculty details not found.");
}

$faculty = mysqli_fetch_assoc($faculty_result);
$dept = $faculty['department'];
$year = $faculty['current_year'];

// Fetch requests approved by teacher for this dept/year
$query = "
    SELECT o.sid, s.name, s.department, s.current_year,
           o.reason, o.leave_date, o.return_date, o.destination,
           o.tstatus, o.fstatus, o.sstatus
    FROM outpass_requests o
    INNER JOIN students s ON o.sid = s.sid
    WHERE o.tstatus = 'Approved'
      AND s.department = '$dept'
      AND s.current_year = '$year'
    ORDER BY o.leave_date DESC
";

$result = mysqli_query($connection, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>HMS</title>
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
<body style="background-color: #f8f9fa;">
<?php include('topbar.php'); ?>
<br>
<br>
<div class="main-content">
<div class="container mt-4">
    <h4 class="mb-3">RT Approval Requests</h4>
<hr>
    <table class="table table-striped table table-bordered table-sm text-center align-middle">
        <thead class="thead-dark">
            <tr>
                <th>SID</th>
                <th>Student ID</th>
                <th>Name</th>
                <th>Dept</th>
                <th>Year</th>
                <th>Reason</th>
                <th>L.Date</th>
                <th>R.Date</th>
                <th>Destination</th>
                <th>RT Status</th>
                <th>Faculty Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $sno = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            $sid = $row['sid'];
            $fstatus = $row['fstatus'] ?? 'Pending';
            $tstatus = $row['tstatus'];

            // Badge class for faculty status
            $fBadge = 'badge badge-secondary';
            if ($fstatus == 'Approved') $fBadge = 'badge badge-success';
            elseif ($fstatus == 'Rejected') $fBadge = 'badge badge-danger';

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
                    <td><span class='badge badge-success'>{$tstatus}</span></td>
                    <td><span class='{$fBadge}' id='fstatus_$sid'>{$fstatus}</span></td>
                    <td>";

            // Action buttons only if faculty status is pending/null
            if ($fstatus == 'Pending' || $fstatus == NULL || $fstatus == '') {
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
        var fstatus = $(this).data("action");

        $.ajax({
            url: "update_faculty_status.php",
            type: "POST",
            data: { sid: sid, fstatus: fstatus },
            dataType: "json",
            success: function(response) {
                if (response.status === "success") {
                    alert("Outpass Status Updated!");
                    var badge = (fstatus === "Approved") ? "badge badge-success" : "badge badge-danger";
                    $("#fstatus_" + sid).attr("class", badge).text(fstatus);
                    $("#row_" + sid + " .action-btn").remove(); // remove buttons
                    $("#row_" + sid + " td:last").append("<span class='text-muted font-weight-bold'>Decision Made</span>");
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

</body>
</html>
