//modal ajax
$(document).ready( function(){
    $("#loginBtn").click( function(){
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
        
    $("#signupBtn").click( function(){
        $('#signupModal').modal("show");
        $("#signup").html("<div class='text-center'><img src='img/loading.gif'></div>");
        
        $.ajax({
            type: "POST",
            url: "inc/functions.php",
            datatype: "text",
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
    
    $("#logoutBtn").click(function(){
        $.ajax({
           type: "POST",
           url: "inc/functions.php",
           data:{action: 'logout'},
           success:function(){
               location.reload();
           },
           complete: function(){ // Used for debugging purposes
           }
        });
        
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
            datatype: "text",
            data: {action: 'login', username: username, password: password},
            success: function(data, status){
                if(data=='success'){
                    $('#loginModal').modal('toggle');
                    location.reload();
                }else{
                    $(".btn-group").after('<span class="error">&nbsp;&nbsp;Incorrect Username or Password</span>')
                }
                
            },
            complete: function(data, status){ // Used for debugging purposes
            },
        });
    }
    
});

$(document).on("click", "#signupSubmit", function(event){
    
    if(modalCheck()){
        var username = $('#username').val();
        var password = $('#password').val();
        var state = $('#stateSelect').val();
        console.log(username + " " + password + " " + state);
        $.ajax({
            type: "POST",
            url: "inc/functions.php",
            datatype: "text",
            data: {action: 'signup', username: username, password: password, state: state},
            success:function(data, status){
                if(data=="duplicate"){
                    $(".btn-group").after('<span class="error">&nbsp;&nbsp;Username already exists.</span>')
                } else if (data=='success'){
                    $('#loginModal').modal('toggle');
                    location.reload();
                }
            },
            complete: function(data, status){ // Used for debugging purposes
            }
        });
    }
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