<?php
    session_start();
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
