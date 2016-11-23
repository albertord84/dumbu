$(document).ready(function(){  
    
    /*$("#client_credit_card_name").val(upgradable_datas['credit_card_name']);    
    $("#client_credit_card_number").val(upgradable_datas['credit_card_number']);
    $("#client_credit_card_cvv").val(upgradable_datas['credit_card_cvc']);
    $("#client_credit_card_validate_month").val(upgradable_datas['credit_card_exp_month']);
    $("#client_credit_card_validate_year").val(upgradable_datas['credit_card_exp_year']);*/
    $("#client_email").val(upgradable_datas['email']);
    
    
    $("#btn_cancel_update_datas").click(function() {
        $(location).attr('href',base_url+'index.php/welcome/reload_panel_client');
    });
    
    $("#btn_send_update_datas").click(function() {
        var name=validate_element('#client_credit_card_name', "^[A-Z ]{4,50}$");
        var email=validate_element('#client_email',"^[a-zA-Z0-9\._-]+@([a-zA-Z0-9-]{2,}[.])*[a-zA-Z]{2,4}$");        
        var number=validate_element('#client_credit_card_number',"^[0-9]{16,16}$");
        var cvv=validate_element('#client_credit_card_cvv',"^[0-9 ]{3,4}$");
        var month=validate_month('#client_credit_card_validate_month',"^[0-10-9]{2,2}$");
        var year=validate_year('#client_credit_card_validate_year',"^[2-20-01-20-9]{4,4}$");
        
        if(name && email && number && cvv && month && year){
            var l = Ladda.create(this);  l.start(); l.start();
            $.ajax({
                url : base_url+'index.php/welcome/update_client_datas',
                data : {
                    'client_email':$('#client_email').val(),
                    'client_credit_card_number':$('#client_credit_card_number').val(),
                    'client_credit_card_cvv':$('#client_credit_card_cvv').val(),
                    'client_credit_card_name':$('#client_credit_card_name').val(),
                    'client_credit_card_validate_month':$('#client_credit_card_validate_month').val(),
                    'client_credit_card_validate_year':$('#client_credit_card_validate_year').val()
                },
                type : 'POST',
                dataType : 'json',
                success : function(response) {
                    if(response['success']){
                        alert(response['message']);
                        $(location).attr('href',base_url+'index.php/welcome/reload_panel_client');                                                       
                    } else{
                        alert(response['message']);
                    }
                    l.stop();
                },
                error : function(xhr, status) {
                    l.stop();
                }
            });            
        } else{
            alert('Erro nos dados fornecidos');
        }
        
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
        
        
        
    });  
 }); 