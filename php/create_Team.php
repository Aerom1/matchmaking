<?php 

$conn = include 'connectToDB.php';
include 'input.php'; // pour la fonction clean_input qui évite les injections sql
include 'updateDropdowns.php';

// Recup les infos à sauvegarder
$name   = clean_input($_POST['name']);

// Commence par vérifier s'il y a déjà un joueur qui porte ce nom dans l'équipe
$sql = "SELECT * FROM tbteam WHERE tbteam.name=?";
($stmt = $conn->prepare($sql)) or trigger_error($conn->error, E_USER_ERROR);
$stmt->bind_param('s',$name) or trigger_error('Mysql bind_param | '.$stmt->error, E_USER_ERROR);
$stmt->execute() or trigger_error('Mysql execute | '.$stmt->error, E_USER_ERROR);

// Return response to the browser
if (!$stmt) {
    // printf("<br/>Error message: %s\n", $conn->error);
    $success = false;
    $result = "Error message: $stmt->error";
} else {

    $result = $stmt->get_result();
    $nbEquipe = $result->num_rows;

    if ($nbEquipe >= 1) {
        // echo "Une personne porte déjà ce nom !";
        $success = false;
        $result = "Une équipe porte déjà ce nom !";
    } else {

        $sql = "INSERT INTO tbteam (name, logo, fav, sport) VALUES (?, '', 0, '')";
        ($stmt = $conn->prepare($sql)) or trigger_error('Mysql prepare | '.$conn->error, E_USER_ERROR);
        $stmt->bind_param('s', $name) or trigger_error('Mysql bind_param | '.$stmt->error, E_USER_ERROR);
        $stmt->execute() or trigger_error('Mysql execute | '.$stmt->error, E_USER_ERROR);

        // Return response to the browser
        if (!$stmt) {
            // printf("<br/>Error message: %s\n", $conn->error);
            $success = false;
            $result = "Error: $conn->error";
        } else {
            // printf("<br/>Le joueur a été ajouté");
            $success = true;
            $result = "👍 $name a été créée";
            $new_id = $conn->insert_id;

            $_SESSION['team']['id'] = $new_id;
            $_SESSION['team']['name'] = $name;
            $_SESSION['team']['logo'] = '';
            $_SESSION['team']['fav'] = 0;
            
            $all_teams = $conn->query("SELECT * FROM tbteam") -> fetch_all( MYSQLI_ASSOC );

            $dropdownfav = updateDropdownHTMLfav($all_teams);
            $dropdowndel = updateDropdownHTMLdel($all_teams, $name);

            $stmt->close();
        }
    }
    
    $tableauRetour = array(
        'success' => $success,
        'result' => $result,
        'id' => $new_id,
        'dropdownHTMLfav' => $dropdownfav,
        'dropdownHTMLdel' => $dropdowndel
    );

    echo json_encode($tableauRetour, JSON_UNESCAPED_UNICODE);

}

$conn -> close();

?>
