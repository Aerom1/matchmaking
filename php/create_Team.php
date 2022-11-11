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
            $result = "👍 $name a été créée";
            $new_id = $conn->insert_id;

            $_SESSION['team']['id'] = $new_id;
            $_SESSION['team']['name'] = $name;
            $_SESSION['team']['logo'] = '';
            $_SESSION['team']['fav'] = 0;

            $all_teams = $conn->query("SELECT * FROM tbteam") -> fetch_all( MYSQLI_ASSOC );
            $dropdownHTMLfav = '<li style="font-size:2em;"><center>🏠</li><li><hr class="dropdown-divider"></li>';
            foreach( $all_teams as $team) {
                if($team["fav"]) {
                    $dropdownHTMLfav .= "<li><center><button type='button' class='dropdown-item' style='color:white;background-color:green;'>✓  ".$team["name"]."</button></li>";
                } else {
                    $dropdownHTMLfav .=  "<li><center><button onclick='selectFavorite(this)' teamid=".$team["id"]." type='button' class='dropdown-item'>".$team["name"]."</button></li>";
                }
            }
            $dropdownHTMLdel = '';
            foreach( $all_teams as $team) {
                $dropdownHTMLdel .= "<li><center><button onclick='btDelTeam(this)' teamid=" .$team["id"]. " teamname=" .$team["name"]. " type='button' class='dropdown-item'>".$team["name"]."</button></li>";
            }
            $stmt->close();
        }
    }
    
    $tableauRetour = array(
        'success' => $success,
        'result' => $result,
        'id' => $new_id,
        'dropdownHTMLfav' => $dropdownHTMLfav,
        'dropdownHTMLdel' => $dropdownHTMLdel
    );

    echo json_encode($tableauRetour, JSON_UNESCAPED_UNICODE);

}

$conn -> close();

?>
