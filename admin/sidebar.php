<?php
$current_page = basename($_SERVER['PHP_SELF']); // Get the current file name
?>
<div class="sidebar">           
<div class="sidebar-overlay" onclick="toggleSidebar()"></div>
    <div class="sidebar-header">
        <h4 class="text-center text-white" id="hms-title">HMS</h4>
        <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
    </div>
    <br>

    <a href="dashboard.php">
        <i class="fas fa-tachometer-alt"></i> Dashboard
    </a>

    <!-- Attendance Dropdown -->
    <a href="#" class="dropdown-toggle" data-menu="attendance-menu">
        <i class="fas fa-clipboard-list"></i> Attendance Record <i class="fas fa- dropdown-icon"></i>
    </a>
    <div id="attendance-menu" class="dropdown-container">
        <a href="view_attendance.php">
            <i class="fas fa-tachometer-alt"></i>Overall Report
        </a>
        <a href="roomwise_attendance.php">
            <i class="fas fa-users"></i>Roomwise Report
        </a>
    </div>

    <!-- Student Dropdown -->
    <a href="#" class="dropdown-toggle" data-menu="student-menu">
        <i class="fas fa-user-graduate"></i> Student <i class="fas fa- dropdown-icon"></i>
    </a>
    <div id="student-menu" class="dropdown-container">
        <a href="add_student.php">
            <i class="fas fa-user-plus"></i> New Student
        </a>
        <a href="view_students.php">
            <i class="fas fa-users"></i> Student Status
        </a>
    </div>

    <!-- Faculty Dropdown -->
    <a href="#" class="dropdown-toggle" data-menu="faculty-menu">
        <i class="fas fa-chalkboard-teacher"></i> Faculty <i class="fas fa- dropdown-icon"></i>
    </a>
    <div id="faculty-menu" class="dropdown-container">
        <a href="add_teacher.php">
            <i class="fas fa-user-tie"></i> New Faculty
        </a>
        <a href="view_teachers.php">
            <i class="fas fa-user-check"></i> Faculty Status
        </a>
    </div>

     <!-- Meal Dropdown -->
     <a href="#" class="dropdown-toggle" data-menu="meal-menu">
        <i class="fas fa-door-open"></i>Manage Room <i class="fas fa- dropdown-icon"></i>
    </a>
    <div id="meal-menu" class="dropdown-container">
        <a href="room_allocation.php">
            <i class="fas fa-user-plus"></i>Manage Room
        </a>
        <a href="assign_room.php">
            <i class="fas fa-users"></i>Assigning Room
        </a>
    </div>

    <a href="food_schedule.php">
        <i class="fas fa-utensils"></i> Meal Manage
    </a>

    <a href="admin_outpass_requests.php">
        <i class="fas fa-external-link-alt"></i> Hod Approval
    </a>
    <a href="principal_outpass_requests.php">
        <i class="fas fa-external-link-alt"></i> Principal Approval
    </a>

    <a href="../logout.php">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a>
</div>
<!-- Sidebar Styles -->
<style>
.sidebar {
    height: 100%;
    width: 250px;
    position: fixed;
    top: 0;
    left: 0;
    background-color: #343a40;
    padding-top: 30px;
    transition: width 0.3s ease-in-out;
    z-index: 999;
}

.sidebar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 15px;
}

.toggle-btn {
    background: none;
    border: none;
    font-size: 23px;
    color: white;
    cursor: pointer;
    transition: transform 0.3s;
}

.toggle-btn:hover {
    transform: scale(1.2);
}

.sidebar a {
    display: flex;
    align-items: center;
    gap: 20px;
    color: white;
    padding: 12px 15px;
    text-decoration: none;
    font-size: 16px;
    transition: all 0.3s;
    margin-bottom: 20px; 
}

.sidebar a i {
    width: 20px;
}

.sidebar a:hover {
    background-color: #575757;
    padding-left: 20px;
}

/* Dropdown Styling */
.dropdown-container {
    max-height: 0;
    overflow: hidden;
    padding-left: 20px;
    background-color: #464a4e;
    transition: max-height 0.3s ease-in-out, padding 0.3s ease-in-out;
}

.dropdown-container.open {
    max-height: 200px;
    padding-top: 10px;
    padding-bottom: 10px;
}

.dropdown-container a {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #ddd;
    padding: 10px 15px;
    text-decoration: none;
    font-size: 14px;
    transition: 0.3s;
    margin-bottom: 12px; 
}

.dropdown-container a:hover {
    background-color: #575757;
}

.dropdown-icon {
    margin-left: auto;
    transition: transform 0.3s ease-in-out;
}

/* Sidebar Collapse */
.sidebar.closed {
    width: 60px;
}

.sidebar.closed a {
    justify-content: center;
    font-size: 0;
}

.sidebar.closed a i {
    font-size: 18px;
}

.sidebar.closed #hms-title {
    display: none;
}

/* Responsive Layout */
@media (max-width: 768px) {
    .sidebar {
        width: 100px;
    }

    .sidebar.closed {
        width: 40px;
    }

    .sidebar a {
        justify-content: center;
        font-size: 0;
    }

    .sidebar a i {
        font-size: 18px;
    }

    .sidebar .toggle-btn {
        text-align: right;
        width: 100%;
    }
    
    /* Example - adjust based on your actual class name */
.toggle-btn {
    margin-top: -15px; /* adjust the value as needed */
    position: relative;
    z-index: 10;
}

    #hms-title {
        display: none;
    }

    .main-content {
        margin-left: 60px;
        padding: 15px;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }
}

/* Optional for main content if not already in your code */
.main-content {
    margin-left: 250px;
    transition: margin-left 0.3s ease-in-out;
    padding: 20px;
}

.sidebar.closed ~ .main-content {
    margin-left: 60px;
}
/* Hide sidebar on mobile by default */
@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
        width: 250px;
        position: fixed;
        transition: transform 0.3s ease-in-out;
    }

    .sidebar.active {
        transform: translateX(0);
    }

    .sidebar .toggle-btn {
        position: absolute;
        left: 100%;
        top: 15px;
        background-color: #343a40;
        padding: 8px 12px;
        border-radius: 5px 0 0 5px;
    }

    #hms-title {
        display: none;
    }

    .sidebar.closed a,
    .sidebar.closed .dropdown-container {
        display: none !important;
    }

    .main-content {
        margin-left: 0 !important;
    }
}

</style>

<!-- Sidebar Script -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const dropdownLinks = document.querySelectorAll(".dropdown-toggle");

        dropdownLinks.forEach((link) => {
            link.addEventListener("click", function (event) {
                event.preventDefault();
                let menuId = this.getAttribute("data-menu");
                let menu = document.getElementById(menuId);
                let icon = this.querySelector(".dropdown-icon");

                if (menu.classList.contains("open")) {
                    menu.classList.remove("open");
                    icon.style.transform = "rotate(0deg)";
                } else {
                    document.querySelectorAll(".dropdown-container").forEach((m) => {
                        if (m.id !== menuId) {
                            m.classList.remove("open");
                        }
                    });

                    document.querySelectorAll(".dropdown-icon").forEach((i) => {
                        if (i !== icon) {
                            i.style.transform = "rotate(0deg)";
                        }
                    });

                    menu.classList.add("open");
                    icon.style.transform = "rotate(180deg)";
                }
            });
        });
    });
    function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.sidebar-overlay');

    sidebar.classList.toggle('active');

    if (sidebar.classList.contains('active')) {
        overlay.style.display = 'block';
    } else {
        overlay.style.display = 'none';
    }
}
</script>

<!-- Font Awesome for Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
