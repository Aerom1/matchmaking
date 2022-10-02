
<?php

    function connectToBDD(){
        $query = "SELECT * FROM tbplayer LIMIT 1";
        $result = mysqli_query(OpenCon(), $query);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            debug_to_console($row);
            debug_to_console($row["xp"]);
            // $row = $result->fetch_assoc();
            // debug_to_console($row);
            // echo '<script>alert("' . $test . '")</script>';
        } else {
            debug_to_console("PAS DE RESULTAT");
            debug_to_console($result);
        }
        return "DFGHJKLM";
    }

    function OpenCon() {
        $servername = "localhost"; // 192.168.1.40 localhost
        $username = "root";
        $password = "";
        $db = "dbmatchmaking";
        $port = "3306";
        $conn = new mysqli($servername, $username, $password, $db) or die("Connect failed: %s\n". $conn -> error);
        return $conn;
    }
    function CloseCon($conn){
        $conn -> close(); 
    }

    function debug_to_console($data) 
    {
        $output = $data;
        if (is_array($output))
            $output = implode(',', $output);	
        echo "<script>console.log('==============================Debug Objects: " . $output . "' );</script>";
    }
    ?>
