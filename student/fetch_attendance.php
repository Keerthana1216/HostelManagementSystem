<?php
include('../includes/dbconn.php');
session_start();

// Ensure `sid` is received properly
$sid = $_POST['sid'] ?? '';
$date_input = $_POST['date'] ?? ''; // Expected format: dd/mm/yy

// Validate `sid`
if (empty($sid)) {
    echo "<tr><td colspan='6' class='text-center text-danger'>Invalid Student ID.</td></tr>";
    exit;
}

// Convert `date_input` (dd/mm/yy) → `YYYY-MM-DD`
$formatted_date = '';
if (!empty($date_input)) {
    $date_parts = explode('/', $date_input); // Split dd/mm/yy
    if (count($date_parts) == 3) {
        $formatted_date = "20" . $date_parts[2] . "-" . $date_parts[1] . "-" . $date_parts[0]; // Convert to YYYY-MM-DD
    }
}

// Build SQL Query
$query = "SELECT MONTH(date) as month, YEAR(date) as year, 
                 SUM(status = 1) as present_days, 
                 COUNT(*) as total_days 
          FROM attendance 
          WHERE sid = ?";

$types = "i";
$params = [$sid];

if (!empty($formatted_date)) {
    $query .= " AND date = ?";
    $types .= "s";
    $params[] = $formatted_date;
}

$query .= " GROUP BY YEAR(date), MONTH(date) 
            ORDER BY year DESC, month DESC";

// Prepare and Execute Query
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, $types, ...$params);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$sno = 1;
$output = "";

while ($row = mysqli_fetch_assoc($result)) {
    $month_name = date('F', mktime(0, 0, 0, $row['month'], 10));
    $percentage = ($row['total_days'] > 0) ? round(($row['present_days'] / $row['total_days']) * 100, 2) : 0;

    $output .= "<tr>
                    <td>{$sno}</td>
                    <td>{$month_name}</td>
                    <td>{$row['year']}</td>
                    <td>{$row['total_days']}</td>
                    <td>{$row['present_days']}</td>
                    <td><span class='font-weight-bold'>{$percentage}%</span></td>
                </tr>";
    $sno++;
}

// If No Records Found
if ($sno == 1) {
    $output .= "<tr><td colspan='6' class='text-center text-muted'>No attendance records found</td></tr>";
}

echo $output;
?>
