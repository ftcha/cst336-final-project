<?php
    session_start();
    
    if(!isset($_SESSION['isAdmin']) or !$_SESSION['isAdmin']){
        header("Location:index.php");
    }
    
    include 'inc/header.php';
    $conn=getDatabaseConnection();
    
    $sql = "SELECT t.tranId, userName, NAME, price FROM transactionDetails td
              JOIN TRANSACTION t ON
                td.tranId = t.tranID
              JOIN users u ON
                t.userId = u.userId
            ORDER BY tranId";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
?>

<div class="container">
    <h3>Reports</h3>
    
    <ul class="nav nav-tabs">
        <li><a href="report1.php">Inventory Report</a></li>
        <li><a href="report2.php">Transaction Report</a></li>
        <li class="active"><a href="report3.php">Transaction Detail Report</a></li>
    </ul>
    <br />
    
    <table class='table table-hover'>
        <tbody>
            <?php 
                $currTran=0;
                foreach($records as $record){
                    echo "<tr>";
                    if($currTran != $record['tranId']){
                        $agg=getAgg($record['tranId']);
                        $currTran++;
                        echo "<td></td><td></td><td></td><td></td><td></td><td></td>";
                        echo "</tr><tr>";
                      
                        echo "<td><strong>".$record['userName']."</strong><td>";
                        echo "<td><strong>Transaction Number: ".$record['tranId']."</strong></td>";
                        echo "<td><strong>Subtotal: $".$agg['subtotal']."</strong></td>";
                        echo "<td><strong>Tax: $".$agg['tax']."</strong></td>";
                        echo "<td><strong>Shipping: $".$agg['shipping']."</strong></td>";
                        echo "<td><strong>Total: $".$agg['total']."</strong></td>";
                        echo "<tr/><tr>";
                    }
                        
                    echo "<td></td>";
                    echo "<td>".$record['NAME']."</td>";
                    echo "<td>".$record['price']."</td>";
                    echo "<td></td>";
                    echo "<td></td>";
                    echo "<td></td>";
                    echo "<td></td>";
                    echo "</tr>";
                }

            ?>
        </tbody>
    </table>
</div>
  
<?php
    include 'inc/footer.php';
?>
