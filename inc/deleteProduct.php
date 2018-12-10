<?php

    session_start();
    include 'dbConnection.php';
        
    $conn = getDatabaseConnection();
    
    $sql = "DELETE
            FROM product
            WHERE productId = " . $_GET['productId'];
            
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    header("Location:../products.php");
?>