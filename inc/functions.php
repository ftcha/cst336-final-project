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
        $loginStatus='fail';
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
            $loginStatus='fail';
        }else{
            $_SESSION['loggedIn']=true;
            $_SESSION['username']=$record['userName'];
            $_SESSION['userId']=$record['userId'];
            
            if($record['roleName'] == 'Admin'){
                $_SESSION['isAdmin']=true;
            }else{
                $_SESSION['isAdmin']=false;
            }
    
            $loginStatus='success';
        }
        
        return $loginStatus;
    }
    
    function signupAttempt($username, $password, $state){
        global $conn;
        $loginStatus='fail';
        $np=array();
        $np2=array();
        
        $np[':username']=$username;
        $np[':password']=sha1($password);
        
        $sql="INSERT INTO users VALUES (NULL, :username, :password)";
              
        try{
            $stmt=$conn->prepare($sql);
            $stmt->execute($np);
        }  catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062){
                $loginStatus="duplicate";
            }else{
                $loginStatus="error";
            }
            
            return $loginStatus;
        }   
        
        
        $np2[':username']=$username;
        $sql="INSERT INTO user_roles VALUES ((SELECT userID FROM users WHERE username=:username), (SELECT roleId FROM roles WHERE roleName='User'))";
        
        $stmt=$conn->prepare($sql);
        $stmt->execute($np2);

        $np2[':username']=$username;
        $sql="INSERT INTO user_states VALUES ((SELECT userID FROM users WHERE username=:username), (SELECT stateCode FROM states WHERE stateCode='$state'))";
        
        try{
            $stmt=$conn->prepare($sql);
            $stmt->execute($np2);
        } catch (PDOException $e){
            $loginStatus="3";
            return $loginStatus;
        }
        
        $loginStatus = loginAttempt($username, $password);
        
        return $loginStatus;
    }

?>