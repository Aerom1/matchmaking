<?php 

// Connection
$conn = include 'connectToDB.php';
include 'input.php'; // pour la fonction clean_input qui évite les injections sql

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try{
        
    // Récupérez les données envoyées depuis la requête FETCH
    $data = json_decode(file_get_contents('php://input'), true);

    // Récupérez les données spécifiques (dans ce cas, les joueurs)
    $donneesJoueurs = $data['donneesJoueurs'];

    $success = true;
    $result = "Les statuts des joueurs (actif/inactif) ont été mis à jour!";

    // Boucle pour mettre à jour chaque joueur dans la table 'tbplayer'
    foreach ($donneesJoueurs as $idJoueur => $valeur) {
        // Échapper les valeurs pour éviter les injections SQL
        $idJoueur = $conn->real_escape_string($idJoueur);
        $valeur = $conn->real_escape_string($valeur);

        // Exécuter la requête SQL de mise à jour
        $sql = "UPDATE tbplayer SET absent = '$valeur' WHERE id = '$idJoueur'";

        if ($conn->query($sql) !== TRUE) {
            // Gestion des erreurs si la mise à jour échoue
            echo "Erreur lors de la mise à jour pour le joueur avec l'ID $idJoueur : " . $conn->error;
            $success = false;
            $result = "Error: $conn->error";
        }
    }    

    $conn->close();

    echo json_encode(
        array(
            'success' => $success,
            'result' => $result
        ), JSON_UNESCAPED_UNICODE);
        
} catch(Exception $e){
    echo("ERREUR: $e");
}

$conn -> close();
?>
