$(document).ready(function(){
    
    $("#btn_send_message").click(function(){
        name=validate_empty('#visitor_name');
        email=validate_element('#visitor_email',"^[a-zA-Z0-9\._-]+@([a-zA-Z0-9-]{2,}[.])*[a-zA-Z]{2,4}$");
        if($('#visitor_phone').val()!='')
            telf=validate_element('#visitor_phone',"^\([0-9]{2,3}\) [0-9]{5}-[0-9{4}$");        
        else
            telf=true;
        message=validate_empty('#visitor_message');
        if(name && email && telf && message){
            $.ajax({
                url : base_url+'index.php/welcome/message',
                data :{ 'name':$("#visitor_name").val(),
                        'company':$("#visitor_company").val(),
                        'email':$("#visitor_email").val(),
                        'telf':$("#visitor_phone").val(),
                        'message':$("#visitor_message").val()
                    },
                type : 'POST',
                dataType : 'json',
                success : function(response){
                    if(response['success']){
                        alert(response['message']);
                        $('#talkme_frm').each (function(){
                            this.reset();
                        });                        
                    } else
                        alert(response['message']);
                },
                error : function(xhr, status) {
                    alert('Erro enviando a mensagem, tente depois...');
                }
            });
        } else{
            alert('Alguns dados incorretos');
        }        
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
    
     function validate_empty(element_selector){
        if($(element_selector).val()===''){
            $(element_selector).css("border", "1px solid red");
            return false;
        } else{
            $(element_selector).css("border", "1px solid gray");
            return true;
        }
    } 
 }); 