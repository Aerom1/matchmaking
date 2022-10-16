<!DOCTYPE html>
<html lang="en">
<head>
    <!-- <meta charset="utf8_general_ci"> -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        $db = "dbmatch2";
        echo "Salut ".$db."<br/>";
        $conn = connectToDB($db);
        
        pascontent($conn, "SELECT * FROM tbteam");
        pascontent($conn, "SELECT * FROM tbplayer");

        // $cpt=0;
        // $qt = $conn->query("SELECT * FROM tbteam");
        // // $qt1 = $qt->fetch_all( MYSQLI_ASSOC );
        // echo "<br/><br/>size team:".count($qt)."<br/>";
        // // $qt2 = json_encode($qt1);
        // // echo $qt2;
        // while ($row = $qt->fetch_assoc()) {
        //     echo "<br/>ligne ".$cpt++." : ".json_encode($row);
        // }

        // $qp = $conn->query("SELECT * FROM tbplayer");
        // $qp1 = $qp->fetch_all( MYSQLI_ASSOC );
        // echo "<br/><br/>size player:".count($qt2)."<br/>";
        // $qp2 = json_encode($qp1);
        // echo $qp2;

        function pascontent($conn, $req){
            $cpt=0;
            $qt = $conn->query($req);
            // $qt1 = $qt->fetch_all( MYSQLI_ASSOC );
            echo "<br/><br/>size: ".count($qt)."<br/>";
            // $qt2 = json_encode($qt1);
            // echo $qt2;
            while ($row = $qt->fetch_assoc()) {
                echo "<br/>ligne ".$cpt++." : ".json_encode($row);
            }

        }


		// $teams = getTable($conn, "SELECT * FROM tbteam");
		// $players = getTable($conn, "SELECT * FROM tbplayer"); 


        // echo $players;
        // echo "<br/>";

        // echo $teams;

        // $teams2 = json_encode($teams);
		// $players2 = json_encode($players);
        // echo $players2;
		// echo "<script>";
		// echo "var jsteams = ".   json_encode($teams)   . ";\n";
		// echo "var jsplayers = ". json_encode($players) . ";\n";
		// echo "</script>";

        function getTable($conn, $req){
            $q = $conn->query($req);
            $data = $q->fetch_all( MYSQLI_ASSOC );
            return json_encode( $data );

            // while ($row = $q->fetch_assoc()) { // pour éviter une boucle infinie faite au tout début du codage ^^
            //     array_push($rows, $row);
            // }
            // debug_to_console("récupéré ".count($data)." éléments de " . $req);
            // return $data;            
        }
        
        function connectToDB($db) {
            $servername = "localhost"; // 192.168.1.40 localhost
            $username = "root";
            $password = "";
            $conn = $db;
            $port = "3306";
            $conn = new mysqli($servername, $username, $password, $conn) or die("Connect failed: %s\n". $conn -> error);
            if ( mysqli_connect_errno() ) {
                debug_to_console("Connection failed: " . mysqli_connect_error());
                die("Connection failed: " . $conn->connect_error);
              }
              
            return $conn;
        }
    
        function debug_to_console($data) {
            $output = $data;
            if (is_array($output))
                $output = implode(',', $output);	
            echo "<script>console.log('==============================PHP Debug: " . $output . "' );</script>";
        }

    ?>

</body>
</html>