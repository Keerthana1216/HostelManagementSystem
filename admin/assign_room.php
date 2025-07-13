<?php
session_start();
include('../includes/dbconn.php'); // Database connection

if (!isset($_SESSION['email'])) {
    header('Location: ../index.php');
    exit();
}

// Check database connection
if (!$connection) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

// Fetch students without assigned rooms
$students_without_rooms_query = "SELECT sid, name FROM students WHERE room_no IS NULL OR room_no = '' OR room_no NOT IN (SELECT room_no FROM rooms)";
$students_without_rooms_result = mysqli_query($connection, $students_without_rooms_query) or die("Query Failed: " . mysqli_error($connection));

// Fetch all rooms with capacity and current student count
$rooms_query = "SELECT r.room_no, r.capacity, COUNT(s.sid) AS student_count 
                FROM rooms r 
                LEFT JOIN students s ON r.room_no = s.room_no 
                GROUP BY r.room_no";
$rooms_result = mysqli_query($connection, $rooms_query) or die("Query Failed: " . mysqli_error($connection));

// Fetch all students with assigned rooms
$students_query = "SELECT s.sid, s.name, s.room_no, r.capacity, 
                  (SELECT COUNT(*) FROM students WHERE room_no = s.room_no) AS student_count 
                   FROM students s 
                   LEFT JOIN rooms r ON s.room_no = r.room_no";
$students_result = mysqli_query($connection, $students_query) or die("Query Failed: " . mysqli_error($connection));

$room_message = "";

// Assign or Update Room
if (isset($_POST['assign_room'])) {
    $student_id = $_POST['student_id'];
    $room_no = $_POST['room_no'];

    // Get room capacity
    $capacity_query = "SELECT capacity FROM rooms WHERE room_no = '$room_no'";
    $capacity_result = mysqli_query($connection, $capacity_query) or die("Query Failed: " . mysqli_error($connection));
    $room_capacity = mysqli_fetch_assoc($capacity_result)['capacity'];

    // Count students in the room
    $student_count_query = "SELECT COUNT(*) AS student_count FROM students WHERE room_no = '$room_no'";
    $student_count_result = mysqli_query($connection, $student_count_query) or die("Query Failed: " . mysqli_error($connection));
    $current_students = mysqli_fetch_assoc($student_count_result)['student_count'];

    if ($current_students >= $room_capacity) {
        $room_message = "<div class='alert alert-danger'>Room is unavailable! Maximum capacity reached.</div>";
    } else {
        $assign_query = "UPDATE students SET room_no = '$room_no' WHERE sid = '$student_id'";
        if (mysqli_query($connection, $assign_query)) {
            $room_message = "<div class='alert alert-success'>Room assigned successfully!</div>";
        } else {
            $room_message = "<div class='alert alert-danger'>Room assignment failed: " . mysqli_error($connection) . "</div>";
        }
    }
}
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
            margin-left: 260px;
            padding: 20px;
            min-height: 100vh;
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

<!-- Main Content -->
<div class="main-content">
    <br>
    <div class="">
        <h3>Assign Rooms</h3>
        <hr>

    <!-- Display Success/Failure Message -->
    <?php if (!empty($room_message)) echo $room_message; ?>

    <form method="POST">
        <div class="form-group">
            <label for="student_id">Select Student:</label>
            <select name="student_id" id="student_id" class="form-control" required>
                <option value="">--Select--</option>
                <?php while ($student = mysqli_fetch_assoc($students_without_rooms_result)): ?>
                    <option value="<?php echo $student['sid']; ?>"> <?php echo $student['name']; ?> </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="room_no">Select Room:</label>
            <select name="room_no" id="room_no" class="form-control" required>
                <option value="">--Select--</option>
                <?php 
                mysqli_data_seek($rooms_result, 0); // Reset result set for reuse
                while ($room = mysqli_fetch_assoc($rooms_result)): ?>
                    <option value="<?php echo $room['room_no']; ?>" 
                            data-capacity="<?php echo $room['capacity']; ?>" 
                            data-occupied="<?php echo $room['student_count']; ?>">
                        <?php echo $room['room_no']; ?> (Capacity: <?php echo $room['capacity']; ?>, Occupied: <?php echo $room['student_count']; ?>)
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div id="room_alert" class="alert alert-danger" style="display: none;"></div>
        <center><button type="submit" name="assign_room" class="btn btn-success">Assign Room</button></center>
    </form>

    <hr>
    <br>
    <h4 class="text-center">Students & Assigned Rooms</h4>
    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Student Name</th>
                <th>Assigned Room</th>
                <th>Capacity</th>
                <th>Occupied</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($student = mysqli_fetch_assoc($students_result)): ?>
                <tr>
                    <td><?php echo $student['name']; ?></td>
                    <td><?php echo $student['room_no'] ? $student['room_no'] : "Not Assigned"; ?></td>
                    <td><?php echo $student['capacity'] ? $student['capacity'] : "-"; ?></td>
                    <td><?php echo $student['student_count']; ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="student_id" value="<?php echo $student['sid']; ?>">
                            <select name="room_no" class="form-control" required>
                                <option value="">-- Change Room --</option>
                                <?php 
                                mysqli_data_seek($rooms_result, 0);
                                while ($room = mysqli_fetch_assoc($rooms_result)): ?>
                                    <option value="<?php echo $room['room_no']; ?>"><?php echo $room['room_no']; ?></option>
                                <?php endwhile; ?>
                            </select>
                            <button type="submit" name="assign_room" class="btn btn-primary btn-sm mt-1">Update</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $("#room_no").change(function () {
            var selectedOption = $(this).find(":selected");
            var roomCapacity = selectedOption.data("capacity");
            var studentsInRoom = selectedOption.data("occupied");

            if (studentsInRoom >= roomCapacity) {
                $("#room_alert").html("Room is unavailable! Maximum capacity reached.").show();
            } else {
                $("#room_alert").hide();
            }
        });
    });
</script>

</body>
</html>
