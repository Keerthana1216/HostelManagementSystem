<?php
include('../includes/dbconn.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sid = isset($_POST['sid']) ? trim($_POST['sid']) : '';
    $date = isset($_POST['date']) ? trim($_POST['date']) : '';

    if (empty($sid) || empty($date)) {
        echo "<tr><td colspan='3' class='text-center text-muted'>Invalid input.</td></tr>";
        exit();
    }

    // Fetch attendance for the selected date using sid
    $query = "SELECT sid, date, status FROM attendance WHERE sid = ? AND date = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "ss", $sid, $date);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['date']) . "</td>
                    <td>
                        <select class='form-control status-dropdown' data-sid='" . $row['sid'] . "'>
                            <option value='1' " . ($row['status'] == 1 ? "selected" : "") . ">Present</option>
                            <option value='0' " . ($row['status'] == 0 ? "selected" : "") . ">Absent</option>
                        </select>
                    </td>
                    <td>
                        <button class='btn btn-primary update-btn' data-sid='" . $row['sid'] . "'>Update</button>
                    </td>
                </tr>";
        }
    } else {
        echo "<tr><td colspan='3' class='text-center text-danger'>No attendance records found for this date.</td></tr>";
    }
}
?>
