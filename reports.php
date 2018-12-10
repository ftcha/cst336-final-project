<?php
    session_start();
    
    if(!isset($_SESSION['isAdmin']) or !$_SESSION['isAdmin']){
        header("Location:index.php");
    }
    include 'inc/header.php';
?>

<div class="container">
    <h3>Reports</h3>
    <ul class="nav nav-tabs">
        <li><a href="report1.php">Average Price</a></li>
        <li><a href="report2.php">Transaction Report</a></li>
        <li><a href="report3.php">Transaction Detail Report</a></li>
    </ul>
</div>
  
<?php
    include 'inc/footer.php';
?>
