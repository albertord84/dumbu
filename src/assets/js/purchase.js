$(document).ready(function(){ 
    
    var icons_profiles={            
        0:{'ptr_img_obj':$('#img_ref_prof0'),'ptr_p_obj':$('#name_ref_prof0'),  'ptr_label_obj':$('#cnt_follows_prof0'),     'ptr_panel_obj':$('#reference_profile0'),'img_profile':'','login_profile':'','status_profile':'', 'follows_from_profile':'',  'ptr_lnk_ref_prof':$('#lnk_ref_prof0')},
        1:{'ptr_img_obj':$('#img_ref_prof1'),'ptr_p_obj':$('#name_ref_prof1'),  'ptr_label_obj':$('#cnt_follows_prof1'),     'ptr_panel_obj':$('#reference_profile1'),'img_profile':'','login_profile':'','status_profile':'', 'follows_from_profile':'',  'ptr_lnk_ref_prof':$('#lnk_ref_prof1')},
        2:{'ptr_img_obj':$('#img_ref_prof2'),'ptr_p_obj':$('#name_ref_prof2'),  'ptr_label_obj':$('#cnt_follows_prof2'),     'ptr_panel_obj':$('#reference_profile2'),'img_profile':'','login_profile':'','status_profile':'', 'follows_from_profile':'',  'ptr_lnk_ref_prof':$('#lnk_ref_prof2')}        
    };
        
    var num_profiles=0, MAX_NUM_PROFILES=3;    
    var verify=false, flag=false;
    
    $("#btn_add_new_profile").hover(
        function(){
            $('#btn_add_new_profile').css('cursor', 'pointer');
        },
        function(){
            $('#btn_add_new_profile').css('cursor', 'default');
        }
    );
    
    $('#btn_add_new_profile').mousedown(function(){
        $("#btn_add_new_profile").attr("src",base_url+"assets/images/+down.png");
    });
    $('#btn_add_new_profile').mouseup(function(){
        $("#btn_add_new_profile").attr("src",base_url+"assets/images/+.png");
    });
    $("#btn_add_new_profile").click(function(){
        if(num_profiles<MAX_NUM_PROFILES){
            
            $("#MyModal").modal('show').css(
                {
                    'margin-top': function () {
                        return -($(this).height() / 2);
                    },
                    'margin-left': function () {
                        return -($(this).width() / 2);
                    }
                })
        } else
            alert('Alcançou a quantidade maxima permitida');        
    });
    
    $("#btn_insert_profile").click(function(){
        if(validate_element('#login_profile','^[a-zA-Z0-9\._]{1,300}$')){                
            if(num_profiles<MAX_NUM_PROFILES){
                if($('#login_profile').val()!=''){
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
            $('#reference_profile_message').text('* O nome do perfil só pode conter letras, nÃºmeros, sublinhados e pontos.');
            $('#reference_profile_message').css('visibility','visible');
            $('#reference_profile_message').css('color','red');
            //alert('O nome de um perfil só pode conter combinações de letras, nÃºmeros, sublinhados e pontos.');
        }        
    });
    
    
    //modal_container_add_reference_rpofile
    
    
    
    
    
    
    
    
    
    {/*
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
           
    $(".image-reference-profile").hover(
        function(e){
            //alert($(e.target).attr('id'))
            $('.image-reference-profile').css('cursor', 'pointer');
        },
        function(){
            $('.image-reference-profile').css('cursor', 'default');
        }
    );
    
    $("#img_ref_prof0").click(function(){
        delete_profile_click(icons_profiles[0]['login_profile']);
    });
    $("#img_ref_prof1").click(function(){
        delete_profile_click(icons_profiles[1]['login_profile']);
    });
    $("#img_ref_prof2").click(function(){
        delete_profile_click(icons_profiles[2]['login_profile']);
    });
    
    function delete_profile_click(element){
       if(confirm('Deseja elimiar o perfil de referência '+element)){
            $.ajax({
                url : base_url+'index.php/welcome/client_desactive_profiles',
                data : {'profile':element},
                type : 'POST',
                dataType : 'json',
                success : function(response){
                    if(response['success']){
                        delete_icons_profiles(element);
                    } else
                        alert(response['message']);
                },
                error : function(xhr, status) {
                    alert('Não foi possível conectar com o Instagram');
                }
            });
        }
   }
   
    $('#insert_profile_form').keypress(function (e) {
        if (e.which == 13) {
            $("#btn_insert_profile").click();
            return false;
        }
    });
            
    
    
    $("#btn_RP_status").click(function(){
        $('#reference_profile_status_container').css({"visibility":"hidden","display":"none"})
    });
    */}
   
    function display_reference_profiles(){
        var reference_profiles_status=false;
        for(i=0;i<num_profiles;i++){
            icons_profiles[i]['ptr_img_obj'].attr("src",icons_profiles[i]['img_profile']);            
            icons_profiles[i]['ptr_img_obj'].prop('title', 'Click para eliminar '+icons_profiles[i]['login_profile']);
            icons_profiles[i]['ptr_p_obj'].prop('title', 'Ver '+icons_profiles[i]['login_profile']+' no Instagram');
            icons_profiles[i]['ptr_p_obj'].text((icons_profiles[i]['login_profile']).replace(/(^.{9}).*$/,'$1...'));            
            icons_profiles[i]['ptr_label_obj'].text(icons_profiles[i]['follows_from_profile']);
            icons_profiles[i]['ptr_lnk_ref_prof'].attr("href",'https://www.instagram.com/'+icons_profiles[i]['login_profile']+'/');                         
            if(icons_profiles[i]['status_profile']==='ended'){
                icons_profiles[i]['ptr_p_obj'].css({'color':'red'});
                $('#reference_profile_status_list').append('<li>O sistema já siguiu todos os seguidores do perfil de referência <b style="color:red">"'+icons_profiles[i]['login_profile']+'"</b></li>');
                reference_profiles_status=true;
            } else
            if(icons_profiles[i]['status_profile']==='privated'){
                icons_profiles[i]['ptr_p_obj'].css({'color':'red'});
                $('#reference_profile_status_list').append('<li>O perfil de referência <b style="color:red">"'+icons_profiles[i]['login_profile']+'"</b> passou a ser privado</li>');
                reference_profiles_status=true;
            } else
            if(icons_profiles[i]['status_profile']==='deleted'){
                icons_profiles[i]['ptr_p_obj'].css({'color':'red'});
                $('#reference_profile_status_list').append('<li>O perfil de referência <b style="color:red">"'+icons_profiles[i]['login_profile']+'"</b> nã existe mais no Instragram</li>');
                reference_profiles_status=true;
            }else
                icons_profiles[i]['ptr_p_obj'].css({'color':'black'});
            icons_profiles[i]['ptr_panel_obj'].css({"visibility":"visible","display":"block"});
        }
        if(reference_profiles_status){
            $('#reference_profile_status_container').css({"visibility":"visible","display":"block"})
        }
        if(num_profiles){
            $('#container_present_profiles').css({"visibility":"visible","display":"block"})
            $('#container_missing_profiles').css({"visibility":"hidden","display":"none"});
        } else{
            $('#container_missing_profiles').css({"visibility":"visible","display":"block"})
            $('#container_present_profiles').css({"visibility":"hidden","display":"none"});
        }
    }
        
    function inser_icons_profiles(datas){
        icons_profiles[num_profiles]['img_profile']=datas['img_url'];
        icons_profiles[num_profiles]['login_profile']=datas['profile'];
        icons_profiles[num_profiles]['follows_from_profile']=datas['follows_from_profile'];
        icons_profiles[num_profiles]['status_profile']=datas['status_profile'];
        icons_profiles[num_profiles]['ptr_lnk_ref_prof'].attr("href",'https://www.instagram.com/'+datas['profile']+'/');         
        num_profiles=num_profiles+1;
        display_reference_profiles();
        if(num_profiles){
            $('#container_present_profiles').css({"visibility":"visible","display":"block"})
            $('#container_missing_profiles').css({"visibility":"hidden","display":"none"});
        } else{
            $('#container_missing_profiles').css({"visibility":"visible","display":"block"})
            $('#container_present_profiles').css({"visibility":"hidden","display":"none"});
        }
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
                icons_profiles[j]['ptr_lnk_ref_prof'].attr("href",icons_profiles[j+1]['ptr_lnk_ref_prof'].attr("href"));             
            }
        }
        j=j-1;
        icons_profiles[j]['img_profile']='';
        icons_profiles[j]['login_profile']='';
        icons_profiles[j]['follows_from_profile']='';
        icons_profiles[j]['ptr_lnk_ref_prof'].attr("href","");
        num_profiles=num_profiles-1; 
        display_reference_profiles();
        icons_profiles[j]['ptr_panel_obj'].css({"visibility":"hidden","display":"none"});
        
        if(num_profiles){
            $('#container_present_profiles').css({"visibility":"visible","display":"block"})
            $('#container_missing_profiles').css({"visibility":"hidden","display":"none"});
        } else{
            $('#container_missing_profiles').css({"visibility":"visible","display":"block"})
            $('#container_present_profiles').css({"visibility":"hidden","display":"none"});
        }
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
 }); 
 
 
 