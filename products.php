<?php
    session_start();
    
    if(!isset($_SESSION['isAdmin']) or !$_SESSION['isAdmin']){
        header("Location:index.php");
    }
    
    include 'inc/header.php';
?>

    <div class='container'>
        <a href="addProduct.php" class="btn btn-success" role="button">Add Product</a>
        <br /><br />
      
        <?php displayProducts() ?>
    </div>
  
<?php
    include 'inc/footer.php';
?>
