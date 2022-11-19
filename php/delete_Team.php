<?php  // var_dump (file_get_contents('php://input'));


// Connection
$conn = include 'connectToDB.php';
include 'updateDropdowns.php';

// Recup les infos à sauvegarder
include 'input.php'; // pour la fonction clean_input qui évite les injections sql
$id   = clean_input($_POST['id']);
$name   = clean_input($_POST['name']);

// Requête au serveur
$sql  = "DELETE FROM tbteam WHERE id = $id";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
// $req  = $conn->query($sql);

// Return response to the browser
if (!$stmt) {
    $success = false;
    $result = "Error: $stmt->error";
} else {
    $success = true;
    $result = "👍 $name a été supprimée";

    $all_teams = $conn->query("SELECT * FROM tbteam") -> fetch_all( MYSQLI_ASSOC );
    $dropdownfav = updateDropdownHTMLfav($all_teams);
    $dropdowndel = updateDropdownHTMLdel($all_teams, $name);

}
$stmt->close;

echo json_encode(
    array(
        'success' => $success,
        'result' => $result,
        'dropdownHTMLfav' => $dropdownfav,
        'dropdownHTMLdel' => $dropdowndel
    ), JSON_UNESCAPED_UNICODE);

$conn -> close();
?>
