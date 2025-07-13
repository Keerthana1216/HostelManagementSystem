<?php 
include('../includes/dbconn.php');

if(isset($_GET['gender'])) {
    $gender = mysqli_real_escape_string($connection, $_GET['gender']);
    
    // Fetch students sorted in ascending order by SID
    $query = "SELECT sid, name FROM students WHERE gender = '$gender' ORDER BY sid ASC";
    $result = mysqli_query($connection, $query);

    // Check if query execution was successful
    if (!$result) {
        die("<h4 class='text-danger text-center'>Error fetching data: " . mysqli_error($connection) . "</h4>");
    }
?>
<br>

    <div class="row justify-content-center">
        <div class="col-md-7">
                <div class="card-body">
                    <?php if(mysqli_num_rows($result) > 0): ?>
                        <table class="table table-hover table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?php echo $row['sid']; ?></td>
                                        <td><?php echo $row['name']; ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="text-center text-danger">No students found!</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 12px;
        border: none;
    }
    .card-header {
        font-size: 12px;
        font-weight: normal;
        border-radius: 12px 12px 0 0;
    }
    .table {
        border-radius: 10px;
        overflow: hidden;
    }
    .table th, .table td {
        text-align: center;
        padding: 10px;
    }
    .table tbody tr:hover {
        background-color: #f1f1f1;
    }
</style>

<?php
} else {
    echo "<h4 class='text-danger text-center'>Invalid Request</h4>";
}
?>
