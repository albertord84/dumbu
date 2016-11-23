$(document).ready(function(){   
    $("#btn_dumbu_login").click(function() {
        if($('#userLogin').val()!='' && $('#userPassword').val()!==''){
            //$("#waiting").css({"visibility":"visible","display":"block"});
            var l = Ladda.create(this);  l.start(); l.start();
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
                        if(response['role']=='ADMIN'){                            
                            var cad=base_url+'index.php/admin/index?'+response['str'];              
                            $(location).attr('href',cad);
                        } else
                        if(response['role']=='ATTENDET'){
                            $(location).attr('href',base_url+'index.php/attendent/');
                        } else
                        if(response['role']=='CLIENT'){
                            $(location).attr('href',base_url+'index.php/welcome/'+response['resource']+'');
                        } 
                    } else
                        if(response['cause']=='checkpoint_required') {
                            alert(response['message']);
                            var cad=base_url+'index.php/welcome/verify_account?'+'user_login='+$('#userLogin').val()+'&verify_link='+response['verify_link']+'&return_link='+response['return_link'];
                            $(location).attr('href',cad);                            
                        } else{
                            alert(response['message']);
                            //$(location).attr('href',base_url+'index.php/welcome/'+response['resource']);
                        }
                            
                    //$("#waiting").css({"visibility":"hidden","display":"none"});
                    l.stop();
                },                
                error : function(xhr, status) {
                    /*TODO: mensaje de ERROR*/alert('internal error');    
                    l.stop();
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
    
    $('#promotional_btn').hover(
        function () { $(this).css({"border":"1px solid silver"}); }, 
        function () { $(this).css({"border":"2px solid #28BB93"});}
     );    
    
    $('#signin_btn').hover(
        function () { $(this).css({"border":"1px solid silver"}); }, 
        function () { $(this).css({"border":"2px solid #28BB93"});}
     );
    
    
 }); 