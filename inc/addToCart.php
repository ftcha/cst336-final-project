<?php
    
    session_start();

    $productId = $_GET['addProduct'];
    $cart_item = array();
    
    if (!array_key_exists($productId, $_SESSION['cart'])) {
        $_SESSION['cart'][$productId]=$cart_item;
    }
    
    $_SESSION['cart'][$productId]['quantity']++;
    header('Location: ../shop.php');
    
    
    
    // Structure of the "$_SESSION['cart']" array:
    
    /*
        Array
    (
        [cart] => Array
            (
                [3] => Array
                    (
                        [quantity] => 3
                    )
    
                [5] => Array
                    (
                        [quantity] => 4
                    )
    
                [8] => Array
                    (
                        [quantity] => 1
                    )
    
            )
    
    )
    */

?>
