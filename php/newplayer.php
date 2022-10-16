<?php 

// echo "<div>".$_POST['name'] . " | " . $_POST['team'] . " | " . $_POST['id'] . " | " . $_POST['xp'] . " | " . $_POST['absent']."</div><br/><br/>";
// echo "php-input:<br/>";
// var_dump (file_get_contents('php://input'));
// echo "<br/><br/>";
// echo "post:<br/>";
// var_dump($_POST);
// echo "<br/><br/>";

// Connection
// include "functions.php"; 
// $conn = connectToDB();
$conn = include 'connectToDB.php';

// Recup les infos à sauvegarder
$name   = $_POST['name'];
$team   = $_POST['team'];
$xp     = $_POST['xp'];
$absent = $_POST['absent'];

// Commence par vérifier s'il y a déjà un joueur qui porte ce nom dans l'équipe
$sql = "SELECT * FROM tbplayer WHERE name='$name' and team=$team";
$req = $conn->query($sql);
// var_dump($req);

// Return response to the browser
if (!$req) {
    // printf("<br/>Error message: %s\n", $conn->error);
    $success = false;
    $result = "Error message: $conn->error";
} else {
    if ($req->num_rows >= 1) {
        // echo "Une personne porte déjà ce nom !";
        $success = false;
        $result = "Une personne porte déjà ce nom !";
    } else {
        $sql = "INSERT INTO tbplayer (name, team, xp, absent) VALUES ('$name', $team, $xp, $absent)";
        $req = $conn->query($sql);
        
        // Return response to the browser
        if (!$req) {
            // printf("<br/>Error message: %s\n", $conn->error);
            $success = false;
            $result = "Error message: $conn->error";
        } else {
            // printf("<br/>Le joueur a été ajouté");
            $success = true;
            $result = "Le joueur a été ajouté";
            $new_id = $conn->insert_id;
        }
    }
    
$tableauRetour = array(
    success => $success,
    result => $result,
    id => $new_id
);

echo json_encode($tableauRetour, JSON_UNESCAPED_UNICODE);

}



$conn -> close();

?>
