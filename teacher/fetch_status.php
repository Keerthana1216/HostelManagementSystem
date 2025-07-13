<?php 
    include('../includes/dbconn.php');
    $timestamp = time();
    $date = gmdate('Y-m-d',$timestamp);
    $query = "select status from attendance where sid = '$_POST[sid]' and date = '$date'";
    $query_run = mysqli_query($connection,$query);
    while($row=mysqli_fetch_assoc($query_run)){
        echo $row['status'];
    }
?>