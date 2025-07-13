<?php 
session_start();
include('../includes/dbconn.php');

if (!isset($_SESSION['email'])) {
    header('Location: ../index.php');
    exit();
}

// Get filters with security
$date = isset($_GET['date']) ? mysqli_real_escape_string($connection, $_GET['date']) : date('Y-m-d');
$status_filter = isset($_GET['status']) ? mysqli_real_escape_string($connection, $_GET['status']) : 'all';

// Base Query
$query = "SELECT std.sid, std.st_id AS reg_no, std.name, std.department, std.current_year, att.status 
          FROM students AS std 
          LEFT JOIN attendance AS att ON std.sid = att.sid AND att.date = '$date'";

// Apply filter conditions
if ($status_filter == 'present') {
    $query .= " WHERE att.status = 1";
} elseif ($status_filter == 'absent') {
    $query .= " WHERE att.status = 0";
} elseif ($status_filter == 'not_marked') {
    $query .= " WHERE att.status IS NULL";
}

$query_run = mysqli_query($connection, $query);
if (!$query_run) {
    die("Query failed: " . mysqli_error($conn));
}
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
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            margin: 0;
    padding: 0;
    display: flex;
        }
        .wrapper {
            display: flex;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #343a40;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
            transition: all 0.3s ease;
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

.sidebar.collapsed {
    width: 60px; /* or 0 if completely hidden */
}
.main-content {
    flex: 1;
    padding: 20px;
    transition: margin-left 0.3s ease;
}

.sidebar.collapsed + .main-content {
    margin-left: 60px; /* Match the collapsed width */
}

/* Optional full width on collapse */
@media (max-width: 768px) {
    .sidebar.collapsed + .main-content {
        margin-left: 0;
        width: 100%;
    }
}
        .main-content {
            margin-left: 230px;
            padding: 20px;
            width: calc(100% - 240px);
        }
        .table-responsive {
            overflow-x: auto;
        }
        .progress {
            margin-left: 50px;
            height: 24px;
            width: 90px;
        }
        .progress-bar.bg-success { color: white !important; }
        .progress-bar.bg-danger { color: white !important; }
        .progress-bar.bg-warning { color: white !important; }
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
@media (max-width: 768px) {
            .sidebar {
                width: 250px;
    background-color: #343a40;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    transition: transform 0.3s ease-in-out;
            }

            .sidebar.active {
                left: 0;
            }

            .main-content {
                margin-left: 0 !important;
            }

            #toggle-btn {
                display: block;
            }
        }

        #toggle-btn {
            font-size: 24px;
            cursor: pointer;
            display: none;
            margin: 10px;
        }
    </style>
</head>
<body>
<?php include('sidebar.php'); ?>
<div class="main-content">
<div class="container mt-4">
    <div class="d-flex align-items- justify-content-between flex-wrap">
        <h3 class="">Attendance Records</h3>

        <form method="GET" class="form-inline">
            <label for="date" class="mr-2 font-weight-bold">Select Date:</label>
            <input type="date" name="date" id="date" class="form-control mr-3" value="<?= $date; ?>">

            <label for="status" class="mr-2 font-weight-bold">Filter by:</label>
            <select name="status" id="status" class="form-control">
                <option value="all" <?= ($status_filter == 'all') ? 'selected' : ''; ?>>All</option>
                <option value="present" <?= ($status_filter == 'present') ? 'selected' : ''; ?>>Present</option>
                <option value="absent" <?= ($status_filter == 'absent') ? 'selected' : ''; ?>>Absent</option>
                <option value="not_marked" <?= ($status_filter == 'not_marked') ? 'selected' : ''; ?>>Not Marked</option>
            </select>
        </form>
    </div>
    <hr>
        <div class="row justify-content-center">
            <div class="container mt-2">
                <div class="table-responsive">
                    <table class="table table-striped mt-1 " style="width: 100%; margin: auto;">
                        <thead class="thead-dark">
                            <tr>
                                <th>S.No</th>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Reg.No</th>
                                <th>Dept</th>
                                <th>Year</th>
                                <th>Attendance Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $sno = 1;
                            $hasData = false;

                            while ($row = mysqli_fetch_assoc($query_run)) {
                                $hasData = true; // Data exists
                                $attendance_status = 'Not Marked';
                                $status_class = 'bg-warning'; 

                                if ($row['status'] == 1) {
                                    $attendance_status = 'Present';
                                    $status_class = 'bg-success';
                                } elseif ($row['status'] === "0") {
                                    $attendance_status = 'Absent';
                                    $status_class = 'bg-danger';
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
                                        <div class="progress">
                                            <div class="progress-bar <?php echo $status_class; ?>" style="width: 100%;">
                                                <?php echo $attendance_status; ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php 
                            } 

                            // Show "No Data Available" if no records found
                            if (!$hasData) {
                                echo '<tr><td colspan="7" class="text-center text-danger">No Data Available</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function toggleSidebar() {
        let sidebar = document.querySelector('.sidebar');
        let hmsTitle = document.getElementById('hms-title');
        
        sidebar.classList.toggle('closed');

        if (sidebar.classList.contains('closed')) {
            hmsTitle.style.display = 'none';
        } else {
            hmsTitle.style.display = 'block';
        }
    }
    $(document).ready(function(){
        $("#date, #status").change(function(){
            $(this).closest("form").submit(); 
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.9/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
