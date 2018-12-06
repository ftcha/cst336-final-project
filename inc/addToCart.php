<?php
    
    session_start();

    $productId = $_GET['addProduct'];
    $cart_item = array();
    
    if(empty($_SESSION['cart'])){
        $_SESSION['cart'] = array();
    }
    
    if(array_key_exists($productId, $_SESSION['cart'])){
        header('Location: ../shop.php?action=exists&id=' . $productId);
    } else {
        $_SESSION['cart'][$productId]=$cart_item;
        header('Location: ../shop.php');
    }
    

?>