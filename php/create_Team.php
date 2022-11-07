<?php 

$conn = include 'connectToDB.php';
include 'input.php'; // pour la fonction clean_input qui évite les injections sql

// Recup les infos à sauvegarder
$name   = clean_input($_POST['name']);

// Commence par vérifier s'il y a déjà un joueur qui porte ce nom dans l'équipe
$sql = "SELECT * FROM tbteam WHERE tbteam.name=?";
($stmt = $conn->prepare($sql)) or trigger_error($conn->error, E_USER_ERROR);
$stmt->bind_param('s',clean_input($_POST['name']));
$stmt->execute() or trigger_error($stmt->error, E_USER_ERROR);

// Return response to the browser
if (!$stmt) {
    // printf("<br/>Error message: %s\n", $conn->error);
    $success = false;
    $result = "Error message: $conn->error";
} else {

    $result = $stmt->get_result();
    $nbEquipe = $result->num_rows;

    if ($nbEquipe >= 1) {
        // echo "Une personne porte déjà ce nom !";
        $success = false;
        $result = "Une équipe porte déjà ce nom !";
    } else {

        $sql = "INSERT INTO tbteam (name, logo, fav) VALUES (?, '', 0)";
        ($stmt = $conn->prepare($sql)) or trigger_error($conn->error, E_USER_ERROR);
        $stmt->bind_param('s', clean_input($_POST['name']));
        $stmt->execute() or trigger_error( $stmt->error, E_USER_ERROR);

        // Return response to the browser
        if (!$stmt) {
            // printf("<br/>Error message: %s\n", $conn->error);
            $success = false;
            $result = "Error: $conn->error";
        } else {
            // printf("<br/>Le joueur a été ajouté");
            $success = true;
            $result = "👍 l'équipe a été créée";
            $new_id = $conn->insert_id;

            $_SESSION['team']['id'] = $new_id;
            $_SESSION['team']['name'] = $name;
            $_SESSION['team']['logo'] = '';
            $_SESSION['team']['fav'] = 0;

        }
    }
    
    $tableauRetour = array(
        'success' => $success,
        'result' => $result,
        'id' => $new_id
    );

    echo json_encode($tableauRetour, JSON_UNESCAPED_UNICODE);

}

$conn -> close();

?>
