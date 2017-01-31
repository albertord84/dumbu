$(document).ready(function(){
    $("#execute_query").click(function(){
        if($("#client_status").val()<=0 && $("#signin_initial_date").val()==='' &&
           $("#signin_final_date").val()==='' && $("#credit_card_expiration_year").val()==='--SELECT--' &&
           $("#pay_day").val()=='--SELECT--' && $("#profile_client").val()==='' &&
           $("#email_client").val()==='' && $("#order_key_client").val()==='' && $("#ds_user_id").val()==='') 
            alert('Deve selecionar pelo menos um critério para filtrar a informação');
        else{
            var params;
            params='client_status='+$("#client_status").val();
            params=params+'&signin_initial_date='+$("#signin_initial_date").val();
            params=params+'&signin_final_date='+$("#signin_final_date").val();
            params=params+'&credit_card_expiration_year='+$("#credit_card_expiration_year").val();
            params=params+'&pay_day='+$("#pay_day").val();
            params=params+'&profile_client='+$("#profile_client").val();
            params=params+'&email_client='+$("#email_client").val();
            params=params+'&order_key_client='+$("#order_key_client").val();
            params=params+'&ds_user_id='+$("#ds_user_id").val();
            $(location).attr('href',base_url+'index.php/admin/list_filter_view?'+params);
        }
        
    });

    /*$("#client_status").change(function(){
        alert($("#client_status").val());
    });*/
    
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
                        alert(response['message']);
                        $(e.currentTarget).attr({"disabled":"true"});
                    } else
                        alert(response['message']);
                },
                error : function(xhr, status) {
                    alert('Não foi possível realizar a operação de cancelamento!');
                }
            });
            l.stop();
        }
    });
    
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
                        alert(response['message']);
                        $(name_row).css({"visibility":"hidden","display":"none"});
                    } else
                        alert(response['message']);
                },
                error : function(xhr, status) {
                    alert('Não foi possível realizar a operação de desactivação!');
                }
            });
            l.stop();
        }
    });
    
    $(".view-ref-prof").click(function(e){
       id=$(e.currentTarget).attr('id');
       alert(id);
    });
    
    
    
    
    
}); 
