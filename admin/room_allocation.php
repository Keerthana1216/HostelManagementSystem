<?php
session_start();
include('../includes/dbconn.php'); // Database connection

if (!isset($_SESSION['email'])) {
    header('Location: ../index.php');
    exit();
}

// Fetch total number of rooms
$total_rooms_query = "SELECT COUNT(*) AS total_rooms FROM rooms";
$total_rooms_result = mysqli_query($connection, $total_rooms_query);
$total_rooms = mysqli_fetch_assoc($total_rooms_result)['total_rooms'];

// Fetch total number of students
$total_students_query = "SELECT COUNT(*) AS total_students FROM students";
$total_students_result = mysqli_query($connection, $total_students_query);
$total_students = mysqli_fetch_assoc($total_students_result)['total_students'];

// Fetch rooms with student counts
$rooms_query = "SELECT r.room_no, COUNT(s.sid) AS student_count 
                FROM rooms r 
                LEFT JOIN students s ON r.room_no = s.room_no 
                GROUP BY r.room_no";
$rooms_result = mysqli_query($connection, $rooms_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <title>HMS</title>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            position: fixed;
            width: 250px;
            height: 100vh;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
        }
        .main-content {
            margin-left: 260px;
            padding: 20px;
            min-height: 100vh;
        }
        .room-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 15px;
            text-align: center;
        }
        .student-list {
            display: none; /* Hide student list initially */
            margin-top: 10px;
            text-align: left;
            background: rgba(255, 255, 255, 0.9);
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(5px);
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

<!-- Sidebar -->
<div class="sidebar">
    <?php include('sidebar.php'); ?>
</div>
<br>
<!-- Main Content -->
<div class="main-content">
    <div class="">
        <h3>Room Allocation</h3>
        <hr>

    <!-- Display Total Rooms & Total Students -->
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="alert alert-info text-center">Total Rooms: <strong><?php echo $total_rooms; ?></strong></div>
        </div>
        <div class="col-md-6">
            <div class="alert alert-success text-center">Total Students: <strong><?php echo $total_students; ?></strong></div>
        </div>
    </div>

    <!-- Display Rooms with Student List (Hidden Initially) -->
    <div class="row">
        <?php while ($room = mysqli_fetch_assoc($rooms_result)): ?>
            <div class="col-md-4">
                <div class="room-card">
                    <h5>Room No: <?php echo htmlspecialchars($room['room_no']); ?></h5>
                    <p>Students: <?php echo htmlspecialchars($room['student_count']); ?></p>

                    <button class="btn btn-primary btn-sm" onclick="toggleDetails('<?php echo $room['room_no']; ?>')">View</button>

                    <div id="students-<?php echo $room['room_no']; ?>" class="student-list">
                        <h6>Student List:</h6>
                        <?php
                        $students_query = "SELECT name FROM students WHERE room_no = '" . $room['room_no'] . "'";
                        $students_result = mysqli_query($connection, $students_query);
                        
                        if (mysqli_num_rows($students_result) > 0) {
                            while ($student = mysqli_fetch_assoc($students_result)) {
                                echo "<p>" . htmlspecialchars($student['name']) . "</p>";
                            }
                        } else {
                            echo "<p class='text-muted'>No students</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<script>
    function toggleDetails(roomNo) {
        var detailsDiv = document.getElementById("students-" + roomNo);
        if (detailsDiv.style.display === "none" || detailsDiv.style.display === "") {
            detailsDiv.style.display = "block";  // Show student list
        } else {
            detailsDiv.style.display = "none";   // Hide student list
        }
    }
</script>

</body>
</html>
