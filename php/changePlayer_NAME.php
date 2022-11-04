<?php 

// Connection
$conn = include 'connectToDB.php';
include 'input.php'; // pour la fonction clean_input qui évite les injections sql

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try{
    
    if(isset($_POST['id']) and isset($_POST['name'])) {
        $sql = "UPDATE tbplayer SET name = ? WHERE tbplayer.id = ?;";

        ($stmt = $conn->prepare($sql)) or trigger_error($conn->error, E_USER_ERROR);
        $stmt->bind_param('si', clean_input($_POST['name']), clean_input($_POST['id'])); 
        $stmt->execute() or trigger_error($stmt->error, E_USER_ERROR);

        // Return response to the browser
        if ($stmt) {
            $success = true;
            $result = "👍 Nouveau nom : " . $_POST['name'];
        } else {
            $success = false;
            $result = "Error: $conn->error";
        }
        $stmt->close();
    }

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
