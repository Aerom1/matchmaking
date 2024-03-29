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

// Désactiver cette ligne pour obliger à s'identifier pour accéder à la BDD
// include "check_connection_or_exit.php"; 


$conn = include 'connectToDB.php';
include 'input.php'; // pour la fonction clean_input qui évite les injections sql

// Recup les infos à sauvegarder
$name   = clean_input($_POST['name']);
$team   = clean_input($_POST['team']);
$xp     = clean_input($_POST['xp']);
$absent = clean_input($_POST['absent']);

// // Commence par vérifier s'il y a déjà un joueur qui porte ce nom dans l'équipe
// $sql = "SELECT * FROM tbplayer WHERE name='$name' and team=$team";
// $req = $conn->query($sql);

// // Return response to the browser
// if (!$req) {
//     // printf("<br/>Error message: %s\n", $conn->error);
//     $success = false;
//     $result = "Error message: $conn->error";
// } else {
//     if ($req->num_rows >= 1) {
//         // echo "Une personne porte déjà ce nom !";
//         $success = false;
//         $result = "Une personne porte déjà ce nom !";
//     } else {

        $sql = "INSERT INTO tbplayer (name, team, xp, absent) VALUES (?, ?, ?, ?)";
        // $req = $conn->query($sql);
        ($stmt = $conn->prepare($sql)) or trigger_error('Mysql prepare | '.$conn->error, E_USER_ERROR);
        $stmt->bind_param('siii',$name, $team, $xp, $absent) or trigger_error('Mysql bind_param | '.$stmt->error, E_USER_ERROR);
        $stmt->execute() or trigger_error('Mysql execute | '.$stmt->error, E_USER_ERROR);


        // Return response to the browser
        if (!$stmt) {
            // printf("<br/>Error message: %s\n", $conn->error);
            $success = false;
            $result = "Error: $stmt->error";
        } else {
            // printf("<br/>Le joueur a été ajouté");
            $success = true;
            $result = "👍 $name a été ajouté";
            $new_id = $conn->insert_id;
        }
// }
    
$tableauRetour = array(
    'success' => $success,
    'result' => $result,
    'id' => $new_id
);

echo json_encode($tableauRetour, JSON_UNESCAPED_UNICODE);

// }



$conn -> close();

?>
