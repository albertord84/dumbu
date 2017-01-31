$(document).ready(function(){   
    $("#btn_dumbu_login1").click(function() {        
        do_login('#userLogin1','#userPassword1', '#container_login_message1',this);
    });
    
    $("#btn_dumbu_login2").click(function() {        
        do_login('#userLogin2','#userPassword2', '#container_login_message2',this);
    });
    
    $('#google_conversion_frame').ready(function(){        
        $('#google_conversion_frame').css({"float": "none","display":"none"});
    } );
        
    function do_login(fieldLogin,fieldPass, fieldErrorMessage, object){
        if($(fieldLogin).val()!='' && $(fieldPass).val()!==''){
            if(validate_element(fieldLogin,'^[a-zA-Z0-9\._]{1,300}$')){
                var l = Ladda.create(object);  l.start();
                $.ajax({
                    url : base_url+'index.php/welcome/user_do_login',      
                    data : {
                        'user_login':$(fieldLogin).val(),
                        'user_pass': $(fieldPass).val()
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
                            if(response['cause']=='checkpoint_required') {
                                var cad=base_url+'index.php/welcome/client?'+'checkpoint_required='+$(fieldLogin).val()+'&verify_link='+response['verify_link']+'&return_link='+response['return_link'];                                
                                $(location).attr('href',cad);                            
                            }    
                        } else{                            
                            if(response['cause']=='phone_verification_settings') {
                                $(fieldErrorMessage).text(response['message']);
                                $(fieldErrorMessage).css('visibility','visible');
                                $(fieldErrorMessage).css('color','red');
                                l.stop();
                            } else
                                if(response['cause']=='empty_message'){
                                    $(fieldErrorMessage).text(response['message']);
                                    $(fieldErrorMessage).css('visibility','visible');
                                    $(fieldErrorMessage).css('color','red');
                                    l.stop();
                                } else 
                                    if(response['cause']=='unknow_message'){
                                        $(fieldErrorMessage).text(response['message']);
                                        $(fieldErrorMessage).css('visibility','visible');
                                        $(fieldErrorMessage).css('color','red');
                                        l.stop();
                                    }
                                    else{
                                        $(fieldErrorMessage).text(response['message']);
                                        $(fieldErrorMessage).css('visibility','visible');
                                        $(fieldErrorMessage).css('color','red');
                                        l.stop();   
                                    }                                
                        }
                    },                
                    error : function(xhr, status) {
                        alert('Não foi possível comunicar com o Instagram. Confira sua conexão com Intenet e tente novamente');    
                        l.stop();
                    }
                });   
            } else{
                $(fieldErrorMessage).text('O nome de um perfil só pode conter combinações de letras, números, sublinhados e pontos.');
                $(fieldErrorMessage).css('visibility','visible');
                $(fieldErrorMessage).css('color','red');
            }       
        } else{
            $(fieldErrorMessage).text('Deve preencher todos os dados corretamente.');
            $(fieldErrorMessage).css('visibility','visible');
            $(fieldErrorMessage).css('color','red');
        }
    }
    
     $('#login_container1').keypress(function (e) {
        if (e.which == 13) {
            $("#btn_dumbu_login1").click();
            return false;
        }
    });
    
    $('#login_container2').keypress(function (e) {
        if (e.which == 13) {
            $("#btn_dumbu_login2").click();
            return false;
        }
    });
    
    
    $("#help").click(function(){
        url=base_url+"index.php/welcome/help";
        window.open(url, '_blank');
    });
    
    
    $("#help").hover(
        function(){
            $('#help').css('cursor', 'pointer');
        },
        function(){
            $('#help').css('cursor', 'default');
        }
    );
    
    
    $("#login").click(function(){
        $("#usersLoginForm").fadeIn();
        $("#usersLoginForm").css({"visibility":"visible","display":"block"});
    });
    
    $("#userCloseLogin").click(function(){
        $("#usersLoginForm").fadeOut();
        $("#usersLoginForm").css({"visibility":"hidden","display":"none"});
    });     
    
    $('#promotional_btn').hover(
        function () { $(this).css({"border":"1px solid silver"});}, 
        function () { $(this).css({"border":"1px solid #28BB93"});}
     ); 
    
    $('#signin_btn_insta_login').css({"color":"white"});
    
    $('#botao-assinar').hover(
        function () { 
            $(this).attr("src",base_url+"assets/img/BOTAO ASSINAR AGORA-hover.png")
            $(this).css({"cursor":"pointer"})
        }, 
        function () {$(this).attr("src",base_url+"assets/img/BOTAO ASSINAR AGORA.png")}
     ); 
     
    $('#botao-assinar').mousedown(function(){
        $("#botao-assinar").attr("src",base_url+"assets/img/BOTAO ASSINAR AGORA-mdown.png");
    });
    $('#botao-assinar').mouseup(function(){
        $("#botao-assinar").attr("src",base_url+"assets/img/BOTAO ASSINAR AGORA.png");
    });
    
     
    $('#img_to_promotional_btn').mousedown(function(){
        $("#img_to_promotional_btn").attr("src",base_url+"assets/img/black-friday/assinar_agora_black_friday_mouse_down.png");
    });
    $('#img_to_promotional_btn').mouseup(function(){
        $("#img_to_promotional_btn").attr("src",base_url+"assets/img/black-friday/assinar_agora_black_friday.png");
    });
    $('#img_to_promotional_btn').hover(
        function () { 
                $("#img_to_promotional_btn").attr("src",base_url+"assets/img/black-friday/assinar_agora_black_friday_mouse_over.png");
            }, 
        function () { 
                $("#img_to_promotional_btn").attr("src",base_url+"assets/img/black-friday/assinar_agora_black_friday.png");
            }
     ); 
    
    $('#signin_btn').hover(
        function () { $(this).css({"border":"1px solid silver"}); }, 
        function () { $(this).css({"border":"2px solid #28BB93"});}
     );
    
    
     function validate_element(element_selector,pattern){
        if(!$(element_selector).val().match(pattern)){
            $(element_selector).css("border", "1px solid red");
            return false;
        } else{
            $(element_selector).css("border", "1px solid gray");
            return true;
        }
    }    
 }); 