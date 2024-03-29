// file-upload.php
<?php 
// Tutorial PHP 8 Upload & Store File/Image in MySQL  https://www.positronx.io/php-upload-store-file-image-in-mysql-database/

session_start(); // Start the session to store variable between pages

$conn = include 'php/connectToDB.php';
include 'php/input.php'; // pour la fonction clean_input qui évite les injections sql

if ( isset($_POST["submit"]) ) {

    // OUVERTURE DE LA PAGE APRES FILE SUBMIT DEPUIS LA MEME PAGE

    // Set image placement folder
    $target_dir = "img/team/";
    // Get file path
    $target_file = $target_dir . basename($_FILES["fileUpload"]["name"]);
    // Get file extension
    $imageExt = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    // Allowed file types
    $allowd_file_ext = array("jpg", "jpeg", "gif", "png");
    
    // $tmpname = $_FILES['fileUpload']['tmp_name'];
    // echo "<script>alert('target_file : $target_file')</script>";
    // echo "<script>alert('tmpname : $tmpname ')</script>";



    if (!file_exists($_FILES["fileUpload"]["tmp_name"])) {
       $resMessage = array(
           "status" => "alert-danger",
           "message" => "Choisir le fichier à importer."
       );
    } else if (!in_array($imageExt, $allowd_file_ext)) {
        $resMessage = array(
            "status" => "alert-danger",
            "message" => "Formats de fichier autorisés : .jpg, .jpeg, .gif and .png."
        );            
    } else if ($_FILES["fileUpload"]["size"] > 2097152) {
        $resMessage = array(
            "status" => "alert-danger",
            "message" => "Fichier trop grand : max 2 Mb"
        );
    } else {
        
        if (file_exists($target_file)) { 
            echo "<script>console.log('################################# le fichier existe déjà'); </script>";
        } else {
            echo "<script>console.log('################################# le fichier n existe pas encore'); </script>";
            if (move_uploaded_file($_FILES["fileUpload"]["tmp_name"], $target_file)) {
                echo "<script>console.log('################################# déplacement réussi'); </script>";
            } else {

                echo "<script>console.log('################################# déplacement échoué'); </script>";
                $resMessage = array(
                    "status" => "alert-danger",
                    "message" => "Erreur d'import #".$_FILES["fileUpload"]["error"]." | file:".$target_file
                );
                goto stopUpload;
            }
        }

        $sql = "UPDATE tbteam SET logo = ? WHERE tbteam.id = ?;";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $target_file, clean_input($_POST["teamId"])); 

        // $sql = "INSERT INTO tbteam (logo) VALUES ('$target_file')";
        if ($stmt->execute()) {
        $resMessage = array(
            "status" => "alert-success",
            "message" => "Logo modifié !"
        );
        $_SESSION['team']['logo'] = clean_input($target_file);
        } else {
        $resMessage = array(
            "status" => "alert-danger",
            "message" => "Erreur d'enregistrement (SQL)."
            );
        }

        stopUpload:

    }
} else {

    // OUVERTURE DE LA PAGE DEPUIS L'ACCUEIL (index.php)
    $all_teams = $conn->query("SELECT * FROM tbteam") -> fetch_all( MYSQLI_ASSOC );

}
?>
