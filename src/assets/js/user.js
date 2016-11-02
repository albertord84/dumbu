$(document).ready(function(){
    
    $("#btn_dumbu_login").click(function() {
        if($('#userLogin').val()!='' && $('#userPassword').val()!=''){
            $("#waiting").css({"visibility":"visible","display":"block"});
            $.ajax({
                url : base_url+'index.php/welcome/user_do_login',      
                data : {
                    'user_login':$('#userLogin').val(),
                    'user_pass': $('#userPassword').val()
                },
                type : 'POST',
                dataType : 'json',
                async: false,
                success : function(response) {
                    if(!response['success']){                        
                        /*TODO: error message*/alert(response['message']);
                    }
                    if(response['resource']){
                        $(location).attr('href',base_url+'index.php/welcome/'+response['resource']);                        
                    }
                    $("#waiting").css({"visibility":"hidden","display":"none"});
                },                
                error : function(xhr, status) {
                    /*TODO: mensaje de ERROR*/alert('noooooooooooooo');                
                }
            });            
        } 
    });
    
    $("#login").click(function(){
        $("#usersLoginForm").fadeIn();
        $("#usersLoginForm").css({"visibility":"visible","display":"block"});
    });
    
    $("#userCloseLogin").click(function(){
        $("#usersLoginForm").fadeOut();
        $("#usersLoginForm").css({"visibility":"hidden","display":"none"});
    }); 
    
    
 }); 