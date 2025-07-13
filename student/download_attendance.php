<?php
session_start();
require_once('../tcpdf/tcpdf.php'); // Ensure correct path
include('../includes/dbconn.php');

if (!isset($_SESSION['email'])) {
    die("Unauthorized access!");
}

$email = mysqli_real_escape_string($connection, $_SESSION['email']);

// Fetch student details
$query = "SELECT sid, name FROM students WHERE email = '$email'";
$result = mysqli_query($connection, $query);

if (mysqli_num_rows($result) == 0) {
    die("Student not found");
}

$student = mysqli_fetch_assoc($result);
$sid = $student['sid'];
$name = $student['name'];

// Fetch attendance records
$query = "SELECT MONTH(date) as month, YEAR(date) as year, 
                 SUM(status = 1) as present_days, COUNT(*) as total_days 
          FROM attendance WHERE sid = '$sid' 
          GROUP BY YEAR(date), MONTH(date) 
          ORDER BY year DESC, month DESC";
$result = mysqli_query($connection, $query);

// Create PDF
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Hostel Management System');
$pdf->SetTitle('Attendance Report');
$pdf->SetMargins(10, 10, 10);
$pdf->AddPage();

// Title
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Attendance Report', 0, 1, 'C');

// Student Info
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 10, "Student: $name (ID: $sid)", 0, 1, 'L');
$pdf->Ln(5);

// Table Headers
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(10, 8, 'S.No', 1, 0, 'C');
$pdf->Cell(40, 8, 'Month', 1, 0, 'C');
$pdf->Cell(30, 8, 'Year', 1, 0, 'C');
$pdf->Cell(30, 8, 'Total Days', 1, 0, 'C');
$pdf->Cell(30, 8, 'Present Days', 1, 0, 'C');
$pdf->Cell(30, 8, 'Attendance %', 1, 1, 'C');

// Table Data
$pdf->SetFont('helvetica', '', 10);
$sno = 1;
while ($row = mysqli_fetch_assoc($result)) {
    $month_name = date('F', mktime(0, 0, 0, $row['month'], 10));
    $percentage = ($row['total_days'] > 0) ? round(($row['present_days'] / $row['total_days']) * 100, 2) : 0;

    $pdf->Cell(10, 8, $sno, 1, 0, 'C');
    $pdf->Cell(40, 8, $month_name, 1, 0, 'C');
    $pdf->Cell(30, 8, $row['year'], 1, 0, 'C');
    $pdf->Cell(30, 8, $row['total_days'], 1, 0, 'C');
    $pdf->Cell(30, 8, $row['present_days'], 1, 0, 'C');
    $pdf->Cell(30, 8, "$percentage%", 1, 1, 'C');
    $sno++;
}

// Output PDF
$pdf->Output("attendance_report_$sid.pdf", 'D'); // 'D' forces download
?>
