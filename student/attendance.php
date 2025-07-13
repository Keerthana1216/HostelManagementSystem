<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: index2.php');
    exit();
}

include('../includes/dbconn.php');

$email = mysqli_real_escape_string($connection, $_SESSION['email']);

// Fetch student details
$query = "SELECT sid, name FROM students WHERE email = '$email'";
$result = mysqli_query($connection, $query);

if (mysqli_num_rows($result) == 0) {
    session_destroy();
    header('Location: index.php');
    exit();
}

$student = mysqli_fetch_assoc($result);
$sid = $student['sid'];
$name = $student['name'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <title>HMS</title>
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
</style>
</head>

<body style="background-color: #f8f9fa;">
<?php include('topbar.php'); ?>

<div class="main-content">
<nav class="">
        <h3>HOSTEL MANAGEMENT SYSTEM</h3>
    </nav>

<div class="container mt-5">
    <h4 class="mt-3">
        <span style="color: black; font-weight: bold;">Report: </span> 
        <span style="color: #007bff; font-weight: bold;"><?php echo htmlspecialchars($name); ?> (ID: <?php echo htmlspecialchars($sid); ?>)</span>
    </h4>
    
    <!-- Search Bar -->
<div class="d-flex justify-content-end mb-3">
    <input type="text" id="searchMonth" class="form-control form-control-sm" 
           placeholder="Search month" 
           style="width: 200px; border-radius: 5px; border: 1px solid #ced4da;">
    <button class="btn btn-sm btn-primary ml-2" id="searchBtn">Search</button>
</div>

    <hr>

    <!-- Attendance Table -->
    <table class="table table-striped mt-3">
       <thead class="thead-dark">
            <tr>
                <th scope="col">S.No</th>
                <th scope="col">Month</th>
                <th scope="col">Year</th>
                <th scope="col">Total Days</th>
                <th scope="col">Present Days</th>
                <th scope="col">Attendance Percentage</th>
            </tr>
        </thead>
        <tbody id="attendanceTable">
            <!-- Data will be loaded here dynamically -->
        </tbody>
    </table>
    
<!-- jQuery & Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.9/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
$(document).ready(function() {
    function fetchAttendance(month = '') {
        $.ajax({
            url: "fetch_attendance.php",
            type: "POST",
            data: { sid: "<?php echo $sid; ?>", month: month },
            success: function(data) {
                $("#attendanceTable").html(data);
                $("#downloadLink").attr("href", "download_attendance.php?sid=<?php echo $sid; ?>&month=" + month);
            }
        });
    }

    // Load all attendance records initially
    fetchAttendance();

    // Handle search
    $("#searchBtn").click(function() {
        var month = $("#searchMonth").val();
        fetchAttendance(month);
    });
});
</script>

</body>
</html>
