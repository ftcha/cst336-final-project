<?php
    session_start();
    include 'inc/header.php';
?>

  <div class='container text-center'>
      <h1>Welcome to the Emporium</h1>
      <img src='img/vhs.jpg' style='padding:30px;'>
      <br>
      <button type="submit" class="btn btn-lg" onclick="location.href='shop.php';" style='margin-bottom:20px;'>Enter</button>
  </div>
<?php
    include 'inc/footer.php';
?>
