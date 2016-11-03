$(document).ready(function(){  
    
    var icons_profiles={            
        0:{'ptr_img_obj':$('#img_ref_prof0'),'ptr_p_obj':$('#name_ref_prof0'),'ptr_panel_obj':$('#reference_profile0'),'img_profile':'','login_profile':''},
        1:{'ptr_img_obj':$('#img_ref_prof1'),'ptr_p_obj':$('#name_ref_prof1'),'ptr_panel_obj':$('#reference_profile1'),'img_profile':'','login_profile':''},
        2:{'ptr_img_obj':$('#img_ref_prof2'),'ptr_p_obj':$('#name_ref_prof2'),'ptr_panel_obj':$('#reference_profile2'),'img_profile':'','login_profile':''},
        3:{'ptr_img_obj':$('#img_ref_prof3'),'ptr_p_obj':$('#name_ref_prof3'),'ptr_panel_obj':$('#reference_profile3'),'img_profile':'','login_profile':''},
        4:{'ptr_img_obj':$('#img_ref_prof4'),'ptr_p_obj':$('#name_ref_prof4'),'ptr_panel_obj':$('#reference_profile4'),'img_profile':'','login_profile':''}
        /*5:{'ptr_img_obj':$('#img_ref_prof5'),'ptr_p_obj':$('#name_ref_prof5'),'ptr_panel_obj':$('#reference_profile5'),'img_profile':'','login_profile':''},
        6:{'ptr_img_obj':$('#img_ref_prof6'),'ptr_p_obj':$('#name_ref_prof6'),'ptr_panel_obj':$('#reference_profile6'),'img_profile':'','login_profile':''},
        7:{'ptr_img_obj':$('#img_ref_prof7'),'ptr_p_obj':$('#name_ref_prof7'),'ptr_panel_obj':$('#reference_profile7'),'img_profile':'','login_profile':''},
        8:{'ptr_img_obj':$('#img_ref_prof8'),'ptr_p_obj':$('#name_ref_prof8'),'ptr_panel_obj':$('#reference_profile8'),'img_profile':'','login_profile':''},
        9:{'ptr_img_obj':$('#img_ref_prof9'),'ptr_p_obj':$('#name_ref_prof9'),'ptr_panel_obj':$('#reference_profile9'),'img_profile':'','login_profile':''}*/
    };    
    
    var num_profiles;
    
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
                            $("#waiting_inser_profile").css({"visibility":"hidden","display":"none"});
                            $('#login_profile').val('');
                            $("#insert_profile_form").fadeOut();
                            $("#insert_profile_form").css({"visibility":"hidden","display":"none"});                            
                        } else
                            alert(response['message']);                        
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
    
    $("#close_palnel_insert_profile").click(function(){
        $("#insert_profile_form").fadeOut();
        $("#insert_profile_form").css({"visibility":"hidden","display":"none"});
    });          
    
    $("#switch_status").change(function() {
        //alert(this.checked);
    });   
   
    function display_reference_profiles(){        
        for(i=0;i<num_profiles;i++){
            icons_profiles[i]['ptr_img_obj'].attr("src",icons_profiles[i]['img_profile']);
            icons_profiles[i]['ptr_p_obj'].text(icons_profiles[i]['login_profile']);
            icons_profiles[i]['ptr_panel_obj'].css({"visibility":"visible","display":"block"});
        }
    }
    
    function init_icons_profiles(datas){
        response=jQuery.parseJSON(datas);
        prof=response['array_profiles'];
        num_profiles=response['N'];
        for(i=0;i<num_profiles;i++){
            icons_profiles[i]['img_profile']=prof[i]['img_profile'];
            icons_profiles[i]['login_profile']=prof[i]['login_profile'];
        }
        display_reference_profiles();
    }
    
    function inser_icons_profiles(datas){
        icons_profiles[num_profiles]['img_profile']=datas['img_url'];
        icons_profiles[num_profiles]['login_profile']=datas['profile'];
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