<?php
    session_start();
    
    if(!isset($_SESSION['isAdmin']) or !$_SESSION['isAdmin']){
        header("Location:index.php");
    }
    
    include 'inc/header.php';
    $conn=getDatabaseConnection();
    

    
?>

<div class="container">
    <h3>Reports</h3>
    
    <ul class="nav nav-tabs">
        <li class="active"><a href="report1.php">Inventory Report</a></li>
         <li><a href="report2.php">Transaction Report</a></li>
        <li><a href="report3.php">Transaction Detail Report</a></li>
    </ul>
    <br />
  
    <?php 
        echo "<ul style='list-style-type:none'>";
        echo "<li><strong>Average price of all products:</strong> $" .getAveragePrice(). "</li>";
        echo "<li><strong>Total value of all inventory items:</strong> $".getInventoryValue()."</li>";
        echo "<li><strong>Top selling movie(s):</strong> ".getTopSellingItems()."</li>";
        echo "<li><strong>Worst selling movie(s):</strong> ".getWorstSellingItems()."</li>";
        echo "</ul>";
    ?>
</div>
  
<?php
    include 'inc/footer.php';
?>
