$(document).ready(function(){
    
    $('#login_container2').keypress(function (e) {
        if (e.which == 13) {
            $("#execute_query").click();
            return false;
        }
    });
    
    function modal_alert_message(text_message){
        $('#modal_alert_message').modal('show');
        $('#message_text').text(text_message);        
    }
    
    $("#accept_modal_alert_message").click(function () {
        $('#modal_alert_message').modal('hide');
    });
    
    $("#btn_change_ticket_peixe_urbano_status_id").click(function(){        
        var l = Ladda.create(this);  l.start();
        $.ajax({
            url : base_url+'index.php/admin/change_ticket_peixe_urbano_status_id',
            data : {
                'ticket_peixe_urbano_status_id':$("#select_option_ticket_peixe_urbano_status_id").val(),
                'user_id':$('#cupom_container').children('p').attr("id")                
            },
            type : 'POST',
            dataType : 'json',
            success : function(response){
                if(response['success']){
                    $("#btn_change_ticket_peixe_urbano_status_id").attr("disabled",true);
                    $("#select_option_ticket_peixe_urbano_status_id").attr("disabled",true);
                    $("#myModal_2").modal('hide');                    
                    if($("#select_option_ticket_peixe_urbano_status_id").val()==='1'){
                        $("#btn_cupom_"+$('#cupom_container').children('p').attr("id")).attr("class","btn btn-success")
                        $("#option_pending").attr("selected",false);
                        $("#option_confered").attr("selected",true);
                    }    
                    else if($("#select_option_ticket_peixe_urbano_status_id").val()==='3'){                        
                        $("#btn_cupom_"+$('#cupom_container').children('p').attr("id")).attr("class","btn btn-danger");
                        $("#option_wrong").attr("selected",false);
                        $("#option_confered").attr("selected",true);
                    }
                } else
                    modal_alert_message(response['message']);
            },
            error : function(xhr, status) {
                modal_alert_message('Não foi possível realizar a operação de atualização do status do cupom');
            }
        });
        l.stop();
    });
    
    $("#execute_query").click(function(){
        if($("#client_status").val()<=0 && 
           $("#signin_initial_date").val()==='' &&
           $("#signin_final_date").val()==='' &&
           $("#cod_promocional").val()==='--SELECT--' &&
           $("#pay_day").val()=='--SELECT--' &&
           $("#profile_client").val()==='' &&
           $("#email_client").val()==='' &&
           $("#order_key_client").val()==='' &&
           $("#ds_user_id").val()==='' &&
           $("#credit_card_name").val()==='') 
            modal_alert_message('Deve selecionar pelo menos um critério para filtrar a informação');
        else{
            var params;
            params='client_status='+$("#client_status").val();
            params=params+'&signin_initial_date='+$("#signin_initial_date").val();
            params=params+'&signin_final_date='+$("#signin_final_date").val();
            params=params+'&cod_promocional='+$("#cod_promocional").val();
            params=params+'&client_id='+$("#client_id").val();
            params=params+'&profile_client='+$("#profile_client").val();
            params=params+'&email_client='+$("#email_client").val();
            params=params+'&order_key_client='+$("#order_key_client").val();
            params=params+'&ds_user_id='+$("#ds_user_id").val();
            params=params+'&credit_card_name='+$("#credit_card_name").val();
            $(location).attr('href',base_url+'index.php/admin/list_filter_view?'+params);
        }
        
    });
    
    var id=0;
       
    $(".delete-recurence").click(function(e){
       id=$(e.currentTarget).attr('id');
        name_row='#row-client-'+id;        
        if(confirm('Confirma o cancelamento da recorrência?')){
            var l = Ladda.create(this);  l.start();
            $.ajax({
                url : base_url+'index.php/admin/recorrency_cancel',
                data : {'id':id},
                type : 'POST',
                dataType : 'json',
                success : function(response){
                    if(response['success']){
                        modal_alert_message(response['message']);
                        $(e.currentTarget).attr({"disabled":"true"});
                    } else
                        modal_alert_message(response['message']);
                },
                error : function(xhr, status) {
                    modal_alert_message('Não foi possível realizar a operação de cancelamento!');
                }
            });
            l.stop();
        }
    });
    
    /*$('#modal_alert_message').close(function(){
       window.location.href=window.location;
    });*/
    
    $(".desactive-cliente").click(function(e){
        id=$(e.currentTarget).attr('id');
        name_row='#row-client-'+id;        
        if(confirm('Confirma a desativação do cliente?')){
            var l = Ladda.create(this);  l.start();
            $.ajax({
                url : base_url+'index.php/admin/desactive_client',
                data : {'id':id},
                type : 'POST',
                dataType : 'json',
                success : function(response){
                    if(response['success']){
                        modal_alert_message(response['message']);
                        $('label_status_'+id).text('DELETED');
                        $(name_row).css({"visibility":"hidden","display":"none"});                        
                    } else
                        modal_alert_message(response['message']);
                },
                error : function(xhr, status) {
                    modal_alert_message('Não foi possível realizar a operação de desactivação!');
                }
            });
            l.stop();
        }
    });
    
    $(".view-ref-prof").click(function(e){
       id=$(e.currentTarget).attr('id');
       modal_alert_message(id);
    });
    
    $('#admin_form').keypress(function (e) {
        if (e.which == 13) {
            $("#execute_query").click();
            return false;
        }
    });
    
}); 
