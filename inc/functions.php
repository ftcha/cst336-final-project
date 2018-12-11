<?php
    session_start();
    include 'dbConnection.php';
    $conn=getDatabaseConnection();

    if(isset($_POST['action'])){
        if($_POST['action'] == 'getStates'){
            echo getStateCodes();
        }
        
        if($_POST['action'] == 'login'){
            echo loginAttempt($_POST['username'], $_POST['password']);
        }
        
        if($_POST['action'] == 'logout'){
            echo session_destroy();
        }
        
        if($_POST['action'] == 'signup'){
            echo signupAttempt($_POST['username'], $_POST['password'], $_POST['state']);
        }
        
        if($_POST['action'] == 'addToCart'){
            echo addToCart($_POST['itemNum']);
        }
        
        if($_POST['action'] == 'getCart'){
            echo getCart();
        }
        
        if($_POST['action'] == 'removeFromCart'){
            echo removeFromCart($_POST['itemNum']);
        }
        
        if($_POST['action'] == 'checkout'){
            echo checkout();
        }
    }

    function getStateCodes(){
        global $conn;
        $sql="SELECT stateCode FROM states";
        $stmt=$conn->prepare($sql);
        $stmt->execute();
        $records=$stmt->fetchAll(PDO::FETCH_ASSOC);
        $stateSelectString="";
        foreach($records as $record){
            $stateCodeString.="<option>".$record['stateCode']."</option>";
        }
        
        return $stateCodeString;
    }
    
    function loginAttempt($username, $password){
        global $conn;
        $returnArr = array();
        $returnArr['result'] = 'fail';
        $np=array();
        
        $np[':username']=$username;
        $np[':password']=sha1($password);
        
        $sql="SELECT userName, u.userId, roleName
              FROM users u 
                JOIN user_roles ur ON
                  u.userId = ur.userId
                JOIN roles r ON
                  ur.roleId = r.roleId
              WHERE userName=:username AND password=:password";
              
        $stmt=$conn->prepare($sql);
        $stmt->execute($np);
        $record=$stmt->fetch(PDO::FETCH_ASSOC);
        
        if(empty($record)){
            $_SESSION['loggedIn']=false;
            $returnArr['result'] = 'fail';
        }else{
            $_SESSION['loggedIn']=true;
            $_SESSION['username']=$record['userName'];
            $_SESSION['userId']=$record['userId'];
            
            if($record['roleName'] == 'Admin'){
                $_SESSION['isAdmin']=true;
            }else{
                $_SESSION['isAdmin']=false;
            }
    
            $returnArr['result'] = 'success';
        }
        
        return json_encode($returnArr);
    }
    
    function signupAttempt($username, $password, $state){
        global $conn;
        $returnArr = array();
        $returnArr['result'] = 'fail';
        $np=array();
        $np2=array();
        
        $np[':username']=$username;
        $np[':password']=sha1($password);
        $np[':state']=$state;
        
        $sql="INSERT INTO users VALUES (NULL, :username, :password, :state)";
              
        try{
            $stmt=$conn->prepare($sql);
            $stmt->execute($np);
        }  catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062){
                $returnArr['result'] = "duplicate";
            }else{
                $returnArr['result'] = "error";
            }
            return json_encode($returnArr);
        }   
        
        
        $np2[':username']=$username;
        $sql="INSERT INTO user_roles VALUES ((SELECT userID FROM users WHERE username=:username), (SELECT roleId FROM roles WHERE roleName='User'))";
        
        $stmt=$conn->prepare($sql);
        $stmt->execute($np2);

        $np2[':username']=$username;
        $sql="INSERT INTO user_states VALUES ((SELECT userID FROM users WHERE username=:username), (SELECT stateCode FROM states WHERE stateCode='$state'))";

        $returnArr = loginAttempt($username, $password);
        
        return $returnArr;
    }

    function displayAllProducts(){
        global $conn;
        $sql = "SELECT * 
                FROM product 
                ORDER BY NAME";
                
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        

        displayProductListing($records);
    }
    
    function searchProduct($val){
       
        global $conn;
        $np=array();
        $np[':search']=$val;
       
        $sql = "SELECT * 
                FROM product
                WHERE name LIKE CONCAT('%', :search, '%') OR description LIKE CONCAT('%', :search, '%')
                ORDER BY name";
                  
        $stmt = $conn->prepare($sql);
        $stmt->execute($np);
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);;
        
        displayProductListing($records);

    }
    
    
    function advancedProductSearch($title, $description, $priceMin, $priceMax, $orderTitle, $orderPrice, $orderDesc){
        global $conn;
        $np=array();

        $sql="SELECT * FROM product WHERE 1";
        
        if($title != null){
            $sql.=" AND name LIKE CONCAT('%', :title, '%')";
            $np[':title']=$title;
        }
        
        if($description != null){
            $sql.=" AND description LIKE CONCAT('%', :description, '%')";
            $np[':description']=$description;
        }
        
        if($priceMin != null){
            $sql.=" AND price >= :priceMin";
            $np[':priceMin']=$priceMin;
        }
        
        if($priceMax != null){
            $sql.=" AND price <= :priceMax";
            $np[':priceMax']=$priceMax;
        }
        
        if($orderTitle == 'on' || $orderPrice =='on'){
            $sql.= " ORDER BY";
            
            if($orderTitle == 'on'){
                $sql.=" name";
            }
            
            if($orderTitle == 'on' && $orderPrice == 'on')
                $sql.=",";
                
            if($orderPrice =='on'){
                $sql.=" price";
            }
            
            if($orderDesc == 'on'){
                $sql.=" DESC";
            }
        }
    
        $stmt=$conn->prepare($sql);
        $stmt->execute($np);
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);;
        
        displayProductListing($records);
    }
    
    function displayProductListing($records){
        echo "<table class='table table-hover'><tbody>";
        
        foreach($records as $record){
                echo "<tr class='productRecord'>";
                echo "<td>" . "<img src='" . $record["imageURL"] . "' style='height:250px; width:160px;'>" . "</td>";
                echo "<td class='col-md-3'><strong>" . $record["NAME"] . "</strong></td>";
                echo "<td><em>$" . $record["price"] . "</em></td>";
                echo "<td>" . $record["description"] . "</td>";
                
                if(!inCart($record['productId'])){
                    echo "<td><input type='button' id='".$record['productId']."' class='btn btn-primary btn-block addToCartBtn' value='Add to Cart'></td>";
                } else {
                    echo "<td><input type='button' id='".$record['productId']."' class='btn btn-secondary btn-block addToCartBtn' value='Already in Cart!' disabled></td>";
                }
                
                echo "</tr>";
            }
            
        echo "</tbody></table>";
    }
    

    function displayProducts() {
        global $conn;
        $sql = "SELECT * 
                FROM product 
                ORDER BY NAME";
                
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<table class='table table-borderless table-hover'><tbody>";
        
        foreach($records as $record){
                echo "<tr>";
                echo "<td>" . "<img src='" . $record["imageURL"] . "' style='height:250px; width:160px;'>" . "</td>";
                echo "<td>" . $record["NAME"] . "</td>";
                echo "<td>$" . $record["price"] . "</td>";
                echo "<td>" . $record["description"] . "</td>";
                echo "<td><a class='btn btn-primary' href='updateProduct.php?productId=".$record['productId']."'>Update</a></td>";
                
                echo "<form action='inc\deleteProduct.php' onsubmit='return confirmDelete()'>";
                echo "<input type='hidden' name='productId' value= '".$record['productId']."' />";
                echo "<td><input id=deleteBtn type='submit' class='btn btn-danger' value='Remove'></td>";
                echo "</form>";
                echo "</tr>";
            }
            
        echo "</tbody></table>";
    }


    function addToCart($itemNum){
        $returnArr=array();
        if(isset($_SESSION['loggedIn']) and $_SESSION['loggedIn']){
            $cart_item['itemId']=$itemNum;
    
            if(inCart($itemNum)){
                $item['quantity']++;
            }else{
                $cart_item['quantity']=1;
                array_push($_SESSION['cart'], $cart_item);
            }
            
            $returnArr['cartCount']=cartCount();
            $returnArr['result']='added';
        }else{
            $returnArr['result']='notLoggedIn';
        }
        return json_encode($returnArr);
    }
    
    
    function inCart($itemNum){
        $found = false;
        foreach($_SESSION['cart'] as $item){
            if($item['itemId'] == $itemNum){
                $found = true;
            }
        }
        return $found;
    }
    
    function cartCount(){
        $count=0;
        
        if(isset($_SESSION['cart'])){
            foreach($_SESSION['cart'] as $item){
                $count+=$item['quantity'];
            }
        }
        
        return $count;
    }
    
    function getCart(){
        global $conn;
        $taxRate=getUserTaxRate();
        $shipping=0;
        $total=0;
        $tax=0;
        $cartAsString="";
        
        $cartAsString.="<table id='cartTable' class='table table-hover'>";
        
        
        if(empty($_SESSION['cart'])){
            $cartAsString.="<td class='text-center'><strong>Cart Empty</strong></td>"; 
        }else{
            $shipping=getUserShipping();
            $cartAsString.="<thead><th>Title</th><th>Price</th></th><th></th></thead>";
        }
        
        foreach($_SESSION['cart'] as $item){
            $sql = "SELECT name, price 
                    FROM product
                    WHERE productId=".$item['itemId'];
                  
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $record = $stmt->fetch(PDO::FETCH_ASSOC);
    
            $cartAsString.="<tr>";
            $cartAsString.="<td>".$record['name']."</td><td>".$record['price']."</td><td><button class='btn btn-danger removeFromCartBtn' id='".$item['itemId']."'>REMOVE FROM CART</button></td>";
            $cartAsString.="</tr>";
            $tax+=($record['price'] * $taxRate);
            $total+=($record['price']);
        }
        
        $total=money_format('%.2n', $total);
        $tax=money_format('%.2n', $tax);
        $shipping=money_format('%.2n', $shipping);
        
        $cartAsString.="</table>";
        $cartAsString.="<hr />";
        $cartAsString.="<strong>Total: $".($total)."<br />Tax: $".$tax."<br />Shipping: $".$shipping."</strong>";
        $cartAsString.="<hr/>";
        $cartAsString.="<h3><strong>$".money_format('%.2n', ($total + $tax + $shipping))."</strong></h3>";
        $cartAsString.="<button class='btn btn-primary btn-group' id='checkoutBtn'>Checkout</button>";
        
        return $cartAsString;
    }
    
    function getUserTaxRate(){
        global $conn;
        $rate=0;
        $sql = "SELECT stateTax FROM states s
	              JOIN users u ON
		          s.stateCode = u.stateCode
                WHERE userID=".$_SESSION['userId'];
                  
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $record = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $rate=$record['stateTax'];
        return $rate;
    }
    
    function getUserShipping(){
        global $conn;
        $shipping=0;
        
        $sql = "SELECT shipping FROM states s
	              JOIN users u ON
		          s.stateCode = u.stateCode
                WHERE userID=".$_SESSION['userId'];
                  
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $record = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $shipping=$record['shipping'];
        return $shipping;
    }
    
    function removeFromCart($itemNum){
        foreach($_SESSION['cart'] as $itemKey => $item){
            if($item['itemId'] == $itemNum){
                unset($_SESSION['cart'][$itemKey]);
            }
        }
        
        return getCart();
    }
    
    function checkout(){
        global $conn;
        $taxRate=getUserTaxRate();
        $shipping=getUserShipping();
        $returnArr=array();
        $total=0;
        $tax=0;
        $tranId=1;
        $lineNumber=1;
        
        if(cartCount() == 0){
            $returnArr['result']='empty';
            return json_encode($returnArr);
        }
        
        $sql = "SELECT MAX(tranId) AS lastTran FROM TRANSACTION";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $record = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if(!empty($record['lastTran'])){
            $tranId = $record['lastTran'] + 1;
        }
        
        $sql = "INSERT INTO TRANSACTION VALUES (".$tranId.", 0.0, 0.0, ".$shipping.", ".$_SESSION['userId'].")";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        
        foreach($_SESSION['cart'] as $item){
            $sql = "SELECT price, name 
                    FROM product
                    WHERE productId=".$item['itemId'];
                  
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $record = $stmt->fetch(PDO::FETCH_ASSOC);

            $tax+=($record['price'] * $taxRate);
            $total+=($record['price']);
            
            $sql = "INSERT INTO transactionDetails VALUES (".$tranId.", ".$lineNumber.", '".$record['name']."', ".$record['price'].")";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $lineNumber++;
        }
        
        $total=money_format('%.2n', $total);
        $tax=money_format('%.2n',$tax);

        $sql = "UPDATE TRANSACTION SET subtotal=".$total.", tax=".$tax." WHERE tranId=".$tranId;
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        
        foreach($_SESSION['cart'] as $itemKey => $item){
            unset($_SESSION['cart'][$itemKey]);
        }
        
        $returnArr['result']='success';
        
        return json_encode($returnArr);
    }
    
    function getAveragePrice(){
        global $conn;
        $sql = "SELECT AVG(price) AS average FROM product";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $record = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return money_format('%.2n', $record['average']);
    }
    
    function getInventoryValue(){
        global $conn;
        $sql = "SELECT SUM(price) AS sum FROM product";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $record = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return money_format('%.2n', $record['sum']);
    }
    
    function getTopSellingItems(){
        global $conn;
        $items='';
        $count=null;
        $sql = "SELECT name, COUNT(name) AS numSold
                FROM transactionDetails
                GROUP BY name
                ORDER BY numSold
                DESC";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach($records as $record){
            if($count==null){
                $count=$record['numSold'];
            }
            
            if($record['numSold'] == $count){
                $items.="'".$record['name']."'&nbsp&nbsp ";
            }
        }
        
        return $items;
    }
    
    function getWorstSellingItems(){
        global $conn;
        $items='';
        $count=null;
        $sql = "SELECT name, COUNT(name) AS numSold
                FROM transactionDetails
                GROUP BY name
                ORDER BY numSold";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach($records as $record){
            if($count==null){
                $count=$record['numSold'];
            }
            
            if($record['numSold'] == $count){
                $items.="'".$record['name']."'&nbsp&nbsp ";
            }
        }
        
        return $items;
    }
    
    function getAgg($id){
        global $conn;
        $np = array();
        $np[':id'] = $id;
        
        $sql = "SELECT tranId, FORMAT(subtotal, 2) AS subtotal, FORMAT(tax, 2) AS tax, shipping, FORMAT((subtotal + tax + shipping), 2) AS total
                FROM TRANSACTION
                WHERE tranId=:id";
                
        $stmt = $conn->prepare($sql);
        $stmt->execute($np);
        $agg = $stmt->fetch(PDO::FETCH_ASSOC);
        return($agg);
    }
?>


