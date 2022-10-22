<?php 
    // echo '<p>Bonjour le monde localhost / 192.168.1.40</p>'; 
    // phpinfo();
    $servername = "localhost";
    $username = "root";
    $password = "";
	$db = "dbmatchmaking2";
    // // Create connection
    // $conn = mysqli_connect($servername, $username, $password);
    // // Check connection
    // if (!$conn) {
    //     die("Connection mysqli_connect procedural : failed. Error: " . mysqli_connect_error());
    // }
    // echo "Connected successfully with mysqli_connect procedural";
    $conn2 = new mysqli($servername, $username, $password,$db) or die("Connect MySQLi Object-Oriented failed: %s\n". $conn -> error);
    // echo "\n\n -> FIN DES TESTS DE CONNEXION";
	$result = mysqli_query($conn2, "SELECT * FROM tbplayer LIMIT 1");
	
	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		debug_to_console($row);
		debug_to_console($row["xp"]);
		$row = $result->fetch_assoc();
		debug_to_console($row);
		// echo '<script>alert("' . $test . '")</script>';
	}

 ?>