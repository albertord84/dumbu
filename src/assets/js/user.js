$(document).ready(function(){   
    
    $("#btn_dumbu_login").click(function() {
        if($('#userLogin').val()!='' && $('#userPassword').val()!==''){
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
                    if(response['authenticated']){  
                        if(response['resource']){
                            $(location).attr('href',base_url+'index.php/welcome/'+response['resource']+'');
                        }                        
                    } else
                        if(response['cause']=='checkpoint_required') {
                            alert(response['message']);
                            $(location).attr('href',base_url+'index.php/welcome/verify_account?user_login='+$('#userLogin').val()+'&verify_link='+response['verify_link']+'&return_link='+response['return_link']);
                        } else
                            alert(response['message']);                    
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
    
    $("#promotional_btn").click(function(){
        $(location).attr('href',base_url+'index.php/welcome/sign_in');
    });    
    $('#promotional_btn').hover(
        function () { $(this).css({"border":"1px solid silver"}); }, 
        function () { $(this).css({"border":"2px solid #28BB93"});}
     );
    
    $("#signin_btn").click(function(){
        $(location).attr('href',base_url+'index.php/welcome/sign_in');
    }); 
    $('#signin_btn').hover(
        function () { $(this).css({"border":"1px solid silver"}); }, 
        function () { $(this).css({"border":"2px solid #28BB93"});}
     );
    
    
 }); 