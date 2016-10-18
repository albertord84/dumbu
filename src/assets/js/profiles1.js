$(document).ready(function(){     
    
    
    
    $("#list_profile").show();
    $("#add_profile").hide();
    $("#delete_profile").hide();     
    
    
    
    $("#list_link").click(display_profiles);
    display_profiles();
    function display_profiles() {        
        $.ajax({
            url : base_url+'index.php/welcome/client_list_active_profiles',      
            data : {},
            type : 'POST',
            dataType : 'json',
            success : function(response) {
                if(response['success']){
                    $("#my_active_profiles").empty();
                    var profile=response['data'], N=response['num_row']; 
                    for(i=0;i<N;i++){
                        $('#my_active_profiles').append('<strong>'+profile[i]['insta_name']+'</strong><br>');
                    }
                } else{
                    alert(response['message']);
                }                 
            },
            error : function(xhr, status) {
                alert('Não foi possível comprobar a autenticidade do usuário no Instagram');               
                //$(location).attr('href',base_url+'index.php/welcome/');
            }
        });        
        $("#list_profile").show();    
        $("#add_profile").hide();        
        $("#delete_profile").hide();
        
    }
    
    $("#add_link").click(function() {
        $("#list_profile").hide();    
        $("#add_profile").show();        
        $("#delete_profile").hide();
    });   
    
    $("#btn_add_profile").click(function() {
        $.ajax({
            url : base_url+'index.php/welcome/client_insert_profile',      
            data : {                
                'profile': $("#text_perfil").val(),
            },
            type : 'POST',
            dataType : 'json',
            success : function(response) {                
                if(response['success']){
                    $("#text_perfil").empty();
                    alert(response['message']);
                } else{
                    alert(response['message']);
                }                 
            },
            error : function(xhr, status) {
                alert('Não foi possível comprobar a autenticidade do usuário no Instagram');               
                //$(location).attr('href',base_url+'index.php/welcome/');
            }
        });
    });   
    
    $("#del_link").click(function() {
        $.ajax({
            url : base_url+'index.php/welcome/client_list_active_profiles',
            data : {},
            type : 'POST',
            dataType : 'json',
            success : function(response) {
                if(response['success']){
                    $("#form_profiles").empty();
                    var profile=response['data'], N=response['num_row']; 
                    for(i=0;i<N;i++){                        
                        $('#form_profiles').append('<input type="checkbox" name="cbox[]" value="'+profile[i]['id']+'" style="z-index:2">'+profile[i]['insta_name']+'<br>');
                    }                    
                    $('#form_profiles').append('<br><input id="btn_enviar" type="submit" value="Eliminar" style="z-index:2">');
                } else{
                    alert(response['message']);
                }
            }
        });       
        $("#list_profile").hide();    
        $("#add_profile").hide();        
        $("#delete_profile").show();
    });    
    
    $("#form_profiles").submit(function(){
        $.ajax({
            url : base_url+'index.php/welcome/client_desactive_profiles',
            data : $("#form_profiles > input:checkbox:checked"),
            type : 'POST',
            dataType : 'json',
            success : function(response) {
                $("#del_link").click();
            }
        }); 
        return false;
    });
    
 }); 