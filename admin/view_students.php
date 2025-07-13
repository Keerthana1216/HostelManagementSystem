<?php
session_start();
include('../includes/dbconn.php'); // Include database connection

if (!isset($_SESSION['email'])) {
    header('Location: ../index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <title>HMS</title>
</head>
<body style="background-color: #f8f9fa;">
<style>
    /* Desktop view styles */
    .main-content {
    margin-left: 260px; /* Same as sidebar width + margin */
    padding: 20px;
    transition: margin-left 0.3s;
}
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
.main-content {
    margin-left: 260px;
    padding: 20px;
    transition: all 0.3s ease;
}

.sidebar.closed {
    width: 0;
    overflow: hidden;
}

.main-content.expanded {
    margin-left: auto !important;
    margin-right: auto !important;
    width: 1000px;
    transition: all 0.3s ease;
}
</style>
<?php include('sidebar.php'); ?>
<br>
<div class="main-content" style="margin-left: 260px; padding: 20px;">
    <div class="">
        <h3>Students Status</h3>
        <hr>
        <!-- Display Success Message -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['success_message']; ?>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>SID</th>
                    <th>Name</th>
                    <th>Reg_no</th>
                    <th>Email ID</th>
                    <th>Dept</th>
                    <th>Batch</th>
                    <th>Year</th>
                    <th>Address</th>
                    <th>Contact No</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch students from database
                $query = "SELECT * FROM students ORDER BY sid ASC";
                $query_run = mysqli_query($connection, $query);

                if (!$query_run) {
                    die("Database query failed: " . mysqli_error($connection));
                }

                if (mysqli_num_rows($query_run) > 0) {
                    while ($row = mysqli_fetch_assoc($query_run)) {
                        echo "<tr>
                                <td>{$row['sid']}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['st_id']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['department']}</td>
                                <td>{$row['joining_year']}</td>
                                <td>{$row['current_year']}</td>
                                <td>{$row['address']}</td>
                                <td>{$row['mobile']}</td>
                                <td>
                                    <a href='edit_student.php?id={$row['sid']}' class='btn btn-info btn-sm'>E</a>
                                    <a href='delete_student.php?id={$row['sid']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>D</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='10' class='text-center'>No students found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<script>
     function toggleSidebar() {
    let sidebar = document.querySelector('.sidebar');
    let hmsTitle = document.getElementById('hms-title');
    let mainContent = document.querySelectorAll('.main-content');

    sidebar.classList.toggle('closed');

    if (sidebar.classList.contains('closed')) {
        hmsTitle.style.display = 'none';
        mainContent.forEach(el => el.classList.add('expanded'));
    } else {
        hmsTitle.style.display = 'block';
        mainContent.forEach(el => el.classList.remove('expanded'));
    }
}</script>
</body>
</html>
