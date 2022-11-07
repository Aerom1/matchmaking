// config/database.php
<?php

// Tutorial PHP 8 Upload & Store File/Image in MySQL  https://www.positronx.io/php-upload-store-file-image-in-mysql-database/

    $hostname = "localhost";
    $username = "root";
    $password = "";
    try {
        $conn = new PDO("mysql:host=$hostname;dbname=php_crud", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo "Database connected successfully";
    } catch(PDOException $e) {
        echo "Database connection failed: " . $e->getMessage();
    }
?>