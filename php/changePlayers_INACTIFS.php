<?php 

// Connection
$conn = include 'connectToDB.php';
include 'input.php'; // pour la fonction clean_input qui évite les injections sql

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try{
    $nbok = 0;
    $nbko = 0;

    $sql = "UPDATE tbplayer SET absent = ? WHERE tbplayer.id = ?;";
    ($stmt = $conn->prepare($sql)) or trigger_error($conn->error, E_USER_ERROR);
    foreach($_POST as $id => $absent) {
        $stmt->bind_param('ii', clean_input($absent), clean_input($id)); 
        $stmt->execute() or trigger_error($stmt->error, E_USER_ERROR);
        if ($stmt) 
            {$nbok++;} else 
            {$nbko++;}
      }
      
    // Return response to the browser

    if ($nbko) {
        $success = false;
        $result = "Error ($nbko ko / ".count($_POST).") : $conn->error";
    } else {
        $success = true;
        $result = "👍 Joueurs inactifs mis à jour";
    }
    $stmt->close();
        
    echo json_encode(
        array(
            'success' => $success,
            'result' => $result
        ), JSON_UNESCAPED_UNICODE);
        
} catch(Exception $e){
    echo("ERREUR: $e");
}

$conn -> close();
?>
