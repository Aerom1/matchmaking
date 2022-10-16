<?php

function recup_table_ENTIERE($conn, $req){
    $data = $conn->query($req)->fetch_all( MYSQLI_ASSOC );
    return json_encode($data,JSON_FORCE_OBJECT|JSON_UNESCAPED_UNICODE);
}

function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);	
    echo "<script>console.log('==============================PHP Debug: " . $output . "' );</script>";
}

// function connectToDB() {
//     $servername = "localhost"; // 192.168.1.40 localhost
//     $username = "root";
//     $password = "";
//     $db = "dbmatch2";
//     $conn = new mysqli($servername, $username, $password, $db) or die("Connect failed: %s\n". $conn -> error);
//     $conn->set_charset("utf8");
//     if ( mysqli_connect_errno() ) {
//         die("Connection failed: " . utf8_encode($conn->connect_error));
//         }
//     return $conn;
// }
?>
