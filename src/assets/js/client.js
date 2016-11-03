$(document).ready(function(){
        
    
    active_by_steep(1);
    
    $("#btn_insta_login").click(function(){
        if($('#clientLogin').val()!='' && $('#clientPassword').val()!=''){
            $("#waiting_sign_in").css({"visibility":"visible","display":"block"});
            $.ajax({            
                url : base_url+'index.php/welcome/check_user_for_sing_in',      
                data : {
                    'client_login':$('#clientLogin').val(),
                    'client_pass': $('#clientPassword').val()
                },
                type : 'POST',
                dataType : 'json',
                success : function(response) {
                    $("#waiting_sign_in").css({"visibility":"hidden","display":"none"});
                    if(response['success']){
                        set_global_var('pk',response['pk']);
                        set_global_var('login',$('#clientLogin').val());
                        set_global_var('pass',$('#clientPassword').val());                        
                        set_global_var('need_delete',response['need_delete']);
                        if(need_delete<response['MIN_MARGIN_TO_INIT']){                        
                            /*TODO: mensaje de WARNING ou DECISAO*/alert('Você precisa desseguer pelo menos '+need_delete+' usuários para que o sistema funcione corretamente');
                        }
                        active_by_steep(2);
                    } else{
                        /*TODO: mensaje de ERROR*/alert(response['message']);
                    }
                },
                error : function(xhr, status) {
                    /*TODO: mensaje de ERROR*/alert('Não foi possível comprobar a autenticidade do usuario no Instagram...');                
                }
            });
        }
    });
    
    
    $("#btn_sing_in").click(function(){       
        var name=validate_element('#client_credit_card_name', "^[A-Z ]{4,50}$");
        var email=validate_element('#client_email',"^[a-zA-Z0-9\._-]+@([a-zA-Z0-9-]{2,}[.])*[a-zA-Z]{2,4}$");        
        var number=validate_element('#client_credit_card_number',"^[0-9]{16,16}$");
        var cvv=validate_element('#client_credit_card_cvv',"^[0-9 ]{3,3}$");        
        var month=validate_month('#client_credit_card_validate_month',"^[0-10-9]{2,2}$");
        var year=validate_year('#client_credit_card_validate_year',"^[2-20-01-20-9]{4,4}$");        
        if(name && email && number && cvv && month && year){
            if( $('#check_declaration').prop('checked') ) {
                $.ajax({
                    url : base_url+'index.php/welcome/check_client_data_bank',
                    data : {
                        'client_email':$('#client_email').val(),
                        'client_credit_card_number':$('#client_credit_card_number').val(),
                        'client_credit_card_cvv':$('#client_credit_card_cvv').val(),
                        'client_credit_card_name':$('#client_credit_card_name').val(),
                        'client_credit_card_validate_month':$('#client_credit_card_validate_month').val(),
                        'client_credit_card_validate_year':$('#client_credit_card_validate_year').val(),
                        'need_delete':need_delete,
                        'pk':pk //el id del usuario deve estar en las cookies, pero cifrado con md5,
                    },
                    type : 'POST',
                    dataType : 'json',
                    success : function(response) {
                        if(response['success']){
                            $(location).attr('href',base_url+'index.php/welcome/re_login?user_login='+login+'&user_pass='+pass);                                                       
                        } else{
                            alert(response['message']);
                        }
                    },
                    error : function(xhr, status) {
                    }
                });
                alert('Dados OK. Inserindo');
            } else{
                alert('Deve aceitar os termos de uso');
            }
        } else{
            alert('Erro nos dados fornecidos no Passo 2');
        }
    });         
    
    function active_by_steep(steep) {
        switch (steep){
            case 1:
                $('#login_panel *').prop('disabled',false);            
                $('#login_panel *').css('color', '#000000');           
                $('#data_panel *').prop('disabled',true);
                $('#data_panel *').css('color', '#7F7F7F');            
                $('#sing_in_panel *').prop('disabled',true);
                $('#sing_in_panel *').css('color', '#7F7F7F');
                break;
            case 2:
                $('#login_panel *').prop('disabled',true);            
                $('#login_panel *').css('color', '#7F7F7F');           
                $('#data_panel *').prop('disabled',false);
                $('#data_panel *').css('color', '#000000');            
                $('#sing_in_panel *').prop('disabled',false);
                $('#sing_in_panel *').css('color', '#000000');
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
        if(!$(element_selector).val().match(pattern) || Number($(element_selector).val())<2017 || Number($(element_selector).val())>2030){
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
        }
    }
    
    var pk, login, pass, need_delete=0;
    
 }); 