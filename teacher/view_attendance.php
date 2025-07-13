<?php 
session_start();
if(isset($_SESSION['email'])) {
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
    }
    .wrapper {
        display: flex;
    }
    .table-responsive {
        overflow-x: auto;
    }
    .main-content {
        margin-left: 200px;
        padding: 20px;
        width: calc(100% - 230px);
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
    .progress {
            margin-left: 50px;
            height: 24px;
            width: 80px;
        }
        .progress-bar.bg-success { color: white !important; }
        .progress-bar.bg-danger { color: white !important; }
        .progress-bar.bg-warning { color: white !important; }
    
</style>
</head>

<body style="background-color: #f8f9fa;">
<?php include('topbar.php'); ?>
<br>
 <br>   
<div class="main-container">
<div class="container mt-4">
    <div class="d-flex align-items-center justify-content-between flex-wrap">
        <h4 class="mb-0">Attendance Records</h4>
        
        <form method="GET" id="attendanceForm" class="d-flex align-items-center">
            <label for="date" class="mr-1">Select Date: </label>
            <input type="date" name="date" id="date" class="form-control w-auto" 
                value="<?php echo isset($_GET['date']) ? $_GET['date'] : date('Y-m-d'); ?>">

            <label for="status" class="ml-3 mr-1">Filter By: </label>
            <select name="status" id="status" class="form-control w-auto">
                <option value="all" <?php echo (!isset($_GET['status']) || $_GET['status'] == 'all') ? 'selected' : ''; ?>>All</option>
                <option value="present" <?php echo (isset($_GET['status']) && $_GET['status'] == 'present') ? 'selected' : ''; ?>>Present</option>
                <option value="absent" <?php echo (isset($_GET['status']) && $_GET['status'] == 'absent') ? 'selected' : ''; ?>>Absent</option>
                <option value="notmarked" <?php echo (isset($_GET['status']) && $_GET['status'] == 'notmarked') ? 'selected' : ''; ?>>Not Marked</option>
            </select>
        </form>
    </div>
    <hr>
</div>

    <div class="row justify-content-center">
        <?php 
            include('../includes/dbconn.php');
            $rid = mysqli_real_escape_string($connection, $_SESSION['rid']);
            
            $date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
            $status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';

            $attendance_query = "SELECT sid, status FROM attendance WHERE date = '$date'";
            $attendance_result = mysqli_query($connection, $attendance_query);
            $attendance_data = [];
            while ($att_row = mysqli_fetch_assoc($attendance_result)) {
                $attendance_data[$att_row['sid']] = $att_row['status'];
            }

            $query = "
            SELECT 
                std.sid AS sid, 
                std.st_id AS reg_no, 
                std.name AS name, 
                std.department AS department, 
                std.current_year AS current_year 
            FROM students AS std
            INNER JOIN teachers AS tech 
                ON tech.gender = std.gender
            WHERE tech.rid = '$rid'
            ORDER BY std.sid ASC";  // Ordering by sid in ascending order
        

            $query_run = mysqli_query($connection, $query);
            $has_data = false;
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
                        <th>Attendance Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                $sno = 1;
                while ($row = mysqli_fetch_assoc($query_run)) {
                    $sid = $row['sid'];

                    if (isset($attendance_data[$sid])) {
                        $attendance_status = $attendance_data[$sid] == 1 ? 'Present' : 'Absent';
                        $status_class = $attendance_data[$sid] == 1 ? 'bg-success' : 'bg-danger';
                    } else {
                        $attendance_status = 'Not Marked';
                        $status_class = 'bg-warning';
                    }

                    if ($status_filter == 'present' && $attendance_status != 'Present') continue;
                    if ($status_filter == 'absent' && $attendance_status != 'Absent') continue;
                    if ($status_filter == 'notmarked' && $attendance_status != 'Not Marked') continue;

                    $has_data = true;
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
                ?>
                </tbody>
            </table>

            <?php 
            if (!$has_data) {
                echo '<p class="text-center text-danger">No data available</p>';
            }
            ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $("#date, #status").change(function(){
            $("#attendanceForm").submit(); // Auto-submit on date or status change
        });
    });
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.9/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
<?php 
} else {
  header('Location:../index1.php');
}
?>
