<?php
    session_start();
    include 'inc/header.php';
?>

  <div class='container'>
      
      
    <?php 
        if(isset($_POST['searchVal']) and $_POST['searchVal'] != ''){
            searchProduct($_POST['searchVal']);
        }else{
          displayAllProducts(); 
        }
    ?>
      
  </div>
<?php
    include 'inc/footer.php';
?>
