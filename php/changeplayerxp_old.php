<?php 

// Connection
$conn = include 'connectToDB.php';

// Recup les infos à sauvegarder
include 'input.php'; // pour la fonction clean_input qui évite les injections sql
$id = clean_input($_POST['id']);
$xp = clean_input($_POST['xp']);

// Requete au serveur
$sql = "UPDATE tbplayer SET xp = '$xp' WHERE tbplayer.id = $id;";
$req = $conn->query($sql);

// Return response to the browser
if (!$req) {
    $success = false;
    $result = "Error message: $conn->error";
} else {
    $success = true;
    $result = "La force du joueur a été modifiée";
}

echo  json_encode(  array(
            success => $success,
            result => $result
        ), JSON_UNESCAPED_UNICODE);

$conn -> close();
?>
