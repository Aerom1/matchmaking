<?php 

// echo "php-input:<br/>";
// var_dump (file_get_contents('php://input'));
// echo "<br/><br/>";
// echo "post:<br/>";
// var_dump($_POST);
// echo "<br/><br/>";

// Connection
$conn = include 'connectToDB.php';

// Recup les infos à sauvegarder
$id   = $_POST['id'];
$name = $_POST['name'];
$sql = "UPDATE tbplayer SET name = '$name' WHERE tbplayer.id = $id;";
// $sql = "DELETE FROM tbplayer WHERE id = $id";
$req = $conn->query($sql);
// var_dump($req);

// Return response to the browser
if (!$req) {
    $success = false;
    $result = "Error message: $conn->error";
} else {
    $success = true;
    $result = "Nom modifié 👍";
}

echo json_encode(
    array(
        success => $success,
        result => $result
    ), JSON_UNESCAPED_UNICODE);

$conn -> close();
?>
