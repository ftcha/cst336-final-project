<?php 

    session_start();
    
    if(!isset($_SESSION['isAdmin']) or !$_SESSION['isAdmin']){
        header("Location:index.php");
    }
    
    include 'inc/header.php'; 
    
    $conn = getDatabaseConnection();
    
    if (isset($_GET['addProduct'])) {
        
        $sql="INSERT INTO product (productId, NAME, description, imageURL, price) VALUES
                (NULL, :NAME, :description, :imageURL, :price)";
              
        $np = array();
        $np[":NAME"] = $_GET['NAME'];
        $np[":description"] = $_GET['description'];
        $np[":imageURL"] = $_GET['imageURL'];
        $np[":price"] = $_GET['price'];
        
        $stmt = $conn->prepare($sql);
        $stmt->execute($np);
        
        echo "<span id='addProductMsg'> Product has been added! </span>";
        echo "<br /><br />";
    }

?>
    
    <div class='container'>
        <a href="products.php" class="btn btn-info" role="button">Back</a>
        <br /><br />
        
        <form>
            <input type="hidden" name="productId" />
            <strong>Product Name</strong> <input type="text" class="form-control" name="NAME"><br />
            <strong>Description</strong> <textarea name="description" class="form-control" cols="50" rows ="4"></textarea><br />
            <strong>Price</strong> <input type="text" class="form-control" name="price"> <br />
            <strong>Set Image Url</strong> <textarea name="imageURL" class="form-control" cols="50" rows ="2"></textarea><br />
            
            <br />
            
            <input type="submit" class="btn btn-primary" name="addProduct" value="Add Product">
        </form>
    </div>
    
 <?php
    include 'inc/footer.php';
?>