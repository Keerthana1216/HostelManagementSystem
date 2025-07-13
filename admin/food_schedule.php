<?php
session_start();
include('../includes/dbconn.php'); // Database connection

if (!isset($_SESSION['email'])) {
    header('Location: ../index.php');
    exit();
}

// Fetch food schedule
$sql = "SELECT * FROM food_schedule ORDER BY FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), FIELD(meal_type, 'Breakfast', 'Lunch', 'Dinner')";
$result = $connection->query($sql);

$schedule = [];
while ($row = $result->fetch_assoc()) {
    $schedule[$row['day']][$row['meal_type']] = $row['menu'];
}

$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>HMS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .wrapper {
            display: flex;
        }

        .sidebar {
            width: 250px; /* Sidebar width */
            height: 100vh;
            background-color: #343a40;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 10px;
            text-decoration: none;
        }

        .sidebar a:hover {
            background-color: #495057;
        }

        .main-content {
            margin-left: 260px; /* Push content to the right */
            padding: 20px;
            width: calc(100% - 240px); /* Ensure content is responsive */
        }

        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }

        .btn-edit {
            padding: 5px 10px;
            font-size: 14px;
        }

        .btn-add {
            margin: 20px 0;
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
    </style>
</head>
<body>
<br>
<div class="wrapper">
    <!-- Sidebar -->
    <div class="sidebar">
        <?php include('sidebar.php'); ?>
    </div>
    <br>
    <div class="main-content">
    <div class="">
        <h3>Food Schedule</h3>
        <hr>
        
        <table class="table table-bordered text-center">
            <thead class="thead-dark">
                <tr>
                    <th>Day</th>
                    <th>Breakfast (8:00 AM)</th>
                    <th>Lunch (12:30 PM)</th>
                    <th>Dinner (9:00 PM)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($days as $day): ?>
                    <tr>
                        <td><strong><?php echo $day; ?></strong></td>
                        <?php foreach (['Breakfast', 'Lunch', 'Dinner'] as $meal): ?>
                            <td>
                                <?php 
                                if (isset($schedule[$day][$meal])) {
                                    echo $schedule[$day][$meal];
                                } else {
                                    echo "<span class='text-muted'>Not Set</span>";
                                }
                                ?>
                            </td>
                        <?php endforeach; ?>
                        <td><a href="edit_food_schedule.php?day=<?php echo urlencode($day); ?>" class="btn btn-sm btn-primary btn-edit">E</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="text-center">
            <a href="add_new_schedule.php" class="btn btn-success btn-add">Add New Schedule</a>
        </div>
    </div>
</div>

</body>
</html>