<?php
session_start();
include('../includes/dbconn.php');

if (!isset($_SESSION['sid'])) {
    echo "<script>alert('Please log in first.'); window.location='index2.php';</script>";
    exit();
}

$sid = $_SESSION['sid'];
$has_active_request = false;

// Check if there is an active outpass request
$check_query = "SELECT * FROM outpass_requests WHERE sid = '$sid' AND sstatus IN ('Pending', 'Approved') 
                AND CONCAT(return_date, ' ', return_time) > NOW()";
$check_result = mysqli_query($connection, $check_query);

if (mysqli_num_rows($check_result) > 0) {
    $has_active_request = true;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($has_active_request) {
        echo "<script>alert('You already have an active outpass request. You cannot apply again until it expires.'); window.location='outpass_status.php';</script>";
        exit();
    }

    $reason = mysqli_real_escape_string($connection, $_POST['reason']);
    $destination = mysqli_real_escape_string($connection, $_POST['destination']);
    $leave_date = $_POST['leave_date'];
    $return_date = $_POST['return_date'];
    $leave_time = $_POST['leave_time'];
    $return_time = $_POST['return_time'];

    $sql = "INSERT INTO outpass_requests (sid, name, room_no, reason, leave_date, return_date, leave_time, return_time, destination, sstatus)
            VALUES ('$sid', (SELECT name FROM students WHERE sid = '$sid'), 
                    (SELECT room_no FROM students WHERE sid = '$sid'), '$reason', '$leave_date', '$return_date', 
                    '$leave_time', '$return_time', '$destination', 'Pending')";
 if (mysqli_query($connection, $sql)) {
    $_SESSION['success_message'] = "Outpass request submitted successfully! $status";
    header("Location: outpass_status.php");
    exit();
} else {
    $_SESSION['error_message'] = "Error submitting request: " . mysqli_error($connection);
    header("Location: outpass_status.php");
    exit();
}
}

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
        .card { border-radius: 12px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 20px; background: white; }
        .section-title { font-size: 18px; font-weight: bold; color: #007bff; margin-bottom: 10px; border-bottom: 2px solid #007bff; padding-bottom: 5px; }
        .info-box { font-size: 16px; padding: 10px; background: #e9ecef; border-radius: 5px; margin-bottom: 10px; }
        .btn-apply { background-color: #007bff; border: none; font-size: 18px; padding: 12px; border-radius: 6px; width: 18%; color: white; cursor: pointer; transition: 0.3s; }
        .btn-apply:hover { background-color: #0056b3; }
        .form-control { background: #f8f9fa; border-radius: 5px; }
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

<div class="main content">
<div class="container mt-4">
    <div class="card">
        <h3 class="text-center" style="color:black;">Outpass Request</h3>
        <br>

        <?php if ($has_active_request): ?>
            <div class="alert alert-warning text-center">
                <strong>Note:</strong> You already have an active outpass request. You cannot apply again until it expires.
            </div>
            <div class="text-center">
                <a href="outpass_status.php" class="btn btn-primary">View Outpass Status</a>
            </div>
        <?php else: ?>
            <form method="POST">
                <div class="section-title" style="color:black;">Student Information</div>
                <div class="row">
                    <div class="col-md-4">
                        <label>Student ID:</label>
                        <div class="info-box"><?php echo htmlspecialchars($sid); ?></div>
                    </div>
                    <div class="col-md-4">
                        <label>Name:</label>
                        <div class="info-box"><?php echo htmlspecialchars($_SESSION['name'] ?? ''); ?></div>
                    </div>
                    <div class="col-md-4">
                        <label>Room No:</label>
                        <div class="info-box"><?php echo htmlspecialchars($_SESSION['room_no'] ?? ''); ?></div>
                    </div>
                </div>

                <div class="section-title" style="color:black;">Outpass Details</div>
                <div class="row">
                    <div class="col-md-6">
                        <label>Destination:</label>
                        <input type="text" name="destination" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>Reason:</label>
                        <input type="text" name="reason" class="form-control" required>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <label>Leave Date:</label>
                        <input type="date" name="leave_date" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>Return Date:</label>
                        <input type="date" name="return_date" class="form-control" required>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <label>Leave Time:</label>
                        <input type="time" name="leave_time" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>Return Time:</label>
                        <input type="time" name="return_time" class="form-control" required>
                    </div>
                </div>
                <hr>
                <center><button type="submit" class="btn-apply">Submit Request</button></center>
            </form>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
