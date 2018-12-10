<?php
    session_start();
    
    if(!isset($_SESSION['isAdmin']) or !$_SESSION['isAdmin']){
        header("Location:index.php");
    }
    
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

<div class="container">
    <h3>Reports</h3>
    
    <ul class="nav nav-tabs">
        <li class="active"><a href="report1.php">Average Price</a></li>
         <li><a href="report2.php">Transaction Report</a></li>
        <li><a href="report3.php">Transaction Detail Report</a></li>
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
