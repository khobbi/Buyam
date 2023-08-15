<?php 
    //variables
    $hostname = "localhost";
    $username = "root";
    $password = "e+00h@+8";
    $dbName = "db_buyam";

    //create connection
    $conn = new mysqli($hostname, $username, $password, $dbName);

    //test connection
    if($conn->connect_error){
        die("Connection failed: " . $con->connect_error . "<br>");
    }
?>