// file-upload.php
<?php 
// Tutorial PHP 8 Upload & Store File/Image in MySQL  https://www.positronx.io/php-upload-store-file-image-in-mysql-database/

    // Database connection
    // include("config/database.php");
    // Connection
    $conn = include 'php/connectToDB.php';
    include 'php/input.php'; // pour la fonction clean_input qui évite les injections sql

    // echo "<script>alert('connexion réussie')</script>";
    // echo "//ICI";
    // var_dump($_FILES["fileUpload"]["error"]);
    // echo "<script>alert('target_file : $tmpfileupload)')</script>";

if(isset($_POST["submit"])) {

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
    } else if (file_exists($target_file) | move_uploaded_file($_FILES["fileUpload"]["tmp_name"], $target_file)) {

        $sql = "UPDATE tbteam SET logo = ? WHERE tbteam.id = ?;";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', clean_input($target_file), clean_input($_POST["teamId"])); 

        // $sql = "INSERT INTO tbteam (logo) VALUES ('$target_file')";
        if($stmt->execute()){
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

    } else {
        $resMessage = array(
            "status" => "alert-danger",
            "message" => "Erreur d'import de l'image."
        );
    }
} else {

    // OUVERTURE DE LA PAGE DEPUIS L'ACCUEIL (index.php)
    $all_teams = $conn->query("SELECT * FROM tbteam") -> fetch_all( MYSQLI_ASSOC );

}
?>
