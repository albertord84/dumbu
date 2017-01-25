$(document).ready(function(){ 
    var icons_profiles={            
        0:{'ptr_img_obj':$('#img_ref_prof0'),'ptr_p_obj':$('#name_ref_prof0'),  'ptr_label_obj':$('#cnt_follows_prof0'),     'ptr_panel_obj':$('#reference_profile0'),'img_profile':'','login_profile':'','status_profile':'', 'follows_from_profile':'',  'ptr_lnk_ref_prof':$('#lnk_ref_prof0')},
        1:{'ptr_img_obj':$('#img_ref_prof1'),'ptr_p_obj':$('#name_ref_prof1'),  'ptr_label_obj':$('#cnt_follows_prof1'),     'ptr_panel_obj':$('#reference_profile1'),'img_profile':'','login_profile':'','status_profile':'', 'follows_from_profile':'',  'ptr_lnk_ref_prof':$('#lnk_ref_prof1')},
        2:{'ptr_img_obj':$('#img_ref_prof2'),'ptr_p_obj':$('#name_ref_prof2'),  'ptr_label_obj':$('#cnt_follows_prof2'),     'ptr_panel_obj':$('#reference_profile2'),'img_profile':'','login_profile':'','status_profile':'', 'follows_from_profile':'',  'ptr_lnk_ref_prof':$('#lnk_ref_prof2')},
        3:{'ptr_img_obj':$('#img_ref_prof3'),'ptr_p_obj':$('#name_ref_prof3'),  'ptr_label_obj':$('#cnt_follows_prof3'),     'ptr_panel_obj':$('#reference_profile3'),'img_profile':'','login_profile':'','status_profile':'', 'follows_from_profile':'',  'ptr_lnk_ref_prof':$('#lnk_ref_prof3')},
        4:{'ptr_img_obj':$('#img_ref_prof4'),'ptr_p_obj':$('#name_ref_prof4'),  'ptr_label_obj':$('#cnt_follows_prof4'),     'ptr_panel_obj':$('#reference_profile4'),'img_profile':'','login_profile':'','status_profile':'', 'follows_from_profile':'',  'ptr_lnk_ref_prof':$('#lnk_ref_prof4')},
        5:{'ptr_img_obj':$('#img_ref_prof5'),'ptr_p_obj':$('#name_ref_prof5'),  'ptr_label_obj':$('#cnt_follows_prof5'),     'ptr_panel_obj':$('#reference_profile5'),'img_profile':'','login_profile':'','status_profile':'', 'follows_from_profile':'',  'ptr_lnk_ref_prof':$('#lnk_ref_prof5')},
        /*6:{'ptr_img_obj':$('#img_ref_prof6'),'ptr_p_obj':$('#name_ref_prof6'),  'ptr_label_obj':$('#cnt_follows_prof6'),     'ptr_panel_obj':$('#reference_profile6'),'img_profile':'','login_profile':'','status_profile':'', 'follows_from_profile':'',  'ptr_lnk_ref_prof':$('#img_ref_prof6')},
        7:{'ptr_img_obj':$('#img_ref_prof7'),'ptr_p_obj':$('#name_ref_prof7'),  'ptr_label_obj':$('#cnt_follows_prof7'),     'ptr_panel_obj':$('#reference_profile7'),'img_profile':'','login_profile':'','status_profile':'', 'follows_from_profile':'',  'ptr_lnk_ref_prof':$('#img_ref_prof7')},
        8:{'ptr_img_obj':$('#img_ref_prof8'),'ptr_p_obj':$('#name_ref_prof8'),  'ptr_label_obj':$('#cnt_follows_prof8'),     'ptr_panel_obj':$('#reference_profile8'),'img_profile':'','login_profile':'','status_profile':'', 'follows_from_profile':'',  'ptr_lnk_ref_prof':$('#img_ref_prof8')},
        9:{'ptr_img_obj':$('#img_ref_prof9'),'ptr_p_obj':$('#name_ref_prof9'),  'ptr_label_obj':$('#cnt_follows_prof9'),     'ptr_panel_obj':$('#reference_profile9'),'img_profile':'','login_profile':'','status_profile':'', 'follows_from_profile':'',  'ptr_lnk_ref_prof':$('#img_ref_prof9')}*/
    };    
    
    var num_profiles,flag=false;
    var verify=false;
    
    $("#btn_verify_account").click(function(){
        if(!verify){
            $("#btn_verify_account").text('CONFIRMO ATIVAÇÃO');    
            verify=true;
        } else{
            $("#lnk_verify_account").attr('target', '_self');
            $("#lnk_verify_account").attr("href", base_url+'index.php/welcome/client');
            //$(location).attr('href',base_url+'index.php/welcome/client');
            verify=false;
        }        
    });
    
           
    $(".image-reference-profile").hover(
        function(e){
            //alert($(e.target).attr('id'))
            $('.image-reference-profile').css('cursor', 'pointer');
        },
        function(){
            $('.image-reference-profile').css('cursor', 'default');
        }
    );
    $("#my_img").hover(
        function(){
            $('#my_img').css('cursor', 'pointer');
        },
        function(){
            $('#my_img').css('cursor', 'default');
        }
    );
    
    $(".red_number").hover(
        function(){
            $('.red_number').css('cursor', 'pointer');
        },
        function(){
            $('.red_number').css('cursor', 'default');
        }
    );
    
    $("#btn_unfollow_permition").click(function(){        
        $("#message_status1").remove();
        $("#btn_unfollow_permition").remove();
        $("#message_status2").text('A SOLICITACÃO ESTA SENDO PROCESSADA');
        $("#message_status3").text('INMEDIATEMENTE DE TERMINAR COMEÇARÁ A RECEBER O SERVIÇO');            
    });
    
    $("#img_ref_prof0").click(function(){  
        //delete_profile_click($("#name_ref_prof0"));        
        delete_profile_click(icons_profiles[0]['login_profile']);        
    });
    $("#img_ref_prof1").click(function(){
        delete_profile_click(icons_profiles[1]['login_profile']);
    });
    $("#img_ref_prof2").click(function(){
        delete_profile_click(icons_profiles[2]['login_profile']);
    });
    $("#img_ref_prof3").click(function(){
        delete_profile_click(icons_profiles[3]['login_profile']);
    });
    $("#img_ref_prof4").click(function(){
        delete_profile_click(icons_profiles[4]['login_profile']);
    });
    $("#img_ref_prof5").click(function(){
        delete_profile_click(icons_profiles[5]['login_profile']);
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
            $('#reference_profile_message').text('* O nome do perfil só pode conter letras, nÃºmeros, sublinhados e pontos.');
            $('#reference_profile_message').css('visibility','visible');
            $('#reference_profile_message').css('color','red');
            //alert('O nome de um perfil só pode conter combinações de letras, nÃºmeros, sublinhados e pontos.');
        }        
    });
    
    $("#activate_account_by_status_3").click(function(){
        if($('#userLogin').val()!='' && $('#userPassword').val()!==''){            
            if(validate_element('#userLogin','^[a-zA-Z0-9\._]{1,300}$')){
                var l = Ladda.create(this);  l.start(); l.start();
                $.ajax({
                    url : base_url+'index.php/welcome/user_do_login',
                    //url : base_url+'index.php/welcome/client',
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
                                alert('Senha incorreta');           
                            }
                        l.stop();
                    },                
                    error : function(xhr, status) {
                        alert('Erro encontrado. Informe para o atendimento seu caso. ');    
                        l.stop();
                    }
                });   
            } else{
                $('#container_login_message').text('O nome de um perfil só pode conter combinações de letras, nÃºmeros, sublinhados e pontos.');
                $('#container_login_message').css('visibility','visible');
                $('#container_login_message').css('color','red');
            }       
        } else{
            $('#container_login_message').text('Deve preencher todos os dados corretamente.');
            $('#container_login_message').css('visibility','visible');
            $('#container_login_message').css('color','red');
        }
    });
    
    $("#cancel_usser_account").click(function(){
        //var l = Ladda.create(this);  l.start();
        if(confirm('Sugerimos entrar em contato com nosso Atendimento antes de cancelar sua assinatura. Deseja realmente iniciar o processo de cancelamento?'))            
            window.open('https://docs.google.com/a/dumbu.pro/forms/d/e/1FAIpQLSejGY19wxZXEmMy_E9zcD-vODoimwpFAt4qQ-lN7TGYjbxYjw/viewform?c=0&w=1','_blank');
    });
    
    
    $("#adding_profile").click(function(){
        if(num_profiles<MAX_NUM_PROFILES){
            $("#insert_profile_form").fadeIn();
            $("#insert_profile_form").css({"visibility":"visible","display":"block"});
        } else
            alert('Alcançou a quantidade maxima permitida');        
    });
    
    $("#btn_RP_status").click(function(){
        $('#reference_profile_status_container').css({"visibility":"hidden","display":"none"})
    });
    
   
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
    
    function init_icons_profiles(datas){
        response=jQuery.parseJSON(datas);
        prof=response['array_profiles'];
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
    
    function change_plane(new_plane_id){        
        if(new_plane_id > plane_id)
            confirm_msg = 'Ao mudar para um plano maior, vc deve pagar a diferença. Confirma mudar de plano?';
        else
            confirm_msg = 'Confirma mudar de plano?';        
        if(confirm(confirm_msg)){
            $.ajax({
                url : base_url+'index.php/welcome/change_plane',
                data : {
                    'plane_id':plane_id,
                    'new_plane_id':new_plane_id
                },
                type : 'POST',
                dataType : 'json',
                async: false,
                success : function(response) {
                    if(response['success']==true){
                        alert(response['success']);
                        $(location).attr('href',base_url+'index.php/welcome/client');
                    } else{
                        alert('Não foi possível trocar de plano, Entre en contaco com o Atendimento');
                    }
                    l.stop();
                },
                error : function(xhr, status) {
                    alert('Erro enviando sua solicitação. Reporte o caso para nosso Atendimento');
                    l.stop();
                }
            });
        }
    }
    
    function actual_plane(){
        switch(plane_id){
            case 1:
                $('#radio_plane_7_9990').attr('checked', true);
                $('#container_plane_7_9990').css({'border':'1px solid silver', 'box-shadow': '10px 10px 5px #ACC2BC'});
                $('#container_plane_4_90').css({'border':'1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
                $('#container_plane_9_90').css({'border':'1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
                $('#container_plane_29_90').css({'border':'1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
                $('#container_plane_99_90').css({'border':'1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
                break;
            case 2:
                $('#radio_plane_4_90').attr('checked', true);
                $('#container_plane_7_9990').css({'border':'1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
                $('#container_plane_4_90').css({'border':'1px solid silver', 'box-shadow': '10px 10px 5px #ACC2BC'});
                $('#container_plane_9_90').css({'border':'1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
                $('#container_plane_29_90').css({'border':'1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
                $('#container_plane_99_90').css({'border':'1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
                break;
            case 3:
                $('#radio_plane_9_90').attr('checked', true);
                $('#container_plane_7_9990').css({'border':'1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
                $('#container_plane_4_90').css({'border':'1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
                $('#container_plane_9_90').css({'border':'1px solid silver', 'box-shadow': '10px 10px 5px #ACC2BC'});
                $('#container_plane_29_90').css({'border':'1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
                $('#container_plane_99_90').css({'border':'1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
                break;
            case 4:
                $('#radio_plane_29_90').attr('checked', true);
                $('#container_plane_7_9990').css({'border':'1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
                $('#container_plane_4_90').css({'border':'1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
                $('#container_plane_9_90').css({'border':'1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
                $('#container_plane_29_90').css({'border':'1px solid silver', 'box-shadow': '10px 10px 5px #ACC2BC'});
                $('#container_plane_99_90').css({'border':'1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
                break;
            case 5:
                $('#radio_plane_99_90').attr('checked', true);
                $('#container_plane_7_9990').css({'border':'1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
                $('#container_plane_4_90').css({'border':'1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
                $('#container_plane_9_90').css({'border':'1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
                $('#container_plane_29_90').css({'border':'1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
                $('#container_plane_99_90').css({'border':'1px solid silver', 'box-shadow': '10px 10px 5px #ACC2BC'});
                break;
        }
    } actual_plane();
    
    $('#radio_plane_4_90').click(function(){
        $('#container_plane_4_90').css({'border':'1px solid silver', 'box-shadow': '10px 10px 5px #ACC2BC'});
        $('#container_plane_9_90').css({'border':'1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
        $('#container_plane_29_90').css({'border':'1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
        $('#container_plane_99_90').css({'border':'1px solid silver', 'box-shadow': '5px 5px 2px #888888'});        
        new_plane_id='2';
        change_plane(new_plane_id);
    });  
    
    $('#radio_plane_9_90').click(function(){
        $('#container_plane_4_90').css({'border':'1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
        $('#container_plane_9_90').css({'border':'1px solid silver', 'box-shadow': '10px 10px 5px #ACC2BC'});
        $('#container_plane_29_90').css({'border':'1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
        $('#container_plane_99_90').css({'border':'1px solid silver', 'box-shadow': '5px 5px 2px #888888'});        
        new_plane_id='3';
        change_plane(new_plane_id);
    });    
    $('#radio_plane_29_90').click(function(){
        $('#container_plane_4_90').css({'border':'1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
        $('#container_plane_9_90').css({'border':'1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
        $('#container_plane_29_90').css({'border':'1px solid silver', 'box-shadow': '10px 10px 5px #ACC2BC'});
        $('#container_plane_99_90').css({'border':'1px solid silver', 'box-shadow': '5px 5px 2px #888888'});        
        new_plane_id='4';
        change_plane(new_plane_id);
    });
    $('#radio_plane_99_90').click(function(){
        $('#container_plane_4_90').css({'border':'1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
        $('#container_plane_9_90').css({'border':'1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
        $('#container_plane_29_90').css({'border':'1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
        $('#container_plane_99_90').css({'border':'1px solid silver', 'box-shadow': '10px 10px 5px #ACC2BC'});        
        new_plane_id='5';
        change_plane(new_plane_id);
    });
    
    $('#container_plane_4_90').hover(
        function () { 
                $("#container_plane_4_90").css('cursor', 'pointer');
            }, 
        function () { 
                $("#container_plane_4_90").css('cursor', 'auto');
            }
     ); 
    $('#container_plane_9_90').hover(
        function () { 
                $("#container_plane_9_90").css('cursor', 'pointer');
            }, 
        function () { 
                $("#container_plane_9_90").css('cursor', 'auto');
            }
     ); 
    $('#container_plane_29_90').hover(
        function () { 
                $("#container_plane_29_90").css('cursor', 'pointer');
            }, 
        function () { 
                $("#container_plane_29_90").css('cursor', 'auto');
            }
     ); 
    $('#container_plane_99_90').hover(
        function () { 
                $("#container_plane_99_90").css('cursor', 'pointer');
            }, 
        function () { 
                $("#container_plane_99_90").css('cursor', 'auto');
            }
     ); 
    
    
    init_icons_profiles(profiles); 
 }); 