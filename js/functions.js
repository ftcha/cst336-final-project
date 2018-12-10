//ajax
$(document).on("click", "#loginBtn", function(event){
    $('#loginModal').modal("show");
    $("#login").html("<div class='text-center'><img src='img/loading.gif'></div>");
    
    $.ajax({
        success: function() {
            $("#login").html("<div class='form' id='signinForm'><div class='form-group'><span class='userPrompt'>Username:</span><div class='input-group'><span class='input-group-addon'><i class='glyphicon glyphicon-user'></i></span><input type='text' id='username' name='username' class='form-control' placeholder='Username'/><br /></div></div><div class='form-group'><span class='userPrompt'>Password: </span><div class='input-group'><span class='input-group-addon'><i class='glyphicon glyphicon-lock' aria-hidden='true'></i></span><input type='password' id='password' name='password' placeholder='Password' class='form-control'/><br /></div></div></div>"); 
            $("#login").append("<div class='btn-group'><button type='button' id='loginSubmit' class='btn btn-default' aria-label='Login'>Login</button></div>");
            $("#loginModalLabel").html("<span class='modalTitle text-center'>Login</div>");                   
        },
        complete: function() { // Used for debugging purposes
        }
    });
}); 
        
$(document).on("click", "#signupBtn", function(event){
    $('#signupModal').modal("show");
    $("#signup").html("<div class='text-center'><img src='img/loading.gif'></div>");
        
    $.ajax({
        type: "POST",
        url: "inc/functions.php",
        dataType: "text",
        data: {action: 'getStates'},
        success: function(data, status) {
            $("#signup").html("<div class='form' id='signupForm'><div class='form-group'><span class='userPrompt'>Username:</span><div class='input-group'><span class='input-group-addon'><i class='glyphicon glyphicon-user'></i></span><input type='text' id='username' name='username' class='form-control' placeholder='Username'/><br /></div></div><div class='form-group'><span class='userPrompt'>Password: </span><div class='input-group'><span class='input-group-addon'><i class='glyphicon glyphicon-lock' aria-hidden='true'></i></span><input type='password' id='password' name='password' placeholder='Password' class='form-control'/><br /></div>");   
            $("#signup").append("<label for='stateSelect'>Select State</label>");
            $("#signup").append("<select class='form-control' id='stateSelect'>" + data + "</select></div></div>");
            $("#signup").append("<br /><div class='btn-group'><button type='button' id='signupSubmit' class='btn btn-default' aria-label='Sign Up'>Sign Up</button></div>");
           
            $("#signupModalLabel").html("<span class='modalTitle text-center'>Sign Up</span>");                   
        },
        complete: function(data, status) { // Used for debugging purposes
        }
    });
});

$(document).on("click", "#cartBtn", function(event){
   $('#cartModal').modal("show"); 
   $('#cart').html("<div class='text-center'><img src='img/loading.gif'></div>");
   
   $.ajax({
        type: "POST",
        url: 'inc/functions.php',
        dataType: "text",
        data:{action: 'getCart'},
        success: function(data, status){
            $('#cart').html(data);
            $("#cartModalLabel").html("<span class='modalTitle text-center'>Cart</span>");
        },
        complete: function(data, status){ // Used for debugging purposes
           
        }
   });
});

$(document).on("click", "#checkoutBtn", function(event){
    $.ajax({
        type: 'POST',
        url: 'inc/functions.php',
        dataType: "json",
        data:{action: "checkout"},
        success: function(data, status){
            if(data.result=='success'){
                alert("Checkout Complete!");
                $('#cartModal').modal('toggle');
                location.reload();
            }else if(data.result=='empty'){
                $(".btn-group").after('<span class="error">&nbsp;&nbsp;Cart empty, cannot check out.</span>')
            }
        },
        complete: function(data, status){ // Used for debuggin purposes
        }
    });
});

$(document).on("click", ".removeFromCartBtn", function(event){
    var itemNum=$(this).attr('id');
    
    $.ajax({
        type: "POST",
        url: 'inc/functions.php',
        dataType: "text",
        data:{action: 'removeFromCart', itemNum: itemNum},
        success:function(data, status){ //removes cart then returns new cart minus deleted item as string up update cart in real time
            $("#cart").html(data);
            $("#cartModalLabel").html("<span class='modalTitle text-center'>Cart</span>");
            $("#navigation").load('inc/header.php #navigation');
            document.getElementById(itemNum).value = "Add to Cart";
            document.getElementById(itemNum).disabled = false;
            document.getElementById(itemNum).className= "btn btn-primary btn-block addToCartBtn";
        },
        complete: function(){ // Used for debugging purposes
        }
    });
});

$(document).on("click" ,"#logoutBtn", function(event){
    $.ajax({
        type: "POST",
        url: "inc/functions.php",
        dataType: "none",
        data:{action: 'logout'},
        complete: function(){
        location.reload();
        }
    });
});

//modal submit ajax
$(document).on("click", "#loginSubmit", function(event){

    if(modalCheck()){
        var username = $('#username').val();
        var password= $('#password').val();
        
        $.ajax({
            type: "POST",
            url: "inc/functions.php",
            dataType: "json",
            data: {action: 'login', username: username, password: password},
            success: function(data, status){
                if(data.result=='success'){
                    $('#loginModal').modal('toggle');
                    $("#navigation").load('inc/header.php #navigation');
                }else{
                    $(".btn-group").after('<span class="error">&nbsp;&nbsp;Incorrect Username or Password</span>')
                }
            },
            complete: function(data, status){ // Used for debugging purposes
            }
        });
    }
});

$(document).on("click", "#signupSubmit", function(event){
    if(modalCheck()){
        var username = $('#username').val();
        var password = $('#password').val();
        var state = $('#stateSelect').val();
      
        $.ajax({
            type: "POST",
            url: "inc/functions.php",
            dataType: "json",
            data: {action: 'signup', username: username, password: password, state: state},
            success:function(data, status){
                if(data.result=='duplicate'){
                    $(".modal .btn-group").after('<span class="error">&nbsp;&nbsp;Username already exists.</span>')
                } else if (data.result=='success'){
                    $('#signupModal').modal('toggle');
                    $("#navigation").load('inc/header.php #navigation');
                }
            },
            complete: function(data, status){ // Used for debugging purposes
            }
        });
    }
});


$(document).on("click", ".addToCartBtn", function(event){
    var itemNum=$(this).attr('id');
    
    $.ajax({
        type: "POST",
        url: "inc/functions.php",
        dataType: "json",
        data: {action: 'addToCart', itemNum: itemNum},
        success: function(data, status){
            if(data.result=='added'){
                document.getElementById(itemNum).value = "Added!";
                document.getElementById(itemNum).disabled = true;
                $("#cartCount").html(data.cartCount);
            }else if(data.result=='notLoggedIn'){
                alert("You must log in or sign up to be able to add items to your cart.");
            }
        },
        complete: function(data, status){
        }
    });
});

//functions
function modalCheck(){
    var returnVal = false;
    $(".error").remove();
    
    if(!$('#username').val() && !$('#password').val()){
        $('.input-group-addon .glyphicon-user').after(' <span class="error">*</span>');
        $('.glyphicon-lock').after(' <span class="error">*</span>');
    }else if(!$('#username').val()){
        $('.input-group-addon .glyphicon-user').after(' <span class="error">*</span>');
    }else if(!$('#password').val()){
        $('.glyphicon-lock').after(' <span class="error">*</span>');
    }else{
        returnVal = true;
    }
    
    return returnVal;
}

function confirmDelete(){
    return confirm("Are you sure you want to delete the product?");
}