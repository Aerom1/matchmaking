<?php  // var_dump (file_get_contents('php://input'));


// Connection
$conn = include 'connectToDB.php';

// Recup les infos à sauvegarder
include 'input.php'; // pour la fonction clean_input qui évite les injections sql
$id   = clean_input($_POST['id']);
$name = clean_input($_POST['name']);

// Requête au serveur
$sql  = "DELETE FROM tbplayer WHERE id = $id";
$req  = $conn->query($sql);

// Return response to the browser
if (!$req) {
    $success = false;
    $result = "Error: $conn->error";
} else {
    $success = true;
    $result = "$name a été supprimé 👍";
}

echo json_encode(
    array(
        success => $success,
        result => $result
    ), JSON_UNESCAPED_UNICODE);

$conn -> close();
?>
