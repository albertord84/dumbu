$(document).ready(function () {   
    
    //----------------------------------------------------------------------------------------------------------
    //HASHTAG
    
    var icons_hashtag = {
        0: {'ptr_img_obj':$('#img_hashtag0'), 'ptr_p_obj':$('#name_hashtag0'), 'ptr_label_obj':$('#cnt_follows_hashtag0'), 'ptr_panel_obj':$('#hashtag0'), 'img_hashtag':'', 'login_hashtag':'', 'status_hashtag':'', 'follows_from_hashtag':'', 'ptr_lnk_hashtag':$('#lnk_hashtag0'), 'hashtag_pk':''},
        1: {'ptr_img_obj':$('#img_hashtag1'), 'ptr_p_obj':$('#name_hashtag1'), 'ptr_label_obj':$('#cnt_follows_hashtag1'), 'ptr_panel_obj':$('#hashtag1'), 'img_hashtag':'', 'login_hashtag':'', 'status_hashtag':'', 'follows_from_hashtag':'', 'ptr_lnk_hashtag':$('#lnk_hashtag1'), 'hashtag_pk':''},
        2: {'ptr_img_obj':$('#img_hashtag2'), 'ptr_p_obj':$('#name_hashtag2'), 'ptr_label_obj':$('#cnt_follows_hashtag2'), 'ptr_panel_obj':$('#hashtag2'), 'img_hashtag':'', 'login_hashtag':'', 'status_hashtag':'', 'follows_from_hashtag':'', 'ptr_lnk_hashtag':$('#lnk_hashtag2'), 'hashtag_pk':''},
        3: {'ptr_img_obj':$('#img_hashtag3'), 'ptr_p_obj':$('#name_hashtag3'), 'ptr_label_obj':$('#cnt_follows_hashtag3'), 'ptr_panel_obj':$('#hashtag3'), 'img_hashtag':'', 'login_hashtag':'', 'status_hashtag':'', 'follows_from_hashtag':'', 'ptr_lnk_hashtag':$('#lnk_hashtag3'), 'hashtag_pk':''},
        4: {'ptr_img_obj':$('#img_hashtag4'), 'ptr_p_obj':$('#name_hashtag4'), 'ptr_label_obj':$('#cnt_follows_hashtag4'), 'ptr_panel_obj':$('#hashtag4'), 'img_hashtag':'', 'login_hashtag':'', 'status_hashtag':'', 'follows_from_hashtag':'', 'ptr_lnk_hashtag':$('#lnk_hashtag4'), 'hashtag_pk':''},
        5: {'ptr_img_obj':$('#img_hashtag5'), 'ptr_p_obj':$('#name_hashtag5'), 'ptr_label_obj':$('#cnt_follows_hashtag5'), 'ptr_panel_obj':$('#hashtag5'), 'img_hashtag':'', 'login_hashtag':'', 'status_hashtag':'', 'follows_from_hashtag':'', 'ptr_lnk_hashtag':$('#lnk_hashtag5'), 'hashtag_pk':''}        
    };
    
    $("#upgrade_plane").click(function () {
        $("#myModal_hashtag").modal('hide');    
    });    

    var num_hashtag;
    
    $(".img_hashtag").hover(
            function (e) {
                //modal_alert_message($(e.target).attr('id'))
                $('.img_hashtag').css('cursor', 'pointer');
            },
            function () {
                $('.img_hashtag').css('cursor', 'default');
            }
    );
    
    $("#btn_add_new_hashtag").hover(
        function () {
            $('#btn_add_new_hashtag').css('cursor', 'pointer');
        },
        function () {
            $('#btn_add_new_hashtag').css('cursor', 'default');
    });
   
    $("#img_hashtag0").click(function () {
        if (!(icons_hashtag[0]['login_hashtag']).match("hashtag"))
            delete_hashtag_click(icons_hashtag[0]['login_hashtag']);
    });

    $("#img_hashtag1").click(function () {
        if (!(icons_hashtag[1]['login_hashtag']).match("hashtag"))
            delete_hashtag_click(icons_hashtag[1]['login_hashtag']);
    });

    $("#img_hashtag2").click(function () {
        if (!(icons_hashtag[2]['login_hashtag']).match("hashtag"))
            delete_hashtag_click(icons_hashtag[2]['login_hashtag']);
    });

    $("#img_hashtag3").click(function () {
        if (!(icons_hashtag[3]['login_hashtag']).match("hashtag"))
            delete_hashtag_click(icons_hashtag[3]['login_hashtag']);
    });

    $("#img_hashtag4").click(function () {
        if (!(icons_hashtag[4]['login_hashtag']).match("hashtag"))
            delete_hashtag_click(icons_hashtag[4]['login_hashtag']);
    });

    $("#img_hashtag5").click(function () {
        if (!(icons_hashtag[5]['login_hashtag']).match("hashtag"))
            delete_hashtag_click(icons_hashtag[5]['login_hashtag']);
    });
        
    $("#btn_insert_hashtag").click(function () {        
        if (validate_element('#login_hashtag', '^[a-zA-Z0-9\.-]{1,300}$')) {
            if(num_hashtag < MAX_NUM_GEOLOCALIZATION) {
                if($('#login_hashtag').val() != '') {                    
                    var l = Ladda.create(this);
                    l.start();
                    $.ajax({
                        url: base_url + 'index.php/welcome/client_insert_hashtag',
                        data: {'hashtag': $('#login_hashtag').val()},
                        type: 'POST',
                        dataType: 'json',
                        success: function (response) {
                            if (response['success']) {
                                inser_icons_hashtag(response);
                                $('#login_hashtag').val('');
                                $("#insert_hashtag_form").fadeOut();
                                $("#insert_hashtag_form").css({"visibility": "hidden", "display": "none"});
                                $('#hashtag_message').text('');
                                $('#hashtag_message').css('visibility', 'hidden');
                                if (num_hashtag === MAX_NUM_GEOLOCALIZATION) {
                                    $('#btn_modal_close').click();
                                }
                            } else {
                                $('#hashtag_message').text(response['message']);
                                $('#hashtag_message').css('visibility', 'visible');
                                $('#hashtag_message').css('color', 'red');   
                            }                            
                            l.stop();
                        },
                        error: function (xhr, status) {
                            $('#hashtag_message').text(T('Não foi possível conectar com o Instagram'));
                            $('#hashtag_message').css('visibility', 'visible');
                            $('#hashtag_message').css('color', 'red');
                            l.stop();
                        }
                    });
                }
            } else {
                $('#hashtag_message').text(T('Alcançou a quantidade máxima.'));
                $('#hashtag_message').css('visibility', 'visible');
                $('#hashtag_message').css('color', 'red');            
            }
        } else {
            $('#hashtag_message').text(T('* O nome do hashtag só pode conter letras, números, sublinhados e pontos.'));
            $('#hashtag_message').css('visibility', 'visible');
            $('#hashtag_message').css('color', 'red');
        }
    });
      
    function delete_hashtag_click(element) {
        if (confirm(T('Deseja eliminar o hashtag ') + element)) {
            $.ajax({
                url: base_url + 'index.php/welcome/client_desactive_hashtag',
                data: {'hashtag': element},
                type: 'POST',
                dataType: 'json',
                success: function (response) {
                    if (response['success']) {
                        delete_icons_hashtag(element);
                    } else
                        modal_alert_message(response['message']);
                },
                error: function (xhr, status) {
                    modal_alert_message(T('Não foi possível conectar com o Instagram'));
                }
            });
        }
    }

    function init_icons_hashtag(datas) {
        response = jQuery.parseJSON(datas);
        prof = response['array_hashtag'];
        //prof = response['array_profiles'];
        if (response['message'] !== 'Hashtags unloaded by Instagram failed connection') {
            num_hashtag = response['N_hashtag'];
            for (i = 0; i < num_hashtag; i++) {
                //if (!(typeof prof[i]==='undefined')) {
                    icons_hashtag[i]['img_hashtag'] = prof[i]['img_hashtag'];
                    icons_hashtag[i]['follows_from_hashtag'] = prof[i]['follows_from_hashtag'];
                    icons_hashtag[i]['login_hashtag'] = prof[i]['login_hashtag'];
                    xxx=prof[i]['hashtag_pk'];
                    icons_hashtag[i]['hashtag_pk'] = prof[i]['hashtag_pk'];
                    icons_hashtag[i]['status_hashtag'] = prof[i]['status_hashtag'];
                    
                    /*icons_hashtag[i]['img_hashtag'] = prof[i]['img_profile'];
                    icons_hashtag[i]['follows_from_hashtag'] = prof[i]['follows_from_profile'];
                    icons_hashtag[i]['login_hashtag'] = prof[i]['login_profile'];
                    icons_hashtag[i]['status_hashtag'] = prof[i]['status_profile'];*/
                //}
            }
            for (j = i; j < MAX_NUM_GEOLOCALIZATION; j++) {
                icons_hashtag[j]['img_hashtag'] = base_url + 'assets/images/avatar_hashtag.jpg';
                icons_hashtag[j]['follows_from_hashtag'] = '0';
                icons_hashtag[j]['login_hashtag'] = 'hashtag' + (j + 1);
                icons_hashtag[j]['status_hashtag'] = '0';
            }
            display_hashtag();
        } else {
            modal_alert_message('Não foi possível comunicar com o Instagram pra verificar seus perfis de referência. Tente depois');
        }
    }

    function display_hashtag() {
        var hashtag_status = false;
        for (i = 0; i < MAX_NUM_GEOLOCALIZATION; i++) {
            icons_hashtag[i]['ptr_img_obj'].attr("src", icons_hashtag[i]['img_hashtag']);
            icons_hashtag[i]['ptr_img_obj'].prop('title', T('Click para eliminar ') + icons_hashtag[i]['login_hashtag']);
            icons_hashtag[i]['ptr_p_obj'].prop('title', T('Ver ') + icons_hashtag[i]['login_hashtag'] + T(' no Instagram'));
            icons_hashtag[i]['ptr_label_obj'].text(icons_hashtag[i]['follows_from_hashtag']);
            $avatar = (icons_hashtag[i]['login_hashtag']).match("avatar_hashtag.jpg");            
             
            icons_hashtag[i]['ptr_p_obj'].text((icons_hashtag[i]['login_hashtag']).replace(/(^.{9}).*$/, '$1...'));

            icons_hashtag[i]['ptr_lnk_hashtag'].attr("href", 'https://www.instagram.com/explore/tags/' + icons_hashtag[i]['login_hashtag'] + '/');

            if (icons_hashtag[i]['status_hashtag'] === 'ended') {
                icons_hashtag[i]['ptr_p_obj'].css({'color': 'red'});
                $('#hashtag_status_list').append('<li>' + T('O sistema já seguiu todas as pessoas que postaram fotos no hashtag ') + '<b style="color:red">"' + icons_hashtag[i]['login_hashtag'] + '"</b></li>');
                hashtag_status = true;
            } else
            if (icons_hashtag[i]['status_hashtag'] === 'privated') {
                icons_hashtag[i]['ptr_p_obj'].css({'color': 'red'});
                $('#hashtag_status_list').append('<li>' + T('O hashtag ') + '<b style="color:red">"' + icons_hashtag[i]['login_hashtag'] + '"</b>' + T(' passou a ser privada') + '</li>');
                hashtag_status = true;
            } else
            if (icons_hashtag[i]['status_hashtag'] === 'deleted') {
                icons_hashtag[i]['ptr_p_obj'].css({'color': 'red'});
                $('#hashtag_status_list').append('<li>' + T('O hashtag ') + '<b style="color:red">"' + icons_hashtag[i]['login_hashtag'] + '"</b>' + T(' não existe mais no Instragram') + '</li>');
                hashtag_status = true;
            } else
                icons_hashtag[i]['ptr_p_obj'].css({'color': 'black'});
            icons_hashtag[i]['ptr_panel_obj'].css({"visibility": "visible", "display": "block"});
        }
        if (hashtag_status) {
            $('#hashtag_status_container').css({"visibility": "visible", "display": "block"})
        }
        if (num_hashtag) {
            $('#container_present_hashtag').css({"visibility": "visible", "display": "block"})
            $('#container_missing_hashtag').css({"visibility": "hidden", "display": "none"});
        } else {
            $('#container_missing_hashtag').css({"visibility": "visible", "display": "block"})
            $('#container_present_hashtag').css({"visibility": "hidden", "display": "none"});
        }
    }

    function inser_icons_hashtag(datas) {
        icons_hashtag[num_hashtag]['img_hashtag'] = datas['img_url'];
        icons_hashtag[num_hashtag]['login_hashtag'] = datas['profile'];
        icons_hashtag[num_hashtag]['follows_from_hashtag'] = datas['follows_from_profile'];//datas['follows_from_hashtag'];
        icons_hashtag[num_hashtag]['status_hashtag'] = datas['status_profile'];//datas['status_hashtag'];
        icons_hashtag[num_hashtag]['hashtag_pk'] = datas['hashtag_pk'];
        
        icons_hashtag[num_hashtag]['ptr_lnk_hashtag'].attr("href", 'https://www.instagram.com/explore/tags/' + datas['profile'] + '/');
        
        num_hashtag = num_hashtag + 1;
        display_hashtag();
        if (num_hashtag) {
            $('#container_present_hashtag').css({"visibility": "visible", "display": "block"})
            $('#container_missing_hashtag').css({"visibility": "hidden", "display": "none"});
        } else {
            $('#container_missing_hashtag').css({"visibility": "visible", "display": "block"})
            $('#container_present_hashtag').css({"visibility": "hidden", "display": "none"});
        }
    }
    
    function delete_icons_hashtag(name_localization) {
        var i, j;
        for (i = 0; i < num_hashtag; i++) {
            if (icons_hashtag[i]['login_hashtag'] === name_localization)
                break;
        }
        for (j = i; j < MAX_NUM_GEOLOCALIZATION - 1; j++) {
            icons_hashtag[j]['img_hashtag'] = icons_hashtag[j + 1]['img_hashtag'];
            if ((icons_hashtag[j + 1]['login_hashtag']).match("hashtag")) {
                icons_hashtag[j]['login_hashtag'] = 'hashtag' + (j + 1);
                icons_hashtag[j]['follows_from_hashtag'] = 0;
            } else {
                icons_hashtag[j]['login_hashtag'] = icons_hashtag[j + 1]['login_hashtag'];
                icons_hashtag[j]['follows_from_hashtag'] = icons_hashtag[j + 1]['follows_from_hashtag'];
            }
            icons_hashtag[j]['status_hashtag'] = icons_hashtag[j + 1]['status_hashtag'];
            icons_hashtag[j]['ptr_lnk_hashtag'].attr("href", icons_hashtag[j + 1]['ptr_lnk_hashtag'].attr("href"));
            icons_hashtag[j]['hashtag_pk'] = icons_hashtag[j+1]['hashtag_pk'];
        }
        icons_hashtag[j]['img_hashtag'] = base_url + 'assets/images/avatar_hashtag.jpg';
        icons_hashtag[j]['login_hashtag'] = 'hashtag' + (j + 1);
        icons_hashtag[j]['follows_from_hashtag'] = 0;
        icons_hashtag[j]['ptr_lnk_hashtag'].attr("href", "");
        icons_hashtag[j]['hashtag_pk']='';
        num_hashtag = num_hashtag - 1;
        display_hashtag();

        if (num_hashtag) {
            $('#container_present_hashtag').css({"visibility": "visible", "display": "block"})
            $('#container_missing_hashtag').css({"visibility": "hidden", "display": "none"});
        } else {
            $('#container_missing_hashtag').css({"visibility": "visible", "display": "block"})
            $('#container_present_hashtag').css({"visibility": "hidden", "display": "none"});
        }
    }
    
    $('#modal_container_add_hashtag').keypress(function (e) {
        if (e.which == 13) {
            $("#btn_insert_hashtag").click();
            return false;
        }
    });
    
    init_icons_hashtag(profiles);
}); 