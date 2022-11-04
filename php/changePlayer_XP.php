<?php 


// Connection
$conn = include 'connectToDB.php';
include 'input.php'; // pour la fonction clean_input qui évite les injections sql

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try{
    
    if(isset($_POST['id']) and isset($_POST['xp'])) {
        $sql = "UPDATE tbplayer SET xp = ? WHERE tbplayer.id = ?;";
        
        ($stmt = $conn->prepare($sql)) or trigger_error($conn->error, E_USER_ERROR);
        // $stmt->bind_param('i', $_POST['xp']); 
        $stmt->bind_param('ii', clean_input($_POST['xp']), clean_input($_POST['id'])); 
        $stmt->execute() or trigger_error($stmt->error, E_USER_ERROR);
        
        // Return response to the browser
        if ($stmt) {
            $success = true;
            $result = "👍 Force modifiée";
        } else {
            $success = false;
            $result = "Error: $conn->error";
        }
        $stmt->close();
    }
    
    // // Requete au serveur
    // $sql = "UPDATE tbplayer SET xp = '$xp' WHERE tbplayer.id = $id;";
    // $req = $conn->query($sql);
    
    
    
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
