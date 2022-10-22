<html>
 <head>
  <title>Test PHP</title>
 </head>
 <body>

<?php 
    echo '<p>Bonjour le monde</p>'; 
    
    // phpinfo();

    
    $servername = "localhost";
    $username = "root";
    $password = "";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password);
    // Check connection
    if (!$conn) {
        die("Connection mysqli_connect procedural : failed. Error: " . mysqli_connect_error());
    }
    echo "Connected successfully with mysqli_connect procedural";

    $db = "dbplayer";

    $conn = new mysqli($servername, $username, $password, $db) or die("Connect MySQLi Object-Oriented failed: %s\n". $conn -> error);

    echo "\n\n -> FIN DES TESTS DE CONNEXION";


    echo phpinfo();


 ?>
 </body>
</html>