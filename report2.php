<?php
    session_start();
    
    if(!isset($_SESSION['isAdmin']) or !$_SESSION['isAdmin']){
        header("Location:index.php");
    }
    
    include 'inc/header.php';
    $conn=getDatabaseConnection();
    
    $sql = "SELECT userName, tranId, FORMAT(subtotal, 2) AS Subtotal, tax, shipping, FORMAT((subtotal + tax + shipping), 2) AS total FROM TRANSACTION t
              JOIN users u ON
	            t.userId = u.userId";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    
   $sql = "SELECT FORMAT(SUM(subtotal), 2) AS sumTotal, FORMAT(SUM(tax), 2) AS sumTax, FORMAT(SUM(shipping), 2) AS sumShipping, FORMAT(SUM(subtotal + tax + shipping), 2) AS sumSum FROM TRANSACTION t
             JOIN users u ON
	           t.userId = u.userId";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $agg = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<div class="container">
    <h3>Reports</h3>
    
    <ul class="nav nav-tabs">
        <li><a href="report1.php">Inventory Report</a></li>
        <li class="active"><a href="report2.php">Transaction Report</a></li>
        <li><a href="report3.php">Transaction Detail Report</a></li>
    </ul>
    <br />
    
    <table class='table table-hover'>
        <thead>
            <th>Name</th>
            <th>Transaction Number</th>
            <th>Subtotal</th>
            <th>Tax</th>
            <th>Shipping</th>
            <th>Total</th>
        </thead>
        <tbody>
            <?php 
                foreach($records as $record){
                    echo "<tr>";
                    echo "<td>".$record['userName'] ."</td>";
                    echo "<td>".$record['tranId']."</td>";
                    echo "<td>$".money_format('%.2n', $record['Subtotal'])."</td>";
                    echo "<td>$".money_format('%.2n', $record['tax'])."</td>";
                    echo "<td>$".money_format('%.2n', $record['shipping'])."</td>";
                    echo "<td>$".money_format('%.2n', $record['total'])."</td>";
                    echo "</tr>";
                }
                echo "<tr>";
                echo "<td></td>";
                echo "<td></td>";
                echo "<td><strong>$".money_format('%.2n', $agg['sumTotal'])."</strong></td>";
                echo "<td><strong>$".money_format('%.2n', $agg['sumTax'])."</strong></td>";
                echo "<td><strong>$".money_format('%.2n', $agg['sumShipping'])."</strong></td>";
                echo "<td><strong>$".money_format('%.2n', $agg['sumSum'])."</strong></td>";
                echo "<tr>";
            ?>
        </tbody>
    </table>
</div>
  
<?php
    include 'inc/footer.php';
?>
