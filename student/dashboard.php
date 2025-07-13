<?php 
session_start();
if(isset($_SESSION['email'])){
    include('../includes/dbconn.php');

    // Sanitize user email
    $email = mysqli_real_escape_string($connection, $_SESSION['email']);

    // Fetch student details
    $query2 = "SELECT sid, name FROM students WHERE email = '$email'";
    $query_run2 = mysqli_query($connection, $query2);
    
    // If student not found, destroy session and redirect
    if(mysqli_num_rows($query_run2) == 0) {
        session_destroy();
        header('Location: index2.php');
        exit();
    }

    $student = mysqli_fetch_assoc($query_run2);
    $sid = $student['sid'];
    $name = $student['name'];

    // Get Current Month & Year
    $currentMonth = date('F'); // Example: March
    $currentYear = date('Y');  // Example: 2025
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="../includes/jquery_latest.js"></script>
    <title>HMS</title>
</head>
    <style>
        th { text-transform: uppercase; font-weight: bold; }
        td { vertical-align: middle; }
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

<div class="main-content">
<nav class="">
        <h3>HOSTEL MANAGEMENT SYSTEM</h3>
    </nav>

<div class="container mt-5">
    <h4 class="mt-3">
        <span class="text-dark font-weight-bold">Welcome,</span> 
        <span class="text-primary font-weight-bold"><?php echo htmlspecialchars($name); ?> (ID: <?php echo htmlspecialchars($sid); ?>)</span>
    </h4>

    <h5 class="text-secondary mt-3"><?php echo $currentMonth . ' ' . $currentYear; ?></h5>
<hr>
    <!-- Attendance Table -->
    <table class="table table-bordered table-striped mt-3">
        <thead class="thead-dark">
            <tr>
                <th scope="col" class="text-center">S.No</th>
                <th scope="col">Date</th>
                <th scope="col">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $sno = 1;
            $query = "SELECT att.sid, att.status, att.date FROM attendance att WHERE att.sid = '$sid'";
            $query_run = mysqli_query($connection, $query);

            if(mysqli_num_rows($query_run) > 0) {
                while ($row = mysqli_fetch_assoc($query_run)) {
                    echo "<tr>
                            <td class='text-center'>{$sno}</td>
                            <td>{$row['date']}</td>
                            <td>" . ($row['status'] == 1 ? "<span class='text-success font-weight-bold'>Present</span>" : "<span class='text-danger font-weight-bold'>Absent</span>") . "</td>
                          </tr>";
                    $sno++;
                }
            } else {
                echo "<tr><td colspan='3' class='text-center text-muted'>No attendance records found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.9/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
<?php 
} else {
    header('Location: index.php');
    exit();
}
?>
