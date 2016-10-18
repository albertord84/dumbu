$(document).ready(function(){     
     
    var data_client;
    
    $("#personal_data").show();
    $("#credit_card_data").hide();    
    $("#sumarize_data").hide();  
    $("#sing_in").show();
    $("#login").show();
    $("#logout").hide();
    
    $("#btn_personal_data").click(function() {
        data_client={
            'client_name':$('#client_name').val(),
            'client_login':$('#client_login').val(),
            'client_pass':$('#client_pass').val(),
            'client_email':$('#client_email').val(),
            'client_telf':$('#client_telf').val(),
            'client_languaje':$('#client_languaje').val(),
            'client_insta_id':'',
            'client_insta_followers_ini':0,
            'client_insta_following':0,
            'client_credit_card_number':'',
            'client_credit_card_cvc':'',
            'client_credit_card_name':'',
        };
        $.ajax({
            url : base_url+'index.php/welcome/is_insta_user',      
            data : {     
                'updating':false,
                'client_login':data_client['client_login'],
                'client_pass': data_client['client_pass']
            },
            type : 'POST',
            dataType : 'json',
            success : function(response) {
                if(response['success']){
                    data_client['client_insta_id']=response['insta_id'];
                    data_client['client_insta_followers_ini']=response['insta_followers_ini'];
                    data_client['client_insta_following']=response['insta_following'];                    
                    $("#personal_data").hide();
                    $("#credit_card_data").show("fast");
                } else{
                    alert(response['message']);
                    $(location).attr('href',base_url+'index.php/welcome/');
                }
            },
            error : function(xhr, status) {
                alert('Não foi possível comprobar a autenticidade do usuario no Instagram');
                $(location).attr('href',base_url+'index.php/welcome/');
            }
        });
    });
    
    $("#btn_credit_card_data").click(function() {
        data_client['client_credit_card_number']=$('#client_credit_card_number').val();
        data_client['client_credit_card_cvc']=$('#client_credit_card_cvc').val();
        data_client['client_credit_card_name']=$('#client_credit_card_name').val();        
        $.ajax({
            url : base_url+'index.php/welcome/is_credit_card',
            data : {
                'client_credit_card_number':data_client['client_credit_card_number'],
                'client_credit_card_cvc':data_client['client_credit_card_cvc'],
                'client_credit_card_name':data_client['client_credit_card_name'],
            },
            type : 'POST',
            dataType : 'json',
            success : function(response) {
                if(response['success']){
                    //imprimir en la tabla el resumen de los datos
                    $("#credit_card_data").hide();    
                    $("#sumarize_data").show();
                } else{
                    alert('Dados bancários incorretos');
                }
            },
            error : function(xhr, status) {
                alert('Não foi possível conferir os dados bancários');                
                $(location).attr('href',base_url+'index.php/welcome/');
            }
        });
    });
    
    $("#btn_register_end").click(function() {        
        $.ajax({
            url : base_url+'index.php/welcome/client_sing_in',
            data : {
                'client_name':data_client['client_name'],
                'client_login':data_client['client_login'],
                'client_pass':data_client['client_pass'],
                'client_email':data_client['client_email'],
                'client_telf':data_client['client_telf'],
                'client_languaje':data_client['client_languaje'],
                'client_insta_id':data_client['client_insta_id'],
                'client_insta_followers_ini':data_client['client_insta_followers_ini'],
                'client_insta_following':data_client['client_insta_following'],
                'client_credit_card_number':data_client['client_credit_card_number'],
                'client_credit_card_cvc':data_client['client_credit_card_cvc'],
                'client_credit_card_name':data_client['client_credit_card_name'],
            },
            type : 'POST',
            dataType : 'json',
            async: false,
            success : function(response) {
                if(response['success']){
                    alert('Usuario cadastrado corretamente');
                    $(location).attr('href',base_url+'index.php/welcome/panel_client');
                    /*
                    $.ajax({
                        url : base_url+'index.php/welcome/user_do_login',
                        data : {                            
                            'client_login':data_client['client_login'],
                            'client_pass':data_client['client_pass'],
                        },
                        type : 'POST',
                        dataType : 'json',
                        async: false,
                        success : function(response) {                            
                            alert
                            $(location).attr('href',base_url+'index.php/welcome/panel_client');
                        },
                    });*/
                } else{
                    alert(response['message']);
                }
            },
            error : function(xhr, status) {
                alert('errrrrrrrrrrrrrrror en el php');
            }
        });
    });
    
    $("#btn_register_cancel").click(function() {
        $(location).attr('href',base_url+'index.php/welcome/');
    });
 }); 