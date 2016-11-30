$(document).ready(function(){ 
    var icons_profiles={            
        0:{'ptr_img_obj':$('#img_ref_prof0'),'ptr_p_obj':$('#name_ref_prof0'),  'ptr_label_obj':$('#cnt_follows_prof0'),     'ptr_panel_obj':$('#reference_profile0'),'img_profile':'','login_profile':'','status_profile':'', 'follows_from_profile':''},
        1:{'ptr_img_obj':$('#img_ref_prof1'),'ptr_p_obj':$('#name_ref_prof1'),  'ptr_label_obj':$('#cnt_follows_prof1'),     'ptr_panel_obj':$('#reference_profile1'),'img_profile':'','login_profile':'','status_profile':'', 'follows_from_profile':''},
        2:{'ptr_img_obj':$('#img_ref_prof2'),'ptr_p_obj':$('#name_ref_prof2'),  'ptr_label_obj':$('#cnt_follows_prof2'),     'ptr_panel_obj':$('#reference_profile2'),'img_profile':'','login_profile':'','status_profile':'', 'follows_from_profile':''},
        3:{'ptr_img_obj':$('#img_ref_prof3'),'ptr_p_obj':$('#name_ref_prof3'),  'ptr_label_obj':$('#cnt_follows_prof3'),     'ptr_panel_obj':$('#reference_profile3'),'img_profile':'','login_profile':'','status_profile':'', 'follows_from_profile':''},
        4:{'ptr_img_obj':$('#img_ref_prof4'),'ptr_p_obj':$('#name_ref_prof4'),  'ptr_label_obj':$('#cnt_follows_prof4'),     'ptr_panel_obj':$('#reference_profile4'),'img_profile':'','login_profile':'','status_profile':'', 'follows_from_profile':''}
      /*5:{'ptr_img_obj':$('#img_ref_prof5'),'ptr_p_obj':$('#name_ref_prof5'),  'ptr_label_obj':$('#cnt_follows_prof5'),     'ptr_panel_obj':$('#reference_profile5'),'img_profile':'','login_profile':'','status_profile':'', 'follows_from_profile':''},
        6:{'ptr_img_obj':$('#img_ref_prof6'),'ptr_p_obj':$('#name_ref_prof6'),  'ptr_label_obj':$('#cnt_follows_prof6'),     'ptr_panel_obj':$('#reference_profile6'),'img_profile':'','login_profile':'','status_profile':'', 'follows_from_profile':''},
        7:{'ptr_img_obj':$('#img_ref_prof7'),'ptr_p_obj':$('#name_ref_prof7'),  'ptr_label_obj':$('#cnt_follows_prof7'),     'ptr_panel_obj':$('#reference_profile7'),'img_profile':'','login_profile':'','status_profile':'', 'follows_from_profile':''},
        8:{'ptr_img_obj':$('#img_ref_prof8'),'ptr_p_obj':$('#name_ref_prof8'),  'ptr_label_obj':$('#cnt_follows_prof8'),     'ptr_panel_obj':$('#reference_profile8'),'img_profile':'','login_profile':'','status_profile':'', 'follows_from_profile':''},
        9:{'ptr_img_obj':$('#img_ref_prof9'),'ptr_p_obj':$('#name_ref_prof9'),  'ptr_label_obj':$('#cnt_follows_prof9'),     'ptr_panel_obj':$('#reference_profile9'),'img_profile':'','login_profile':'','status_profile':'', 'follows_from_profile':''}*/
    };    
    
    var num_profiles,flag=false;
    var verify=false;
    
    $("#btn_verify_account").click(function(){
        if(!verify){
            $("#btn_verify_account").text('CONFIRMO ATIVAÇÂO');    
            verify=true;
        } else{
            $("#lnk_verify_account").attr('target', '_self');
            $("#lnk_verify_account").attr("href", base_url+'index.php/welcome/client');
            //$(location).attr('href',base_url+'index.php/welcome/client');
            verify=false;
        }        
    });
        
    $("#btn_unfollow_permition").click(function(){        
        $("#message_status1").remove();
        $("#btn_unfollow_permition").remove();
        $("#message_status2").text('A SOLICITACÃO ESTA SENDO PROCESSADA');
        $("#message_status3").text('INMEDIATEMENTE DE TERMINAR COMEÇARÁ A RECEBER O SERVIÇO');            
    });
    
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
        if(validate_element('#login_profile','^[a-zA-Z0-9\._]{1,300}$')){                
            if(num_profiles<MAX_NUM_PROFILES){
                if($('#login_profile').val()!=''){
                    //$("#waiting_inser_profile").css({"visibility":"visible","display":"block"});
                    var l = Ladda.create(this);  l.start();
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
                                $('#reference_profile_message').text('');
                                $('#reference_profile_message').css('visibility','hidden');
                            } else{
                                $('#reference_profile_message').text(response['message']);
                                $('#reference_profile_message').css('visibility','visible');
                                $('#reference_profile_message').css('color','red')
                                //alert(response['message']);                        
                            }
                            $("#waiting_inser_profile").css({"visibility":"hidden","display":"none"});
                            l.stop();
                        },
                        error : function(xhr, status) {
                            $('#reference_profile_message').text('Não foi possível conectar com o Instagram');
                            $('#reference_profile_message').css('visibility','visible');
                            $('#reference_profile_message').css('color','red');
                            //alert('Não foi possível conectar com o Instagram');
                            l.stop();
                        }
                    });               
                }
            } else{
                $('#reference_profile_message').text('Alcançou a quantidade maxima.');
                $('#reference_profile_message').css('visibility','visible');
                $('#reference_profile_message').css('color','red');
                //alert('Alcançou a quantidade maxima permitida');
            }
        } else{
            $('#reference_profile_message').text('* O nome do perfil só pode conter letras, números, sublinhados e pontos.');
            $('#reference_profile_message').css('visibility','visible');
            $('#reference_profile_message').css('color','red');
            //alert('O nome de um perfil só pode conter combinações de letras, números, sublinhados e pontos.');
        }        
    });
    
    $("#activate_account_by_status_3").click(function(){
        if($('#userLogin').val()!='' && $('#userPassword').val()!==''){            
            if(validate_element('#userLogin','^[a-zA-Z0-9\._]{1,300}$')){
                var l = Ladda.create(this);  l.start(); l.start();
                $.ajax({
                    url : base_url+'index.php/welcome/user_do_login',      
                    data : {
                        'user_login':$('#userLogin').val(),
                        'user_pass': $('#userPassword').val()
                    },
                    type : 'POST',
                    dataType : 'json',
                    async: false,
                    success : function(response) {
                        if(response['authenticated']){                            
                            if(response['role']=='CLIENT'){
                                $(location).attr('href',base_url+'index.php/welcome/'+response['resource']+'');
                            } 
                        } else{
                                $('#container_login_message').text(response['message']);
                                $('#container_login_message').css('visibility','visible');
                                $('#container_login_message').css('color','red');                              
                            }
                        l.stop();
                    },                
                    error : function(xhr, status) {
                        alert('internal error');    
                        l.stop();
                    }
                });   
            } else{
                $('#container_login_message').text('O nome de um perfil só pode conter combinações de letras, números, sublinhados e pontos.');
                $('#container_login_message').css('visibility','visible');
                $('#container_login_message').css('color','red');
            }       
        } else{
            $('#container_login_message').text('Deve preencher todos os dados corretamente.');
            $('#container_login_message').css('visibility','visible');
            $('#container_login_message').css('color','red');
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
   
    function display_reference_profiles(){ 
        status_messages['warning'][2]='';
        status_messages['warning'][3]='';
        
        for(i=0;i<num_profiles;i++){
            icons_profiles[i]['ptr_img_obj'].attr("src",icons_profiles[i]['img_profile']);
            icons_profiles[i]['ptr_p_obj'].text(icons_profiles[i]['login_profile']);
            icons_profiles[i]['ptr_label_obj'].text(icons_profiles[i]['follows_from_profile']);
            if(icons_profiles[i]['status_profile']==='privated'||icons_profiles[i]['status_profile']==='deleted')
                icons_profiles[i]['ptr_p_obj'].css({'color':'red'});
            else
                icons_profiles[i]['ptr_p_obj'].css({'color':'black'});
            icons_profiles[i]['ptr_panel_obj'].css({"visibility":"visible","display":"block"});
        }
    }
    
    function init_icons_profiles(datas){
        response=jQuery.parseJSON(datas);
        prof=response['array_profiles'];
        status_messages=jQuery.parseJSON(status_messages);
        num_profiles=response['N'];
        for(i=0;i<num_profiles;i++){
            icons_profiles[i]['img_profile']=prof[i]['img_profile'];
            icons_profiles[i]['follows_from_profile']=prof[i]['follows_from_profile'];
            icons_profiles[i]['login_profile']=prof[i]['login_profile'];
            icons_profiles[i]['status_profile']=prof[i]['status_profile'];
        }
        display_reference_profiles();
    }
    
    function inser_icons_profiles(datas){
        icons_profiles[num_profiles]['img_profile']=datas['img_url'];
        icons_profiles[num_profiles]['login_profile']=datas['profile'];
        icons_profiles[num_profiles]['follows_from_profile']=datas['follows_from_profile'];
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
                icons_profiles[j]['follows_from_profile']=icons_profiles[j+1]['follows_from_profile'];
                icons_profiles[j]['status_profile']=icons_profiles[j+1]['status_profile'];
            }
        }
        j=j-1;
        icons_profiles[j]['img_profile']='';
        icons_profiles[j]['login_profile']='';
        icons_profiles[j]['follows_from_profile']='';
        num_profiles=num_profiles-1; 
        display_reference_profiles();
        icons_profiles[j]['ptr_panel_obj'].css({"visibility":"hidden","display":"none"});
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
    
    
    init_icons_profiles(profiles); 
 }); 