$(document).ready(function(){
    
    $("#btn_login").click(function() {
        $.ajax({
            url : base_url+'index.php/welcome/user_do_login',      
            data : {
                'user_login':$('#user_login').val(),
                'user_pass': $('#user_pass').val()
            },
            type : 'POST',
            dataType : 'json',
            async: false,
            success : function(response) {
                if(response['success']){
                    //mostrar panel segun role----------------------------------------------------------
                    $(location).attr('href',base_url+'index.php/welcome/panel_client');
                } else{
                    alert(response['message']);
                    $(location).attr('href',base_url+'index.php/welcome/');
                }
            }            
        });
    });
 }); 