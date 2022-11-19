<?php 

// Connection
$conn = include 'connectToDB.php';
include 'input.php'; // pour la fonction clean_input qui évite les injections sql
include 'updateDropdowns.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try{
    
    if(isset($_POST['id']) and isset($_POST['name'])) {
        $sql = "UPDATE tbteam SET name = ? WHERE tbteam.id = ?;";
        ($stmt = $conn->prepare($sql)) or trigger_error($conn->error, E_USER_ERROR);
        $stmt->bind_param('si', clean_input($_POST['name']), clean_input($_POST['id'])); 
        $stmt->execute() or trigger_error($stmt->error, E_USER_ERROR);

        // Return response to the browser
        if (!$stmt) {
            $success = false;
            $result = "Error: $conn->error";
        } else {
            $success = true;
            $result = "👍 Nouveau nom : " . $_POST['name'];

            $_SESSION['team']['name'] = $_POST['name'];
            $all_teams = $conn->query("SELECT * FROM tbteam") -> fetch_all( MYSQLI_ASSOC );
            $dropdownfav = updateDropdownHTMLfav($all_teams);
            $dropdowndel = updateDropdownHTMLdel($all_teams, $name);

        }
        $stmt->close();
    }

    echo json_encode(
        array(
            'success' => $success,
            'result' => $result,
            'dropdownHTMLfav' => $dropdownfav,
            'dropdownHTMLdel' => $dropdowndel
        ), JSON_UNESCAPED_UNICODE);
        
} catch(Exception $e){
    echo("ERREUR: $e");
}

$conn -> close();
?>
