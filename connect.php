<?php
$con=new mysqli('localhost','root','','db_capstone');
date_default_timezone_set('Asia/Manila');
if(!$con){
    die(mysqli_error($con));
}
?>