<?php
session_start();
include('../includes/dbconn.php');

if (!isset($_SESSION['email'])) {
    header('Location: ../index1.php');
    exit();
}

// Validate student ID from URL
if (!isset($_GET['sid']) || empty(trim($_GET['sid']))) {
    die("<div class='alert alert-danger text-center'>Invalid request. No student selected.</div>");
}

$sid = trim($_GET['sid']);

// Fetch student name
$student_query = "SELECT name FROM students WHERE sid = ?";
$stmt = mysqli_prepare($connection, $student_query);
mysqli_stmt_bind_param($stmt, "s", $sid);
mysqli_stmt_execute($stmt);
$student_result = mysqli_stmt_get_result($stmt);
$student = mysqli_fetch_assoc($student_result);
$student_name = $student ? $student['name'] : "Unknown Student";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>HMS</title>
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
}</style>
</head>
<body style="background-color: #f8f9fa;">
<?php include('topbar.php'); ?>
<br>
<div class="main-container">
<div class="container mt-5">
    <h4 class="text-center"></h4>
    <h5 class="text-center text-primary">Student: <?php echo htmlspecialchars($student_name); ?> (<?php echo htmlspecialchars($sid); ?>)</h5>

    <!-- Date Selection -->
    <div class="row justify-content-center">
        <div class="col-md-4">
            <input type="date" id="attendanceDate" class="form-control" value="<?php echo date('Y-m-d'); ?>">
        </div>
    </div>

    <!-- Attendance Table -->
    <table class="table table-bordered mt-4">
        <thead class="thead-dark">
            <tr>
                <th>Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="attendanceTable">
            <!-- Attendance records will be loaded here -->
        </tbody>
    </table>

    <a href="attendance.php" class="btn btn-secondary">Back</a>
</div>

<script>
$(document).ready(function () {
    function fetchAttendance() {
        var sid = "<?php echo $sid; ?>";
        var date = $("#attendanceDate").val();

        $.ajax({
            url: "fetch_attendance.php",
            type: "POST",
            data: { sid: sid, date: date },
            success: function (response) {
                $("#attendanceTable").html(response);
            },
            error: function () {
                alert("Error fetching attendance.");
            }
        });
    }

    // Fetch today's attendance on page load
    fetchAttendance();

    // Fetch attendance for selected date
    $("#fetchAttendance").click(function () {
        fetchAttendance();
    });

    // Update attendance
    $(document).on("click", ".update-btn", function () {
        var sid = $(this).data("sid");
        var status = $(this).closest("tr").find(".status-dropdown").val();

        $.ajax({
            url: "update_attendance.php",
            type: "POST",
            data: { sid: sid, status: status },
            success: function (response) {
                alert(response);
            },
            error: function () {
                alert("Error updating attendance.");
            }
        });
    });
});
</script>

</body>
</html>
