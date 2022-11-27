<?php 
// var_dump (file_get_contents('php://input'));

// Connection
session_start(); // Start the session to store variable between pages
$conn = include 'connectToDB.php';

// Recup les infos à sauvegarder
include 'input.php'; // pour la fonction clean_input qui évite les injections sql
$id   = clean_input($_POST['id']);

// Requête au serveur
$sql = "UPDATE tbteam SET logo = '' WHERE id = $id;";
$req = $conn->query($sql);

// Return response to the browser
if (!$req) {
    $success = false;
    $result = "Error message: $conn->error";
} else {
    $success = true;
    $result = "👍 Logo modifié";
    $_SESSION['team']['logo'] = '';
}

echo json_encode(
    array(
        'success' => $success,
        'result' => $result
    ), JSON_UNESCAPED_UNICODE);

$conn -> close();
?>
