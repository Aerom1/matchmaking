<?php 

// Connection
$conn = include 'connectToDB.php';
include 'input.php'; // pour la fonction clean_input qui évite les injections sql
include 'updateDropdowns.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try{
    
    if(isset($_POST['id']) and isset($_POST['name'])) {

        // Enlever le favori à toutes les lignes
        $conn->query("UPDATE tbteam set fav = 0");

        // Mettre le favori à l'équipe choisie
        $sql = "UPDATE tbteam SET fav = 1 WHERE tbteam.id = ?;";
        ($stmt = $conn->prepare($sql)) or trigger_error($conn->error, E_USER_ERROR);
        $stmt->bind_param('i', clean_input($_POST['id'])); 
        $stmt->execute() or trigger_error($stmt->error, E_USER_ERROR);

        // Return response to the browser
        if (!$stmt) {
            $success = false;
            $result = "Error: $conn->error";
        } else {

            $success = true;
            $result = "👍 Nouvelle équipe favorite : " . $_POST['name'];

            $all_teams = $conn->query("SELECT * FROM tbteam") -> fetch_all( MYSQLI_ASSOC );
            $dropdownHTMLfav = updateDropdownHTMLfav($all_teams);

            // $dropdownHTML = '<li style="font-size:2em;"><center>🏠</li><li><hr class="dropdown-divider"></li>';
            // foreach( $all_teams as $team) {
            //     if($team["fav"]) {
            //         $dropdownHTML .= "<li><center><button type='button' class='dropdown-item' style='color:white;background-color:green;'>✓  ".$team["name"]."</button></li>";
            //     } else {
            //         $dropdownHTML .=  "<li><center><button onclick='selectFavorite(this)' teamid=".$team["id"]." type='button' class='dropdown-item'>".$team["name"]."</button></li>";
            //     }
            // }
            $stmt->close();
        }
    }

    echo json_encode(
        array(
            'success' => $success,
            'result' => $result,
            'dropdownHTML' => $dropdownHTMLfav
        ), JSON_UNESCAPED_UNICODE);
        
} catch(Exception $e){
    echo("ERREUR: $e");
}

$conn -> close();
?>
