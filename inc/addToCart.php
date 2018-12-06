<?php
    
    session_start();

    $productId = $_GET['addProduct'];
    $cart_item = array();
    
    if(empty($_SESSION['cart'])){
        $_SESSION['cart'] = array();
    }
    
    if(array_key_exists($productId, $_SESSION['cart'])){
        // If already exists in cart, do not add. I need to add some visual identifier
        header('Location: ../shop.php?action=exists&id=' . $productId);
    } else {
        // Add to cart, stored in variable $_SESSION['cart']
        $_SESSION['cart'][$productId]=$cart_item;
        header('Location: ../shop.php');
    }
    

    // Store the added product IDs in an array

    // $ids = array();
    // foreach($_SESSION['cart'] as $id=>$value){
    //     array_push($ids, $id);
    // }

    // $ids contains product IDs ($ids = [2,7,8,4])

?>
