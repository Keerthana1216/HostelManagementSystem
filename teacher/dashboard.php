<?php 
session_start();
if (!isset($_SESSION['email']) || !isset($_SESSION['rid'])) {
    header('Location: index1.php');
    exit();
}

include('../includes/dbconn.php');

$rid = $_SESSION['rid']; // Secure session handling

// Fetch unique batch years
$batch_query = "SELECT DISTINCT joining_year FROM students ORDER BY joining_year DESC";
$batch_result = mysqli_query($connection, $batch_query);

// Fetch unique departments
$dept_query = "SELECT DISTINCT department FROM students ORDER BY department ASC";
$dept_result = mysqli_query($connection, $dept_query);

// Fetch students based on teacher's gender
$query = "
    SELECT DISTINCT std.st_id AS reg_no, 
       std.name AS name, 
       std.department AS department, 
       std.email AS email,
       std.joining_year AS joining_year,
       std.current_year AS current_year,
       std.address AS address,
       std.mobile AS mobile
FROM students AS std
INNER JOIN teachers AS tech 
    ON tech.gender = std.gender 
WHERE tech.rid = ?

";

$stmt = $connection->prepare($query);
$stmt->bind_param("i", $rid);
$stmt->execute();
$query_run = $stmt->get_result();

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

<body style="background-color: #f8f9fa;">
<?php include('topbar.php'); ?>

<div class="main-content">
    <div class="container mt-3">
        <h4>Student's Dashboard</h4>

        <!-- Filter Options -->
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="searchStudent">Search Student:</label>
                <input type="text" id="searchStudent" class="form-control" placeholder="Enter student name...">
            </div>
            <div class="col-md-4">
                <label for="batchFilter">Filter by Batch:</label>
                <select id="batchFilter" class="form-control">
                    <option value="">All</option>
                    <?php while ($batch_row = mysqli_fetch_assoc($batch_result)) { ?>
                        <option value="<?php echo $batch_row['joining_year']; ?>"><?php echo $batch_row['joining_year']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="deptFilter">Filter by Department:</label>
                <select id="deptFilter" class="form-control">
                    <option value="">All</option>
                    <?php while ($dept_row = mysqli_fetch_assoc($dept_result)) { ?>
                        <option value="<?php echo $dept_row['department']; ?>"><?php echo $dept_row['department']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
<hr>
        <table class="table table-striped mt-3" id="studentTable">
            <thead class="thead-dark">
                <tr>
                    <th>S.No</th>
                    <th>Name</th>
                    <th>Reg.No</th>
                    <th>Dept</th>
                    <th>Email ID</th>
                    <th>Batch</th>
                    <th>Year</th>
                    <th>Address</th>
                    <th>Contact No</th>
                </tr>
            </thead>
            <tbody>
    <?php
        $sno = 1;
        if ($query_run->num_rows > 0) { 
            while ($row = $query_run->fetch_assoc()) {
    ?>
    <tr class="student-row" 
        data-batch="<?php echo $row['joining_year']; ?>" 
        data-dept="<?php echo $row['department']; ?>">
        <td><?php echo $sno++; ?></td>
        <td><?php echo htmlspecialchars($row['name']); ?></td>
        <td><?php echo htmlspecialchars($row['reg_no']); ?></td>
        <td><?php echo htmlspecialchars($row['department']); ?></td>
        <td><?php echo htmlspecialchars($row['email']); ?></td>
        <td><?php echo htmlspecialchars($row['joining_year']); ?></td>
        <td><?php echo htmlspecialchars($row['current_year']); ?></td>
        <td><?php echo htmlspecialchars($row['address']); ?></td>
        <td><?php echo htmlspecialchars($row['mobile']); ?></td>
    </tr>
    <?php 
            } 
        } else { 
    ?>
    <tr>
        <td colspan="9" class="text-center text-danger">No data found</td>
    </tr>
    <?php } ?>
</tbody>

        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchStudent');
        const batchFilter = document.getElementById('batchFilter');
        const deptFilter = document.getElementById('deptFilter');
        const rows = document.querySelectorAll('.student-row');

        function filterTable() {
    let searchValue = searchInput.value.toLowerCase();
    let batchValue = batchFilter.value;
    let deptValue = deptFilter.value;
    let rows = document.querySelectorAll(".student-row");
    let hasVisibleRow = false;

    rows.forEach(row => {
        let name = row.cells[1].textContent.toLowerCase();
        let batch = row.getAttribute("data-batch");
        let dept = row.getAttribute("data-dept");

        let nameMatch = name.includes(searchValue);
        let batchMatch = batchValue === "" || batch === batchValue;
        let deptMatch = deptValue === "" || dept === deptValue;

        if (nameMatch && batchMatch && deptMatch) {
            row.style.display = "";
            hasVisibleRow = true;
        } else {
            row.style.display = "none";
        }
    });

    let noDataRow = document.getElementById("noDataRow");
    if (!hasVisibleRow) {
        if (!noDataRow) {
            let tableBody = document.querySelector("#studentTable tbody");
            let newRow = document.createElement("tr");
            newRow.id = "noDataRow";
            newRow.innerHTML = `<td colspan="9" class="text-center text-danger font-weight-bold">No data found</td>`;
            tableBody.appendChild(newRow);
        }
    } else {
        if (noDataRow) {
            noDataRow.remove();
        }
    }
}

searchInput.addEventListener('keyup', filterTable);
batchFilter.addEventListener('change', filterTable);
deptFilter.addEventListener('change', filterTable);

    });
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.9/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
