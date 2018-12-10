<?php
    session_start();
    include 'inc/header.php';
?>

  <div class='container'>
      <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#searchCollapse" aria-expanded="false" aria-controls="searchCollapse">
          Advanced Search &nbsp;<i class="glyphicon glyphicon-search" aria-hidden='true'></i>
      </button>
      
      <div class="collapse" id="searchCollapse">
          <div class="card card-body">
              <form class='form' id='shopSearchForm' action="shop.php" method="POST">
                  <div class='form-group'>
                      <span class='titleSearch'>Title:</span>
    
                      <div class='input-group'>
                          <input type='text' id='title' name='title' class='form-control' placeholder='Search Title' value="<?=$_POST['title']?>"/>
                          <br /><br />
                      </div>
                      
                      <span class='descriptionSearch'>Description:</span>
                      <div class='input-group'>
                          <input type='text' id='description' name='description' class='form-control' placeholder='Search Description' value="<?=$_POST['description']?>"/>
                          <br />
                      </div>
                      
                      <span class="Price">Price Range:</span>
                      <div class="input-group col-xs-2">
                        <span class="input-group-addon">Min $</span>
                        <input type="number" min="0" max="1000" class="form-control currency" name="priceMin" placeholder="$0.00" step="0.01" value="<?=$_POST['priceMin']?>">
                      </div>
                      
                      <br />
                      
                      <div class="input-group col-xs-2">
                        <span class="input-group-addon">Max $</span>
                        <input type="number" min="0" max="1000" class="form-control currency" name='priceMax' placeholder="$0.00" step="0.01" value="<?=$_POST['priceMax']?>">
                      </div>
                      
                      <br />
                      
                      <span class="Order">Order By:</span>
                      <div class="input-group">
                          <input type="checkbox" id="orderTitle" name="orderTitle" <?=($_POST['orderTitle']=='on'?'checked':'')?>>
                          <label for="orderTitle">Title</label>
                          <input type="checkbox" id="orderPrice" name="orderPrice" <?=($_POST['orderPrice']=='on'?'checked':'')?>>
                          <label for="orderPrice">Price</label>
                          <input type="checkbox" id="orderDesc" name="orderDesc" <?=($_POST['orderDesc']=='on'?'checked':'')?>>
                          <label for="orderDesc">Descending?</label>
                      </div>
                  </div>
                  
                  <div class='btn-group'>
                      <button type='submit'class='btn btn-default' aria-label='Submit'>Submit</button>
                  </div>
              </form>
          </div>
      </div>
      <br /><br />
        
            <?php 
              
                if(isset($_POST['searchVal']) and $_POST['searchVal'] != ''){
                    echo "<div id='productDisplay'>";
                    searchProduct($_POST['searchVal']);
                    echo "</div>";
                }else if(isset($_POST['title']) or isset($_POST['description'])){
                    echo "<div id='productDisplay'>";
                    advancedProductSearch($_POST['title'], $_POST['description'], $_POST['priceMin'], $_POST['priceMax'], $_POST['orderTitle'], $_POST['orderPrice'], $_POST['orderDesc']);
                    echo "</div>";
                }else{
                    echo "<div id='productDisplay'>";
                    displayAllProducts();
                    echo "</div>";
                }
                
            ?>
       
  </div>

<?php
    include 'inc/footer.php';
?>