<?php 
session_start();
if(isset($_SESSION['email'])){
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="../includes/jquery_latest.js"></script>
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
        }

        /* Align title and date in a straight line */
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px; /* Ensure there's enough space below the topbar */
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

<body style="background-color: #f8f9fa;">
<?php include('topbar.php'); ?>

    <div class="container mt-4">
        <div class="header-container">
            <h4>Mark Today's Attendance</h4>
            <span>Date: <?php echo date('d-m-Y'); ?></span>
        </div>
        <hr>

        <div class="row justify-content-center">
            <?php 
            include('../includes/dbconn.php');
            $rid = mysqli_real_escape_string($connection, $_SESSION['rid']);
            
            $query = "SELECT std.sid, std.st_id AS reg_no, std.name, std.department, std.current_year FROM students AS std INNER JOIN teachers AS tech ON tech.gender = std.gender WHERE tech.rid = '$rid'";
            $query_run = mysqli_query($connection, $query);
            
            if (!$query_run) {
                die("Query failed: " . mysqli_error($connection));
            }
            ?>
            <div class="container mt-1">
                <table class="table table-striped mt-2">
                    <thead class="thead-dark">
                        <tr>
                            <th>S.No</th>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Reg.No</th>
                            <th>Dept</th>
                            <th>Year</th>
                            <th>Attendance</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $sno = 1;
                        while ($row = mysqli_fetch_assoc($query_run)) {
                            $sid = mysqli_real_escape_string($connection, $row['sid']);
                            $date = date('Y-m-d');

                            $query1 = "SELECT status FROM attendance WHERE sid = '$sid' AND date = '$date'";
                            $query_run1 = mysqli_query($connection, $query1);
                            $attendance_status = 'Not Marked';
                            
                            if ($query_run1 && mysqli_num_rows($query_run1) > 0) {
                                $attendance_data = mysqli_fetch_assoc($query_run1);
                                $attendance_status = $attendance_data['status'] == 1 ? 'Present' : 'Absent';
                            }
                        ?>
                        <tr>
                            <td><?php echo $sno++; ?></td>
                            <td><?php echo htmlspecialchars($row['sid']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['reg_no']); ?></td>
                            <td><?php echo htmlspecialchars($row['department']); ?></td>
                            <td><?php echo htmlspecialchars($row['current_year']); ?></td>
                            <td>
                                <?php if ($attendance_status == 'Not Marked') { ?>
                                    <form method="POST" action="mark_attendance.php" style="display:inline;">
                                        <input type="hidden" name="sid" value="<?php echo $row['sid']; ?>">
                                        <input type="hidden" name="status" value="1">
                                        <button type="submit" class="btn btn-success btn-sm">Present</button>
                                    </form>
                                    <form method="POST" action="mark_attendance.php" style="display:inline;">
                                        <input type="hidden" name="sid" value="<?php echo $row['sid']; ?>">
                                        <input type="hidden" name="status" value="0">
                                        <button type="submit" class="btn btn-danger btn-sm">Absent</button>
                                    </form>
                                <?php } else { echo $attendance_status; } ?>
                            </td>
                            <td>
                                <a href="edit_attendance.php?sid=<?php echo $row['sid']; ?>" class="btn btn-primary btn-sm">E</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.9/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
<?php } else { header('Location:../index.php'); } ?>
