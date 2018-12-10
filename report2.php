<?php
    session_start();
    include 'inc/header.php';
    $conn=getDatabaseConnection();
    
    function getAllProducts(){
        global $conn;
        $sql = "SELECT * 
                FROM product 
                ORDER BY NAME";
                
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $records;
    }
    
    function getAveragePrice($array){
        $total = 0;
        foreach($array as $arrayItem){
            $total = $total + $arrayItem['price'];
        }
        
        $average = number_format((float)$total/count($array), 2, '.', '');
        
        return $average;
    }
    
?>

<div class='container text-center'>
    <h1>Reports</h1>
    
    <?php 
        $allProducts = getAllProducts();
        echo "Average price of all products is $" . getAveragePrice($allProducts) . ".";
        echo "<br><br>";
    ?>
    
</div>

<div class="container">
    <h3>Reports</h3>
    
    <ul class="nav nav-tabs">
        <li><a href="report1.php">Average Price</a></li>
        <li class="active"><a href="report2.php">Report2</a></li>
        <li><a href="#">Report3</a></li>
        <li><a href="#">Report4</a></li>
    </ul>
    <br />
  
    <?php 
        $allProducts = getAllProducts();
        echo "Average price of all products is $" . getAveragePrice($allProducts) . ".";
        echo "<br><br>";
    ?>
</div>
  
<?php
    include 'inc/footer.php';
?>
