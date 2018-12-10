<?php 

    session_start();
    
    if(!isset($_SESSION['isAdmin']) or !$_SESSION['isAdmin']){
        header("Location:index.php");
    }
    
    include 'inc/header.php';
        
    $conn = getDatabaseConnection();
    
    if (isset($_GET['productId'])) {
        $product = getProductInfo();
    }
    
    function getProductInfo() {
        
        global $conn;
        
        $sql = "SELECT *
                FROM product
                WHERE productId = " . $_GET['productId'];
        
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $record = $stmt->fetch(PDO::FETCH_ASSOC);

        return $record;
    }
    
    if (isset($_GET['updateProduct'])) {
        
        $sql="UPDATE product
              SET NAME = :NAME,
                  description = :description,
                  imageURL = :imageURL,
                  price = :price
              WHERE productId = :productId";
              
        $np = array();
        $np[":NAME"] = $_GET['NAME'];
        $np[":description"] = $_GET['description'];
        $np[":imageURL"] = $_GET['imageURL'];
        $np[":price"] = $_GET['price'];
        $np[":productId"] = $_GET['productId'];
        
        $stmt = $conn->prepare($sql);
        $stmt->execute($np);
        
        echo "<span id='updateProductMsg'> Product has been updated! </span>";
        echo "<br /><br />";
    }

?>
    
    <div class='container'>
        <a href="products.php" class="btn btn-info" role="button">Back</a>
        <br /><br />
        
        <form>
            <input type="hidden" name="productId" value= "<?=$product['productId']?>"/>
            <strong>Product Name</strong> <input type="text" class="form-control" value="<?=$product['NAME']?>" name="NAME"><br />
            <strong>Description</strong> <textarea name="description" class="form-control" cols="50" rows ="4"><?=$product['description']?></textarea><br />
            <strong>Price</strong> <input type="text" class="form-control" name="price" value= "<?=$product['price']?>"> <br />
            <strong>Set Image Url</strong> <textarea name="imageURL" class="form-control" cols="50" rows ="2"><?=$product['imageURL']?></textarea><br />
            
            <br />
            
            <input type="submit" class="btn btn-primary" name="updateProduct" value="Update Product">
        </form>
    </div>
    
 <?php
    include 'inc/footer.php';
?>