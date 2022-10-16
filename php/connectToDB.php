<?php

// function connectToDB() {
    $servername = "localhost"; // 192.168.1.40 localhost
    $username = "root";
    $password = "";
    $db = "dbmatch2";
    $conn = new mysqli($servername, $username, $password, $db) or die("Connect failed: %s\n". $conn -> error);
    $conn->set_charset("utf8");
    if ( mysqli_connect_errno() ) {
        die("Connection failed: " . utf8_encode($conn->connect_error));
        }
    return $conn;


?>