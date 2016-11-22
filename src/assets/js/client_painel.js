$(document).ready(function(){  
    
    
    
    var icons_profiles={            
        0:{'ptr_img_obj':$('#img_ref_prof0'),'ptr_p_obj':$('#name_ref_prof0'),'ptr_panel_obj':$('#reference_profile0'),'img_profile':'','login_profile':'','status_profile':''},
        1:{'ptr_img_obj':$('#img_ref_prof1'),'ptr_p_obj':$('#name_ref_prof1'),'ptr_panel_obj':$('#reference_profile1'),'img_profile':'','login_profile':'','status_profile':''},
        2:{'ptr_img_obj':$('#img_ref_prof2'),'ptr_p_obj':$('#name_ref_prof2'),'ptr_panel_obj':$('#reference_profile2'),'img_profile':'','login_profile':'','status_profile':''},
        3:{'ptr_img_obj':$('#img_ref_prof3'),'ptr_p_obj':$('#name_ref_prof3'),'ptr_panel_obj':$('#reference_profile3'),'img_profile':'','login_profile':'','status_profile':''},
        4:{'ptr_img_obj':$('#img_ref_prof4'),'ptr_p_obj':$('#name_ref_prof4'),'ptr_panel_obj':$('#reference_profile4'),'img_profile':'','login_profile':'','status_profile':''}
        /*5:{'ptr_img_obj':$('#img_ref_prof5'),'ptr_p_obj':$('#name_ref_prof5'),'ptr_panel_obj':$('#reference_profile5'),'img_profile':'','login_profile':'','status_profile':''},
        6:{'ptr_img_obj':$('#img_ref_prof6'),'ptr_p_obj':$('#name_ref_prof6'),'ptr_panel_obj':$('#reference_profile6'),'img_profile':'','login_profile':'','status_profile':''},
        7:{'ptr_img_obj':$('#img_ref_prof7'),'ptr_p_obj':$('#name_ref_prof7'),'ptr_panel_obj':$('#reference_profile7'),'img_profile':'','login_profile':'','status_profile':''},
        8:{'ptr_img_obj':$('#img_ref_prof8'),'ptr_p_obj':$('#name_ref_prof8'),'ptr_panel_obj':$('#reference_profile8'),'img_profile':'','login_profile':'','status_profile':''},
        9:{'ptr_img_obj':$('#img_ref_prof9'),'ptr_p_obj':$('#name_ref_prof9'),'ptr_panel_obj':$('#reference_profile9'),'img_profile':'','login_profile':'','status_profile':''}*/
    };    
    
    var num_profiles,flag=false;
    
    $("#reference_profile0").click(function(){
        delete_profile_click($("#name_ref_prof0"));
    });
    $("#reference_profile1").click(function(){
        delete_profile_click($("#name_ref_prof1"));
    });
    $("#reference_profile2").click(function(){
        delete_profile_click($("#name_ref_prof2"));
    });
    $("#reference_profile3").click(function(){
        delete_profile_click($("#name_ref_prof3"));
    });
    $("#reference_profile4").click(function(){
        delete_profile_click($("#name_ref_prof4"));
    });
   
   function delete_profile_click(element){
       if(confirm('Deseja elimiar o perfil de referência '+element.text())){
            $.ajax({
                url : base_url+'index.php/welcome/client_desactive_profiles',
                data : {'profile':element.text()},
                type : 'POST',
                dataType : 'json',
                success : function(response){
                    if(response['success']){
                        delete_icons_profiles(element.text());                                      
                    } else
                        alert(response['message']);
                },
                error : function(xhr, status) {
                    alert('Não foi possível conectar com o Instagram');
                }
            });
        }
   }
    
    $("#btn_insert_profile").click(function(){
        if(num_profiles<MAX_NUM_PROFILES){
            if($('#login_profile').val()!=''){
                $("#waiting_inser_profile").css({"visibility":"visible","display":"block"});
                $.ajax({
                    url : base_url+'index.php/welcome/client_insert_profile',
                    data : {'profile':$('#login_profile').val()},
                    type : 'POST',
                    dataType : 'json',
                    success : function(response){
                        if(response['success']){
                            inser_icons_profiles(response);
                            $('#login_profile').val('');
                            $("#insert_profile_form").fadeOut();
                            $("#insert_profile_form").css({"visibility":"hidden","display":"none"});                            
                        } else
                            alert(response['message']);                        
                        $("#waiting_inser_profile").css({"visibility":"hidden","display":"none"});
                    },
                    error : function(xhr, status) {
                        alert('Não foi possível conectar com o Instagram');
                    }
                });               
            }
        } else{
            alert('Alcançou a quantidade maxima permitida');
        }
    });
    
    $("#adding_profile").click(function(){
        if(num_profiles<MAX_NUM_PROFILES){
            $("#insert_profile_form").fadeIn();
            $("#insert_profile_form").css({"visibility":"visible","display":"block"});
        } else
            alert('Alcançou a quantidade maxima permitida');        
    });
    
    $("#adding_profile").hover(function(){},function(){});
    
    $("#close_palnel_insert_profile").click(function(){
        $("#insert_profile_form").fadeOut();
        $("#insert_profile_form").css({"visibility":"hidden","display":"none"});
    });  
      
    function display_reference_profiles(){ 
        status_messages['warning'][2]='';
        status_messages['warning'][3]='';
        if(num_profiles==0){
            $('#missing_referrence_profiles').css({"z-index":"5","visibility":"visible","display":"block"});                        
            if($("#status_text").text()=='ATIVO' && !flag){                
                status_messages['warning'][2]='O Dumbu precisa que você adicione perfis de referência para poder começar a recever o serviço;';
                $("#status_text").css({'color':'red'});
                $("#status_text").text('NÂO INICIADO');
                flag=true; 
            }            
        }
        else{
            $('#missing_referrence_profiles').css({"visibility":"hidden","display":"none"}); 
            if(flag==true){
                status_messages['warning'][2]='';
                $("#status_text").css({'color':'green'});
                $("#status_text").text('ATIVO');
                flag=false;
            }
            var any_private_profile=false;
            for(i=0;i<num_profiles;i++){
                if(icons_profiles[i]['status_profile']==='privated')
                    any_private_profile=true;
            }            
            if(any_private_profile)
                status_messages['warning'][3]='Exitem perfis de referencia privados, considere trocar por outros;';                
            
        }
                
        $("#list_warnings").empty();
        
        if(status_messages['danger'][0]){
            $("#list_warnings").append('<div class="alert alert-danger" role="alert"><ul id="danger_warnings"></ul></div>');
            for(i=1;i<=status_messages['danger'][0];i++){
                if(status_messages['danger'][i]!='')
                    $("#danger_warnings").append('<li>'+status_messages['danger'][i]);
            }
        }
        if(status_messages['warning'][1]!='' || status_messages['warning'][2]!='' || status_messages['warning'][3]!=''){
            $("#list_warnings").append('<div class="alert alert-warning" role="alert"><ul id="warning_warnings"></ul></div>');
            for(i=1;i<=3;i++){
                if(status_messages['warning'][i]!='')                    
                    $("#warning_warnings").append('<li>'+status_messages['warning'][i]);
            }
        }
        if(status_messages['info'][0]){
            $("#list_warnings").append('<div class="alert alert-info" role="alert"><ul id="info_warnings"></ul></div>');
            for(i=1;i<=status_messages['info'][0];i++){
                if(status_messages['info'][i]!='')
                    $("#info_warnings").append('<li>'+status_messages['info'][i]);
            }
        }
        
        for(i=0;i<num_profiles;i++){
            icons_profiles[i]['ptr_img_obj'].attr("src",icons_profiles[i]['img_profile']);
            icons_profiles[i]['ptr_p_obj'].text(icons_profiles[i]['login_profile']);
            if(icons_profiles[i]['status_profile']==='privated'||icons_profiles[i]['status_profile']==='deleted')
                icons_profiles[i]['ptr_p_obj'].css({'color':'red'});
            else
                icons_profiles[i]['ptr_p_obj'].css({'color':'black'});
            icons_profiles[i]['ptr_panel_obj'].css({"visibility":"visible","display":"block"});
        }
        
        
        
    }
    
    function display_reference_profiles2(){
        flag=false;
        if(num_profiles==0){
            $('#missing_referrence_profiles').css({"z-index":"5","visibility":"visible","display":"block"});                        
            if($("#status_text").text()=='ATIVO' && !flag){
                alert($("#middle_warnings"));
                if(!$("#middle_warnings")){
                    $("#list_warnings").prepend('<div id="container_middle_warnings" class="alert alert-warning" role="alert"><ul id="middle_warnings"></ul></div>');
                }                
                $("#middle_warnings").prepend('<li id="missing_st"> O Dumbu precisa que você adicione perfis de referência para poder começar a recever o serviço;');
                
                $("#status_text").css({'color':'red'});
                $("#status_text").text('NÂO INICIADO');
                flag=true;
            }            
        }
        else{
            $('#missing_referrence_profiles').css({"visibility":"hidden","display":"none"}); 
            if(flag=true){
                $("#missing_st").remove();
                if(!$("#middle_warnings").children())
                    $("#container_middle_warnings").remove();
                $("#status_text").css({'color':'green'});
                $("#status_text").text('ATIVO');
            }
            var any_private_profile=false;
            for(i=0;i<num_profiles;i++){
                if(icons_profiles[i]['status_profile']==='privated')
                    any_private_profile=true;
            }
            if(any_private_profile){
                if(!$("#middle_warnings")){
                    $("#list_warnings").prepend('<div class="alert alert-warning" role="alert"><ul id="middle_warnings"></ul></div>');
                }
                $("#middle_warnings").prepend('<li id="private_st"> Exitem perfis de referencia privados, considere trocar por outros;');                                
            }            
            for(i=0;i<num_profiles;i++){
                icons_profiles[i]['ptr_img_obj'].attr("src",icons_profiles[i]['img_profile']);
                icons_profiles[i]['ptr_p_obj'].text(icons_profiles[i]['login_profile']);
                if(icons_profiles[i]['status_profile']==='privated'||icons_profiles[i]['status_profile']==='deleted')
                    icons_profiles[i]['ptr_p_obj'].css({'color':'red'});
                icons_profiles[i]['ptr_panel_obj'].css({"visibility":"visible","display":"block"});
            }
        }
    }
    
    function init_icons_profiles(datas){
        response=jQuery.parseJSON(datas);
        prof=response['array_profiles'];
        status_messages=jQuery.parseJSON(status_messages);
        num_profiles=response['N'];
        for(i=0;i<num_profiles;i++){
            icons_profiles[i]['img_profile']=prof[i]['img_profile'];
            icons_profiles[i]['login_profile']=prof[i]['login_profile'];
            icons_profiles[i]['status_profile']=prof[i]['status_profile'];
        }
        display_reference_profiles();
    }
    
    function inser_icons_profiles(datas){
        icons_profiles[num_profiles]['img_profile']=datas['img_url'];
        icons_profiles[num_profiles]['login_profile']=datas['profile'];
        icons_profiles[num_profiles]['status_profile']=datas['status_profile'];
        num_profiles=num_profiles+1;
        display_reference_profiles();
    }
    
    function delete_icons_profiles(name_profile){
        var i,j;
        for(i=0;i<num_profiles;i++){
            if(icons_profiles[i]['login_profile']===name_profile)
                break;
        }
        for(j=i;j<num_profiles;j++){
            if(j+1<num_profiles){
                icons_profiles[j]['img_profile']=icons_profiles[j+1]['img_profile'];
                icons_profiles[j]['login_profile']=icons_profiles[j+1]['login_profile'];
                icons_profiles[j]['status_profile']=icons_profiles[j+1]['status_profile'];
            }
        }
        j=j-1;
        icons_profiles[j]['img_profile']='';
        icons_profiles[j]['login_profile']='';
        num_profiles=num_profiles-1; 
        display_reference_profiles();
        icons_profiles[j]['ptr_panel_obj'].css({"visibility":"hidden","display":"none"});
    }
    
    
    init_icons_profiles(profiles); 
 }); 