$(document).ready(function(){
    
    active_by_steep(1);
    
    $('#palno_mensal').prop('disabled',true);
    
    
    $("#signin_btn_insta_login").click(function(){
        if($('#signin_clientLogin').val()!='' && $('#signin_clientPassword').val()!='' && $('#client_email').val()!=''){
            if(validate_element('#client_email',"^[a-zA-Z0-9\._-]+@([a-zA-Z0-9-]{2,}[.])*[a-zA-Z]{2,4}$")){
                if(validate_element('#signin_clientLogin','^[a-zA-Z0-9\._]{1,300}$')){
                    //$("#waiting_sign_in").css({"visibility":"visible","display":"block"});                   
                    var l = Ladda.create(this);  l.start(); l.start();
                    $.ajax({            
                        url : base_url+'index.php/welcome/check_user_for_sing_in',      
                        data : {
                            'client_email':$('#client_email').val(),
                            'client_login':$('#signin_clientLogin').val(),
                            'client_pass': $('#signin_clientPassword').val()
                        },
                        type : 'POST',
                        dataType : 'json',
                        success : function(response) {
                            //$("#waiting_sign_in").css({"visibility":"hidden","display":"none"});
                            if(response['success']){                         
                                set_global_var('insta_profile_datas',jQuery.parseJSON(response['datas']));
                                set_global_var('pk',response['pk']);
                                set_global_var('datas',response['datas']);
                                set_global_var('login',$('#signin_clientLogin').val());
                                set_global_var('pass',$('#signin_clientPassword').val());
                                set_global_var('need_delete',response['need_delete']);

                                if(need_delete<response['MIN_MARGIN_TO_INIT']){                        
                                    /*TODO: mensaje de WARNING ou DECISAO*/alert('Você precisa desseguer pelo menos '+need_delete+' usuários para que o sistema funcione corretamente');
                                }
                                active_by_steep(2);
                            } else{
                                /*TODO: mensaje de ERROR*/alert(response['message']);
                                if(response['cause']=='checkpoint_required'){
                                    $(location).attr('href',base_url+'index.php/welcome/verify_account?user_login='+$('#clientLogin').val()+'&verify_link='+response['verify_link']+'&return_link='+response['return_link']);
                                }
                            }
                            l.stop();
                        },
                        error : function(xhr, status) {
                            /*TODO: mensaje de ERROR*/alert('Não foi possível comprobar a autenticidade do usuario no Instagram...');                
                            l.stop();
                        }
                    });                   
                } else {
                    alert('O nome de um perfil só pode conter combinações de letras, números, sublinhados e pontos.');
                }
            } else{
                alert('O email informado não é correto');
            }
        }else {
            alert('Formulario incompleto');
        }
    });
    
    
    $("#btn_sing_in").click(function(){
        $('#btn_sing_in').prop('disabled',true);
        $('#btn_sing_in').css('cursor', 'wait');
        var name=validate_element('#client_credit_card_name', "^[A-Z ]{4,50}$");
        //var email=validate_element('#client_email',"^[a-zA-Z0-9\._-]+@([a-zA-Z0-9-]{2,}[.])*[a-zA-Z]{2,4}$");        
        var number=validate_element('#client_credit_card_number',"^[0-9]{10,20}$");
        var cvv=validate_element('#client_credit_card_cvv',"^[0-9 ]{3,5}$");
        var month=validate_month('#client_credit_card_validate_month',"^[0-10-9]{2,2}$");
        var year=validate_year('#client_credit_card_validate_year',"^[2-20-01-20-9]{4,4}$");
        if(name &&  number && cvv && month && year){
            //if( $('#check_declaration').prop('checked') ) {
                $.ajax({
                    url : base_url+'index.php/welcome/check_client_data_bank',
                    data : {
                        'user_login':login,
                        'user_pass':pass,
                        //'client_email':$('#client_email').val(),
                        'client_credit_card_number':$('#client_credit_card_number').val(),
                        'client_credit_card_cvv':$('#client_credit_card_cvv').val(),
                        'client_credit_card_name':$('#client_credit_card_name').val(),
                        'client_credit_card_validate_month':$('#client_credit_card_validate_month').val(),
                        'client_credit_card_validate_year':$('#client_credit_card_validate_year').val(),
                        'need_delete':need_delete,
                        'pk':pk,
                        'datas':datas
                    },
                    type : 'POST',
                    dataType : 'json',
                    success : function(response) {
                        if(response['success']){
                            alert("Sua compra foi realizada corretamente.");
                            $(location).attr('href',base_url+'index.php/welcome/client');                                                       
                        } else{
                            alert(response['message']);
                            $('#btn_sing_in').prop('disabled',false);
                            $('#btn_sing_in').css('cursor', 'pointer');
                        }
                    },
                    error : function(xhr, status) {
                        $('#btn_sing_in').prop('disabled',false);
                        $('#btn_sing_in').css('cursor', 'pointer');
                    }
                });
            /*} else{
                alert('Deve ler e aceitar os termos de uso');
            }*/
        } else{
            alert('Verifique os dados fornecidos');
            $('#btn_sing_in').prop('disabled',false);
            $('#btn_sing_in').css('cursor', 'pointer');
        }
        $('#btn_sing_in').prop('disabled',false);
        $('#btn_sing_in').css('cursor', 'pointer');
    });         
    
    function active_by_steep(steep) {
        switch (steep){
            case 1:
                $('#login_sign_in').css('visibility','visible');
                $('#indication_login_btn').css('visibility','visible');
                
                $('#container_login_panel *').prop('disabled',false);            
                $('#container_login_panel *').css('color', '#000000');           
                $('#coniner_data_panel *').prop('disabled',true);
                $('#coniner_data_panel *').css('color', '#7F7F7F');            
                $('#container_sing_in_panel *').prop('disabled',true);
                $('#container_sing_in_panel *').css('color', '#7F7F7F');
                
                $('#container_sing_in_panel').height($('#coniner_data_panel').height());  
                $('#container_login_panel').height($('#coniner_data_panel').height());     
                
                $('#container_login_panel').css('background-color','transparent');
                $('#coniner_data_panel').css('background-color','#F5F5F5');
                $('#container_sing_in_panel').css('background-color','#F5F5F5');               
                
                $("#btn_sing_in").hover(function(){$('#btn_sing_in').css('cursor', 'not-allowed');},function(){ });                    
                $("#coniner_data_panel").hover(function(){ $('#coniner_data_panel').css('cursor', 'not-allowed');},function(){ });
                $("#container_sing_in_panel").hover(function(){$('#container_sing_in_panel').css('cursor', 'not-allowed');},function(){ });
                break;
            case 2:
                
                $('#indication_login_btn').css('visibility','hidden');          
                $('#login_sign_in').css('visibility','hidden');
                $('#signin_profile').css('visibility','visible');
                $('#img_ref_prof').attr("src",insta_profile_datas.profile_pic_url);
                $('#name_ref_prof').text(insta_profile_datas.username);
                $('#ref_prof_followers').text('Seguidores '+insta_profile_datas.follower_count);
                $('#ref_prof_following').text('Seguindo '+insta_profile_datas.following);
                
                $('#coniner_data_panel *').prop('disabled',false);
                $('#coniner_data_panel *').css('color', '#000000');            
                $('#container_sing_in_panel *').prop('disabled',false);
                $('#container_sing_in_panel *').css('color', '#000000');  
                
                //$('#container_login_panel').css('background-color','#D3ECC9');
                $('#coniner_data_panel').css('background-color','transparent');
                $('#container_sing_in_panel').css('background-color','transparent');
               
                $("#btn_sing_in").hover(function(){$('#btn_sing_in').css('cursor', 'pointer');}, function(){ });                    
                $("#coniner_data_panel").hover(function(){$('#coniner_data_panel').css('cursor', 'auto');},function(){ });
                $("#container_sing_in_panel").hover(function(){$('#container_sing_in_panel').css('cursor', 'auto');},function(){ });
                
                
                break;
        }        
    }
    
    $("#show_login").click(function(){
        $("#loginform").fadeIn();
        $("#loginform").css({"visibility":"visible","display":"block"});
    });
    
    $("#close_login").click(function(){
        $("#loginform").fadeOut();
        $("#loginform").css({"visibility":"hidden","display":"none"});
    });  
    
    $("#lnk_use_term").click(function(){
        url=base_url+"assets/others/TERMOS DE USO DUMBU.pdf";
        window.open(url, '_blank');
        return false;        
    });
       
    function validate_element(element_selector,pattern){
        if(!$(element_selector).val().match(pattern)){
            $(element_selector).css("border", "1px solid red");
            return false;
        } else{
            $(element_selector).css("border", "1px solid gray");
            return true;
        }
    }
    
    function validate_month(element_selector,pattern){
        if(!$(element_selector).val().match(pattern) || Number($(element_selector).val())>12){
            $(element_selector).css("border", "1px solid red");
            return false;
        } else{
            $(element_selector).css("border", "1px solid gray");
            return true;
        }
    }    
    function validate_year(element_selector,pattern){
        if(!$(element_selector).val().match(pattern) || Number($(element_selector).val())<2017){
           $(element_selector).css("border", "1px solid red");
            return false;
        } else{
            $(element_selector).css("border", "1px solid gray");
            return true;
        }
    }    
    
    function set_global_var(str,value){
        switch (str){
            case 'pk':
                pk=value;
                break;
            case 'need_delete':
                need_delete=value;
                break;
            case 'login':
                login=value;
                break;
            case 'pass':
                pass=value;
                break;
            case 'datas':
                datas=value;
                break;
            case 'insta_profile_datas':
                insta_profile_datas=value;
                break;
        }
    }
    
    
    
    var pk, datas, login, pass,insta_profile_datas, need_delete=0;
    
 }); 