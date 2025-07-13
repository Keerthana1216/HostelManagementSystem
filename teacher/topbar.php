<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require('../includes/dbconn.php'); // Make sure the path is correct based on your project structure

$current_page = basename($_SERVER['PHP_SELF']);
$unread_count = 0;

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT COUNT(*) AS unread_count FROM messages WHERE receiver_id = ? AND is_read = 0");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $unread_count = $row['unread_count'];
}
?>

<!-- Navbar Starts-->
<nav class="navbar navbar-expand-lg navbar-light fixed-top topbar">
    <a class="navbar-brand font-weight" href="index.php">HOSTEL MANAGEMENT SYSTEM</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item <?= ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
                <a class="nav-link" href="dashboard.php">Dashboard</a>
            </li>
            <li class="nav-item <?= ($current_page == 'attendance.php') ? 'active' : ''; ?>">
                <a class="nav-link" href="attendance.php">Attendance</a>
            </li>
            <li class="nav-item <?= ($current_page == 'view_attendance.php') ? 'active' : ''; ?>">
                <a class="nav-link" href="view_attendance.php">View Attendance</a>
            </li>

            <!-- Notification Icon -->
            <li class="nav-item">
                <a class="nav-link position-relative" href="teacher_outpass_requests.php">
                    <i class="fas fa-user-tie"></i> 
                    <span id="notif-badge" class="badge badge-danger position-absolute" style="top: -5px; color: black; right: -5px;"></span>
                </a>
            </li>

            <!-- Notification Icon -->

            <li class="nav-item position-relative">
    <a class="nav-link position-relative" href="faculty_outpass_requests.php">
        <i class="fa-solid fa-bell" style="top: -5px; color: black; font-size: 22px; right: -5px;"></i>
        <?php if ($unread_count > 0): ?>
            <span id="notif-badge" class="position-absolute badge badge-danger">
                <?= $unread_count ?>
            </span>
        <?php endif; ?>
    </a>
</li>
            <!-- Logout Icon -->
            <li class="nav-item">
    <a class="nav-link font-weight-bold" href="../logout.php">
        <i class="fa-solid fa-right-from-bracket" style="color: black;"></i>
    </a>
</li>
        </ul>
    </div>
</nav>
<!-- NavBar Ends -->

<!-- CSS for Styling -->
<style>
.topbar {
    background-color: #ffffff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    padding: 10px 20px;
}
.navbar-brand {
    font-size: 20px;
    font-weight: bold;
    color: #333;
}
.navbar-nav .nav-item .nav-link {
    font-size: 16px;
    font-weight: 500;
    color: #555;
    transition: color 0.3s ease;
}
.navbar-nav .nav-item.active .nav-link,
.navbar-nav .nav-item:hover .nav-link {
    color: #007bff; /* Highlight active link */
}
.navbar-nav .nav-item .fa-bell, 
.navbar-nav .nav-item .fa-right-from-bracket {
    font-size: 22px;
}
.badge-danger {
    background-color: red;
    color: white;
    font-size: 12px;
    padding: 3px 6px;
    border-radius: 50%;
}
#notif-badge {
    top: 0;
    right: 0;
    font-size: 12px;
    padding: 3px 6px;
    border-radius: 50%;
    transform: translate(50%, -50%);
}
.nav-item.position-relative {
    position: relative;
}
</style>

<!-- Font Awesome for Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<script>
    $(document).ready(function () {
        // Simulate the message count dynamically (you could replace this with an API or a database value)
        let notifCount = getMessageCount(); // This function should return the actual message count

        // Update the notification badge based on the count
        if (notifCount > 0) {
            $('#notif-badge').text(notifCount).show();
        } else {
            $('#notif-badge').hide();
        }
    });

    // Function to simulate fetching the message count
    function getMessageCount() {
        // Replace this with actual logic to get the message count
        return 3; // Example: Return 5 messages for now
    }
</script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<?php if ($unread_count > 0): ?>
    <span id="notif-badge" class="position-absolute badge badge-danger">
        <?= $unread_count ?>
    </span>
<?php endif; ?>
