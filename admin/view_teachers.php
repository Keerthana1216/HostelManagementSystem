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

/* Mobile view styles */
@media (max-width: 768px) {
    .sidebar {
        width: 100%;
        position: relative;
        z-index: 1000;
    }

    .sidebar.closed {
        display: none;
    }

    .main-content {
        margin-left: 0 !important;
        width: 100% !important;
        padding: 15px;
    }

    .main-content.expanded {
        width: 100% !important;
        margin: 0 auto !important;
    }
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
}</style>

<?php include('sidebar.php'); ?>
<br>
<div class="main-content" style="margin-left: 260px; padding: 20px;">
    <div class="">
        <h3>Faculty Status</h3>
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
                    <th>RID</th>
                    <th>RT Name</th>
                    <th>Email ID</th>
                    <th>Gender</th>
                    <th>Contact No</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch faculty from database
                $query = "SELECT * FROM teachers ORDER BY rid ASC";
                $query_run = mysqli_query($connection, $query);

                if (!$query_run) {
                    die("Database query failed: " . mysqli_error($connection));
                }

                if (mysqli_num_rows($query_run) > 0) {
                    while ($row = mysqli_fetch_assoc($query_run)) {
                        echo "<tr>
                                <td>{$row['rid']}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['gender']}</td>
                                <td>{$row['mobile']}</td>                               
                                <td>
                                    <a href='edit_teacher.php?id={$row['rid']}' class='btn btn-info btn-sm'>E</a>
                                    <a href='delete_teacher.php?id={$row['rid']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this faculty?\")'>D</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8' class='text-center'>No faculty found.</td></tr>";
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
