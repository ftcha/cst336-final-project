<?php
    session_start();
    include 'functions.php';
    
    if (empty($_SESSION['cart'])){
        $_SESSION['cart'] = array();
    }
?>

<!DOCTYPE html>
<html lang='en'>
    <head>
        <title>Used VHS Emporium</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="js/functions.js"></script>
        <style>
            @import URL('css/styles.css');
        </style>
    </head>
    <body>
        
        <!-- Navbar here -->
     
        <nav id="navigation" class="navbar navbar-inverse navbar-fixed-top">
            <div class="container-fluid">
                <div class='navbar-header'>
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#headerNav">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.php"">Used VHS Emporium</a>
                </div>
                
                <div class="collapse navbar-collapse" id="headerNav">
                        
                    <ul class="nav navbar-nav">
                        <li class="<?=($_SERVER['PHP_SELF']=='/cst336/final/index.php' || $_SERVER['PHP_SELF']=='/final/index.php'?'active':'')?>"> <!-- change active depending on what page user is on -->
                                <a href="index.php" <?=($_SERVER['PHP_SELF']=='final/index.php'?'active':'')?>><i class="glyphicon glyphicon-home" aria-hidden='true'></i>&nbsp;Home</a>
                        </li> 
                        <li class="<?=($_SERVER['PHP_SELF']=='/cst336/final/shop.php' || $_SERVER['PHP_SELF']=='/final/shop.php'?'active':'')?>">
                            <a href="shop.php"><i class="glyphicon glyphicon-film" aria-hidden='true'></i>&nbsp;Shop</a>
                        </li>
                        <!-- Only display if admin is logged in -->
                        <?=$_SESSION['isAdmin']==true?'
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Admin&nbsp;<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="#">Admin Page 1</a>
                                    </li>
                                    <li>
                                        <a href="products.php">Products</a>
                                    </li>
                                </ul>
                            </li>
                        ':''?>

                        <li>
                            <form class="navbar-form" action="shop.php" method="POST">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search" name="searchVal">
                                    <div class="input-group-btn">
                                        <button class="btn btn-default" type="submit" id="searchBtn">
                                            <i class="glyphicon glyphicon-search" aria-hidden='true'></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </li>
                    </ul>
                    
                    
                    <!-- Signup/login here -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- If user is logged in then show cart else show sign up button-->
                        <?=$_SESSION['loggedIn']==true?'
                            <li>
                                <a href="#" id="cartBtn"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span>&nbsp;Cart: <span id="cartCount">'.cartCount().'</span></a>
                                <li><a href="#" id="logoutBtn"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>&nbsp;Logout</a></li>
                            </li>'
                        :'
                            <li>
                                <a href="#" id="signupBtn"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp;Sign Up</a>
                                <li><a href="#" id="loginBtn"><span class="glyphicon glyphicon-log-in" aria-hidden="true"></span>&nbsp;Login</a></li>
                            </li>
                        '?>
                    </ul>
                </div>
            </div>
        </nav>
        
<?php
    include 'modals.php';
?>
