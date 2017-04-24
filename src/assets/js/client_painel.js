$(document).ready(function () {    
    
    //----------------------------------------------------------------------------------------------------------
    //PERFIS DE REFERENCIA
    var icons_profiles = {
        0: {'ptr_img_obj': $('#img_ref_prof0'), 'ptr_p_obj': $('#name_ref_prof0'), 'ptr_label_obj': $('#cnt_follows_prof0'), 'ptr_panel_obj': $('#reference_profile0'), 'img_profile': '', 'login_profile': '', 'status_profile': '', 'follows_from_profile': '', 'ptr_lnk_ref_prof': $('#lnk_ref_prof0')},
        1: {'ptr_img_obj': $('#img_ref_prof1'), 'ptr_p_obj': $('#name_ref_prof1'), 'ptr_label_obj': $('#cnt_follows_prof1'), 'ptr_panel_obj': $('#reference_profile1'), 'img_profile': '', 'login_profile': '', 'status_profile': '', 'follows_from_profile': '', 'ptr_lnk_ref_prof': $('#lnk_ref_prof1')},
        2: {'ptr_img_obj': $('#img_ref_prof2'), 'ptr_p_obj': $('#name_ref_prof2'), 'ptr_label_obj': $('#cnt_follows_prof2'), 'ptr_panel_obj': $('#reference_profile2'), 'img_profile': '', 'login_profile': '', 'status_profile': '', 'follows_from_profile': '', 'ptr_lnk_ref_prof': $('#lnk_ref_prof2')},
        3: {'ptr_img_obj': $('#img_ref_prof3'), 'ptr_p_obj': $('#name_ref_prof3'), 'ptr_label_obj': $('#cnt_follows_prof3'), 'ptr_panel_obj': $('#reference_profile3'), 'img_profile': '', 'login_profile': '', 'status_profile': '', 'follows_from_profile': '', 'ptr_lnk_ref_prof': $('#lnk_ref_prof3')},
        4: {'ptr_img_obj': $('#img_ref_prof4'), 'ptr_p_obj': $('#name_ref_prof4'), 'ptr_label_obj': $('#cnt_follows_prof4'), 'ptr_panel_obj': $('#reference_profile4'), 'img_profile': '', 'login_profile': '', 'status_profile': '', 'follows_from_profile': '', 'ptr_lnk_ref_prof': $('#lnk_ref_prof4')},
        5: {'ptr_img_obj': $('#img_ref_prof5'), 'ptr_p_obj': $('#name_ref_prof5'), 'ptr_label_obj': $('#cnt_follows_prof5'), 'ptr_panel_obj': $('#reference_profile5'), 'img_profile': '', 'login_profile': '', 'status_profile': '', 'follows_from_profile': '', 'ptr_lnk_ref_prof': $('#lnk_ref_prof5')},
    };
    
    

    var num_profiles, flag = false;    
    var verify = false, flag_unfollow_request = false;
    unfollow_total = parseInt(unfollow_total);
    init_unfollow_type();

    $("#btn_verify_account").click(function () {
        if (!verify) {
            $("#btn_verify_account").text('CONFIRMO ATIVAÇÃO');
            verify = true;
        } else {
            $("#lnk_verify_account").attr('target', '_self');
            $("#lnk_verify_account").attr("href", base_url + 'index.php/welcome/client');
            //$(location).attr('href',base_url+'index.php/welcome/client');
            verify = false;
        }
    });

    $(".img_profile").hover(
            function (e) {
                //alert($(e.target).attr('id'))
                $('.img_profile').css('cursor', 'pointer');
            },
            function () {
                $('.img_profile').css('cursor', 'default');
            }
    );

    $("#my_img").hover(
            function () {
                $('#my_img').css('cursor', 'pointer');
            },
            function () {
                $('#my_img').css('cursor', 'default');
            }
    );

    $(".red_number").hover(
            function () {
                $('.red_number').css('cursor', 'pointer');
            },
            function () {
                $('.red_number').css('cursor', 'default');
            }
    );
    
    $("#my_container_toggle").hover(
            function () {
                $('#my_container_toggle').css('cursor', 'pointer');
            },
            function () {
                $('#my_container_toggle').css('cursor', 'default');
            }
    );

    $("#btn_unfollow_permition").click(function () {
        $("#message_status1").remove();
        $("#btn_unfollow_permition").remove();
        $("#message_status2").text(T('A SOLICITACÃO ESTA SENDO PROCESSADA'));
        $("#message_status3").text(T('INMEDIATAMENTE DE TERMINAR COMEÇARÁ A RECEBER O SERVIÇO'));
    });

    $("#img_ref_prof0").click(function () {
        if (!(icons_profiles[0]['login_profile']).match("perfilderef"))
            delete_profile_click(icons_profiles[0]['login_profile']);
    });

    $("#img_ref_prof1").click(function () {
        if (!(icons_profiles[1]['login_profile']).match("perfilderef"))
            delete_profile_click(icons_profiles[1]['login_profile']);
    });

    $("#img_ref_prof2").click(function () {
        if (!(icons_profiles[2]['login_profile']).match("perfilderef"))
            delete_profile_click(icons_profiles[2]['login_profile']);
    });

    $("#img_ref_prof3").click(function () {
        if (!(icons_profiles[3]['login_profile']).match("perfilderef"))
            delete_profile_click(icons_profiles[3]['login_profile']);
    });

    $("#img_ref_prof4").click(function () {
        if (!(icons_profiles[4]['login_profile']).match("perfilderef"))
            delete_profile_click(icons_profiles[4]['login_profile']);
    });

    $("#img_ref_prof5").click(function () {
        if (!(icons_profiles[5]['login_profile']).match("perfilderef"))
            delete_profile_click(icons_profiles[5]['login_profile']);
    });

    $("#btn_insert_profile").click(function () {
        if (validate_element('#login_profile', '^[a-zA-Z0-9\._]{1,300}$')) {
            if (num_profiles < MAX_NUM_PROFILES) {
                if ($('#login_profile').val() != '') {
                    //$("#waiting_inser_profile").css({"visibility":"visible","display":"block"});
                    var l = Ladda.create(this);
                    l.start();
                    $.ajax({
                        url: base_url + 'index.php/welcome/client_insert_profile',
                        data: {'profile': $('#login_profile').val()},
                        type: 'POST',
                        dataType: 'json',
                        success: function (response) {
                            if (response['success']) {
                                inser_icons_profiles(response);
                                $('#login_profile').val('');
                                $("#insert_profile_form").fadeOut();
                                $("#insert_profile_form").css({"visibility": "hidden", "display": "none"});
                                $('#reference_profile_message').text('');
                                $('#reference_profile_message').css('visibility', 'hidden');
                                if (num_profiles == MAX_NUM_PROFILES) {
                                    $('#btn_modal_close').click();
                                }
                            } else {
                                $('#reference_profile_message').text(response['message']);
                                $('#reference_profile_message').css('visibility', 'visible');
                                $('#reference_profile_message').css('color', 'red')
                                //alert(response['message']);                        
                            }
                            $("#waiting_inser_profile").css({"visibility": "hidden", "display": "none"});
                            l.stop();
                        },
                        error: function (xhr, status) {
                            $('#reference_profile_message').text(T('Não foi possível conectar com o Instagram'));
                            $('#reference_profile_message').css('visibility', 'visible');
                            $('#reference_profile_message').css('color', 'red');
                            //alert('Não foi possível conectar com o Instagram');
                            l.stop();
                        }
                    });
                }
            } else {
                $('#reference_profile_message').text(T('Alcançou a quantidade máxima.'));
                $('#reference_profile_message').css('visibility', 'visible');
                $('#reference_profile_message').css('color', 'red');
                //alert('Alcançou a quantidade maxima permitida');
            }
        } else {
            $('#reference_profile_message').text(T('* O nome do perfil só pode conter letras, números, sublinhados e pontos.'));
            $('#reference_profile_message').css('visibility', 'visible');
            $('#reference_profile_message').css('color', 'red');
            //alert('O nome de um perfil só pode conter combinações de letras, nÃºmeros, sublinhados e pontos.');
        }
    });

    $("#activate_account_by_status_3").click(function () {
        if ($('#userLogin').val() != '' && $('#userPassword').val() !== '') {
            if (validate_element('#userLogin', '^[a-zA-Z0-9\._]{1,300}$')) {
                var l = Ladda.create(this);
                l.start();
                l.start();
                $.ajax({
                    url: base_url + 'index.php/welcome/user_do_login',
                    //url : base_url+'index.php/welcome/client',
                    data: {
                        'user_login': $('#userLogin').val(),
                        'user_pass': $('#userPassword').val()
                    },
                    type: 'POST',
                    dataType: 'json',
                    async: false,
                    success: function (response) {
                        if (response['authenticated']) {
                            if (response['role'] == 'CLIENT') {
                                $(location).attr('href', base_url + 'index.php/welcome/' + response['resource'] + '');
                            }
                        } else {
                            alert('Senha incorreta');
                        }
                        l.stop();
                    },
                    error: function (xhr, status) {
                        alert(T('Erro encontrado. Informe para o atendimento seu caso.'));
                        l.stop();
                    }
                });
            } else {
                $('#container_login_message').text(T('O nome de um perfil só pode conter combinações de letras, números, sublinhados e pontos.'));
                $('#container_login_message').css('visibility', 'visible');
                $('#container_login_message').css('color', 'red');
            }
        } else {
            $('#container_login_message').text(T('Deve preencher todos os dados corretamente.'));
            $('#container_login_message').css('visibility', 'visible');
            $('#container_login_message').css('color', 'red');
        }
    });

    $("#cancel_usser_account").click(function () {
        //var l = Ladda.create(this);  l.start();
        if (confirm(T('Sugerimos entrar em contato com nosso Atendimento antes de cancelar sua assinatura. Deseja realmente iniciar o processo de cancelamento?')))
            window.open('https://docs.google.com/a/dumbu.pro/forms/d/e/1FAIpQLSejGY19wxZXEmMy_E9zcD-vODoimwpFAt4qQ-lN7TGYjbxYjw/viewform?c=0&w=1', '_blank');
    });

    $("#adding_profile").click(function () {
        if (num_profiles < MAX_NUM_PROFILES) {
            $("#insert_profile_form").fadeIn();
            $("#insert_profile_form").css({"visibility": "visible", "display": "block"});
        } else
            alert(T('Alcançou a quantidade maxima permitida'));
    });

    $("#btn_RP_status").click(function () {
        $('#reference_profile_status_container').css({"visibility": "hidden", "display": "none"})
    });

    $("#my_container_toggle").click(function () {
        if (unfollow_total) {
            confirm_message = 'Confirma ativar a opção UNFOLLOW NORMAL';
            tmp_unfollow_total = 0;
        } else {
            confirm_message = 'Confirma ativar a opção UNFOLLOW TOTAL';
            tmp_unfollow_total = 1;
        }

        if (confirm(confirm_message)) {
            $.ajax({
                url: base_url + 'index.php/welcome/unfollow_total',
                data: {
                    'unfollow_total': tmp_unfollow_total
                },
                type: 'POST',
                dataType: 'json',
                async: false,
                success: function (response) {
                    if (response['success']) {
                        //alert(parseInt(response['unfollow_total']));
                        set_global_var('unfollow_total', parseInt(response['unfollow_total']));
                        init_unfollow_type();
                    } else {
                        alert(T('Erro ao processar sua requisição. Tente depois...'));
                    }
                    //l.stop();
                },
                error: function (xhr, status) {
                    alert(T('Erro ao processar sua requisição. Tente depois...'));
                    //l.stop();
                }
            });
        }
    });

    function init_unfollow_type() {
        if (unfollow_total) {
            $('#left_toggle_buttom').css({'background-color': '#009CDE'});
            $('#right_toggle_buttom').css({'background-color': '#DFDFDF'});
        } else {
            $('#left_toggle_buttom').css({'background-color': '#DFDFDF'});
            $('#right_toggle_buttom').css({'background-color': '#009CDE'});
        }
    }

    function delete_profile_click(element) {
        if (confirm(T('Deseja elimiar o perfil de referência ') + element)) {
            $.ajax({
                url: base_url + 'index.php/welcome/client_desactive_profiles',
                data: {'profile': element},
                type: 'POST',
                dataType: 'json',
                success: function (response) {
                    if (response['success']) {
                        delete_icons_profiles(element);
                    } else
                        alert(response['message']);
                },
                error: function (xhr, status) {
                    alert(T('Não foi possível conectar com o Instagram'));
                }
            });
        }
    }

    function init_icons_profiles(datas) {
        response = jQuery.parseJSON(datas);
        prof = response['array_profiles'];
        if (response['message'] !== 'Profiles unloaded by instagram failed connection') {
            num_profiles = response['N'];            
            for (i = 0; i < num_profiles; i++) {
                if (!(typeof prof[i] === 'undefined')) {
                    icons_profiles[i]['img_profile'] = prof[i]['img_profile'];
                    icons_profiles[i]['follows_from_profile'] = prof[i]['follows_from_profile'];
                    icons_profiles[i]['login_profile'] = prof[i]['login_profile'];
                    icons_profiles[i]['status_profile'] = prof[i]['status_profile'];
                }
            }
            for (j = i; j < MAX_NUM_PROFILES; j++) {
                icons_profiles[j]['img_profile'] = base_url + 'assets/images/avatar.png';
                icons_profiles[j]['follows_from_profile'] = '0';
                icons_profiles[j]['login_profile'] = 'perfilderef' + (j + 1);
                icons_profiles[j]['status_profile'] = '0';
            }
            display_reference_profiles();
        } else {
            alert('Não foi possível comunicar com o Instagram pra verificar seus perfis de referência. Tente depois');
        }
    }

    function display_reference_profiles() {
        var reference_profiles_status = false;
        for (i = 0; i < MAX_NUM_PROFILES; i++) {
            icons_profiles[i]['ptr_img_obj'].attr("src", icons_profiles[i]['img_profile']);
            icons_profiles[i]['ptr_img_obj'].prop('title', T('Click para eliminar ') + icons_profiles[i]['login_profile']);
            icons_profiles[i]['ptr_p_obj'].prop('title', T('Ver ') + icons_profiles[i]['login_profile'] + T(' no Instagram'));
            icons_profiles[i]['ptr_label_obj'].text(icons_profiles[i]['follows_from_profile']);
            $avatar = (icons_profiles[i]['login_profile']).match("avatar.png");
            /*if($avatar){
             //icons_profiles[i]['ptr_p_obj']
             icons_profiles[i]['ptr_p_obj'].text((icons_profiles[i]['login_profile']).replace(/(^.{9}).*$/,'$1...'));
             }    
             else
             icons_profiles[i]['ptr_p_obj'].text((icons_profiles[i]['login_profile']));
             */
            icons_profiles[i]['ptr_p_obj'].text((icons_profiles[i]['login_profile']).replace(/(^.{9}).*$/, '$1...'));

            icons_profiles[i]['ptr_lnk_ref_prof'].attr("href", 'https://www.instagram.com/' + icons_profiles[i]['login_profile'] + '/');

            if (icons_profiles[i]['status_profile'] === 'ended') {
                icons_profiles[i]['ptr_p_obj'].css({'color': 'red'});
                $('#reference_profile_status_list').append('<li>' + T('O sistema já siguiu todos os seguidores do perfil de referência ') + '<b style="color:red">"' + icons_profiles[i]['login_profile'] + '"</b></li>');
                reference_profiles_status = true;
            } else
            if (icons_profiles[i]['status_profile'] === 'privated') {
                icons_profiles[i]['ptr_p_obj'].css({'color': 'red'});
                $('#reference_profile_status_list').append('<li>' + T('O perfil de referência ') + '<b style="color:red">"' + icons_profiles[i]['login_profile'] + '"</b>' + T(' passou a ser privado') + '</li>');
                reference_profiles_status = true;
            } else
            if (icons_profiles[i]['status_profile'] === 'deleted') {
                icons_profiles[i]['ptr_p_obj'].css({'color': 'red'});
                $('#reference_profile_status_list').append('<li>' + T('O perfil de referência ') + '<b style="color:red">"' + icons_profiles[i]['login_profile'] + '"</b>' + T(' não existe mais no Instragram') + '</li>');
                reference_profiles_status = true;
            } else
                icons_profiles[i]['ptr_p_obj'].css({'color': 'black'});
            icons_profiles[i]['ptr_panel_obj'].css({"visibility": "visible", "display": "block"});
        }
        if (reference_profiles_status) {
            $('#reference_profile_status_container').css({"visibility": "visible", "display": "block"})
        }
        if (num_profiles) {
            $('#container_present_profiles').css({"visibility": "visible", "display": "block"})
            $('#container_missing_profiles').css({"visibility": "hidden", "display": "none"});
        } else {
            $('#container_missing_profiles').css({"visibility": "visible", "display": "block"})
            $('#container_present_profiles').css({"visibility": "hidden", "display": "none"});
        }
    }

    function inser_icons_profiles(datas) {
        icons_profiles[num_profiles]['img_profile'] = datas['img_url'];
        icons_profiles[num_profiles]['login_profile'] = datas['profile'];
        icons_profiles[num_profiles]['follows_from_profile'] = datas['follows_from_profile'];
        icons_profiles[num_profiles]['status_profile'] = datas['status_profile'];
        icons_profiles[num_profiles]['ptr_lnk_ref_prof'].attr("href", 'https://www.instagram.com/' + datas['profile'] + '/');
        num_profiles = num_profiles + 1;
        display_reference_profiles();
        if (num_profiles) {
            $('#container_present_profiles').css({"visibility": "visible", "display": "block"})
            $('#container_missing_profiles').css({"visibility": "hidden", "display": "none"});
        } else {
            $('#container_missing_profiles').css({"visibility": "visible", "display": "block"})
            $('#container_present_profiles').css({"visibility": "hidden", "display": "none"});
        }
    }

    function delete_icons_profiles(name_profile) {
        var i, j;
        for (i = 0; i < num_profiles; i++) {
            if (icons_profiles[i]['login_profile'] === name_profile)
                break;
        }
        for (j = i; j < MAX_NUM_PROFILES - 1; j++) {
            icons_profiles[j]['img_profile'] = icons_profiles[j + 1]['img_profile'];
            if ((icons_profiles[j + 1]['login_profile']).match("perfilderef")) {
                icons_profiles[j]['login_profile'] = 'perfilderef' + (j + 1);
                icons_profiles[j]['follows_from_profile'] = 0;
            } else {
                icons_profiles[j]['login_profile'] = icons_profiles[j + 1]['login_profile'];
                icons_profiles[j]['follows_from_profile'] = icons_profiles[j + 1]['follows_from_profile'];
            }
            icons_profiles[j]['status_profile'] = icons_profiles[j + 1]['status_profile'];
            icons_profiles[j]['ptr_lnk_ref_prof'].attr("href", icons_profiles[j + 1]['ptr_lnk_ref_prof'].attr("href"));
        }
        icons_profiles[j]['img_profile'] = base_url + 'assets/images/avatar.png';
        icons_profiles[j]['login_profile'] = 'perfilderef' + (j + 1);
        icons_profiles[j]['follows_from_profile'] = 0;
        icons_profiles[j]['ptr_lnk_ref_prof'].attr("href", "");
        num_profiles = num_profiles - 1;
        display_reference_profiles();

        if (num_profiles) {
            $('#container_present_profiles').css({"visibility": "visible", "display": "block"})
            $('#container_missing_profiles').css({"visibility": "hidden", "display": "none"});
        } else {
            $('#container_missing_profiles').css({"visibility": "visible", "display": "block"})
            $('#container_present_profiles').css({"visibility": "hidden", "display": "none"});
        }
    }

    function validate_element(element_selector, pattern) {
        if (!$(element_selector).val().match(pattern)) {
            $(element_selector).css("border", "1px solid red");
            return false;
        } else {
            $(element_selector).css("border", "1px solid gray");
            return true;
        }
    }

    function change_plane(new_plane_id) {
        if (new_plane_id > plane_id)
            confirm_msg = T('Ao mudar para um plano maior, vc deve pagar a diferença. Confirma mudar de plano?');
        else
            confirm_msg = T('Confirma mudar de plano?');
        if (confirm(confirm_msg)) {
            $.ajax({
                url: base_url + 'index.php/welcome/change_plane',
                data: {
                    'plane_id': plane_id,
                    'new_plane_id': new_plane_id
                },
                type: 'POST',
                dataType: 'json',
                async: false,
                success: function (response) {
                    if (response['success'] == true) {
                        alert(response['success']);
                        $(location).attr('href', base_url + 'index.php/welcome/client');
                    } else {
                        alert(T('Não foi possível trocar de plano, Entre en contaco com o Atendimento'));
                    }
                    l.stop();
                },
                error: function (xhr, status) {
                    alert(T('Erro enviando sua solicitação. Reporte o caso para nosso Atendimento'));
                    l.stop();
                }
            });
        }
    }

    function actual_plane() {
        switch (plane_id) {
            case 1:
                $('#radio_plane_7_9990').attr('checked', true);
                $('#container_plane_7_9990').css({'border': '1px solid silver', 'box-shadow': '10px 10px 5px #ACC2BC'});
                $('#container_plane_4_90').css({'border': '1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
                $('#container_plane_9_90').css({'border': '1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
                $('#container_plane_29_90').css({'border': '1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
                $('#container_plane_99_90').css({'border': '1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
                break;
            case 2:
                $('#radio_plane_4_90').attr('checked', true);
                $('#container_plane_7_9990').css({'border': '1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
                $('#container_plane_4_90').css({'border': '1px solid silver', 'box-shadow': '10px 10px 5px #ACC2BC'});
                $('#container_plane_9_90').css({'border': '1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
                $('#container_plane_29_90').css({'border': '1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
                $('#container_plane_99_90').css({'border': '1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
                break;
            case 3:
                $('#radio_plane_9_90').attr('checked', true);
                $('#container_plane_7_9990').css({'border': '1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
                $('#container_plane_4_90').css({'border': '1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
                $('#container_plane_9_90').css({'border': '1px solid silver', 'box-shadow': '10px 10px 5px #ACC2BC'});
                $('#container_plane_29_90').css({'border': '1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
                $('#container_plane_99_90').css({'border': '1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
                break;
            case 4:
                $('#radio_plane_29_90').attr('checked', true);
                $('#container_plane_7_9990').css({'border': '1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
                $('#container_plane_4_90').css({'border': '1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
                $('#container_plane_9_90').css({'border': '1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
                $('#container_plane_29_90').css({'border': '1px solid silver', 'box-shadow': '10px 10px 5px #ACC2BC'});
                $('#container_plane_99_90').css({'border': '1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
                break;
            case 5:
                $('#radio_plane_99_90').attr('checked', true);
                $('#container_plane_7_9990').css({'border': '1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
                $('#container_plane_4_90').css({'border': '1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
                $('#container_plane_9_90').css({'border': '1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
                $('#container_plane_29_90').css({'border': '1px solid silver', 'box-shadow': '5px 5px 2px #888888'});
                $('#container_plane_99_90').css({'border': '1px solid silver', 'box-shadow': '10px 10px 5px #ACC2BC'});
                break;
        }
    }
    actual_plane();

    $('#modal_container_add_reference_rpofile').keypress(function (e) {
        if (e.which == 13) {
            $("#btn_insert_profile").click();
            return false;
        }
    });

    function set_global_var(str, value) {
        switch (str) {
            case 'unfollow_total':
                unfollow_total = value;
                break;
        }
    }
    
    $("#upgrade_plane").click(function () {
        
    });
    

    init_icons_profiles(profiles);
    
    
    //----------------------------------------------------------------------------------------------------------
    //GEOLOCALIZACAO
    
    var icons_geolocalization = {
        0: {'ptr_img_obj':$('#img_geolocalization0'), 'ptr_p_obj':$('#name_geolocalization0'), 'ptr_label_obj':$('#cnt_follows_geolocalization0'), 'ptr_panel_obj':$('#geolocalization0'), 'img_geolocalization':'', 'login_geolocalization':'', 'status_geolocalization':'', 'follows_from_geolocalization':'', 'ptr_lnk_geolocalization':$('#lnk_geolocalization0')},
        1: {'ptr_img_obj':$('#img_geolocalization1'), 'ptr_p_obj':$('#name_geolocalization1'), 'ptr_label_obj':$('#cnt_follows_geolocalization1'), 'ptr_panel_obj':$('#geolocalization1'), 'img_geolocalization':'', 'login_geolocalization':'', 'status_geolocalization':'', 'follows_from_geolocalization':'', 'ptr_lnk_geolocalization':$('#lnk_geolocalization1')},
        2: {'ptr_img_obj':$('#img_geolocalization2'), 'ptr_p_obj':$('#name_geolocalization2'), 'ptr_label_obj':$('#cnt_follows_geolocalization2'), 'ptr_panel_obj':$('#geolocalization2'), 'img_geolocalization':'', 'login_geolocalization':'', 'status_geolocalization':'', 'follows_from_geolocalization':'', 'ptr_lnk_geolocalization':$('#lnk_geolocalization2')},
        3: {'ptr_img_obj':$('#img_geolocalization3'), 'ptr_p_obj':$('#name_geolocalization3'), 'ptr_label_obj':$('#cnt_follows_geolocalization3'), 'ptr_panel_obj':$('#geolocalization3'), 'img_geolocalization':'', 'login_geolocalization':'', 'status_geolocalization':'', 'follows_from_geolocalization':'', 'ptr_lnk_geolocalization':$('#lnk_geolocalization3')},
        4: {'ptr_img_obj':$('#img_geolocalization4'), 'ptr_p_obj':$('#name_geolocalization4'), 'ptr_label_obj':$('#cnt_follows_geolocalization4'), 'ptr_panel_obj':$('#geolocalization4'), 'img_geolocalization':'', 'login_geolocalization':'', 'status_geolocalization':'', 'follows_from_geolocalization':'', 'ptr_lnk_geolocalization':$('#lnk_geolocalization4')},
        5: {'ptr_img_obj':$('#img_geolocalization5'), 'ptr_p_obj':$('#name_geolocalization5'), 'ptr_label_obj':$('#cnt_follows_geolocalization5'), 'ptr_panel_obj':$('#geolocalization5'), 'img_geolocalization':'', 'login_geolocalization':'', 'status_geolocalization':'', 'follows_from_geolocalization':'', 'ptr_lnk_geolocalization':$('#lnk_geolocalization5')}        
    };
    
    $("#upgrade_plane").click(function () {
             $("#myModal_geolocalization").modal('hide');    
    });
    
//    function disable_geolocalization_painel() {
//        if(plane_id==1||plane_id>3)
//            $('#container_geolocalization *').prop('disabled', false);
//        else
//            $('#container_geolocalization *').prop('disabled', true);            
//    }

    var num_geolocalization;
    
    $(".img_geolocalization").hover(
            function (e) {
                //alert($(e.target).attr('id'))
                $('.img_geolocalization').css('cursor', 'pointer');
            },
            function () {
                $('.img_geolocalization').css('cursor', 'default');
            }
    );   
   
    $("#img_geolocalization0").click(function () {
        if (!(icons_geolocalization[0]['login_geolocalization']).match("geolocalization"))
            delete_geolocalization_click(icons_geolocalization[0]['login_geolocalization']);
    });

    $("#img_geolocalization1").click(function () {
        if (!(icons_geolocalization[1]['login_geolocalization']).match("geolocalization"))
            delete_geolocalization_click(icons_geolocalization[1]['login_geolocalization']);
    });

    $("#img_geolocalization2").click(function () {
        if (!(icons_geolocalization[2]['login_geolocalization']).match("geolocalization"))
            delete_geolocalization_click(icons_geolocalization[2]['login_geolocalization']);
    });

    $("#img_geolocalization3").click(function () {
        if (!(icons_geolocalization[3]['login_geolocalization']).match("geolocalization"))
            delete_geolocalization_click(icons_geolocalization[3]['login_geolocalization']);
    });

    $("#img_geolocalization4").click(function () {
        if (!(icons_geolocalization[4]['login_geolocalization']).match("geolocalization"))
            delete_geolocalization_click(icons_geolocalization[4]['login_geolocalization']);
    });

    $("#img_geolocalization5").click(function () {
        if (!(icons_geolocalization[5]['login_geolocalization']).match("geolocalization"))
            delete_geolocalization_click(icons_geolocalization[5]['login_geolocalization']);
    });
        
    $("#btn_insert_geolocalization").click(function () {        
        if (validate_element('#login_geolocalization', '^[a-zA-Z0-9\._]{1,300}$')) {
            if(num_geolocalization < MAX_NUM_GEOLOCALIZATION) {
                if($('#login_geolocalization').val() != '') {                    
                    var l = Ladda.create(this);
                    l.start();
                    $.ajax({
                        url: base_url + 'index.php/welcome/client_insert_geolocalization',
                        data: {'geolocalization': $('#login_geolocalization').val()},
                        type: 'POST',
                        dataType: 'json',
                        success: function (response) {
                            if (response['success']) {
                                inser_icons_geolocalization(response);
                                $('#login_geolocalization').val('');
                                $("#insert_geolocalization_form").fadeOut();
                                $("#insert_geolocalization_form").css({"visibility": "hidden", "display": "none"});
                                $('#geolocalization_message').text('');
                                $('#geolocalization_message').css('visibility', 'hidden');
                                if (num_geolocalization === MAX_NUM_GEOLOCALIZATION) {
                                    $('#btn_modal_close').click();
                                }
                            } else {
                                $('#geolocalization_message').text(response['message']);
                                $('#geolocalization_message').css('visibility', 'visible');
                                $('#geolocalization_message').css('color', 'red');   
                            }                            
                            l.stop();
                        },
                        error: function (xhr, status) {
                            $('#geolocalization_message').text(T('Não foi possível conectar com o Instagram'));
                            $('#geolocalization_message').css('visibility', 'visible');
                            $('#geolocalization_message').css('color', 'red');
                            l.stop();
                        }
                    });
                }
            } else {
                $('#geolocalization_message').text(T('Alcançou a quantidade máxima.'));
                $('#geolocalization_message').css('visibility', 'visible');
                $('#geolocalization_message').css('color', 'red');            
            }
        } else {
            $('#geolocalization_message').text(T('* O nome da geolocalização só pode conter letras, números, sublinhados e pontos.'));
            $('#geolocalization_message').css('visibility', 'visible');
            $('#geolocalization_message').css('color', 'red');
        }
    });

    /*$("#btn_RP_status").click(function () {
        $('#reference_profile_status_container').css({"visibility": "hidden", "display": "none"})
    });*/
    
    function delete_geolocalization_click(element) {
        if (confirm(T('Deseja elimiar a geolocalização ') + element)) {
            $.ajax({
                url: base_url + 'index.php/welcome/client_desactive_geolocalization',
                data: {'geolocalization': element},
                type: 'POST',
                dataType: 'json',
                success: function (response) {
                    if (response['success']) {
                        delete_icons_geolocalization(element);
                    } else
                        alert(response['message']);
                },
                error: function (xhr, status) {
                    alert(T('Não foi possível conectar com o Instagram'));
                }
            });
        }
    }

    function init_icons_geolocalization(datas) {
        response = jQuery.parseJSON(datas);
        prof = response['array_geolocalization'];
        //prof = response['array_profiles'];
        if (response['message'] !== 'Geolocalizations unloaded by Instagram failed connection') {
            num_geolocalization = response['N_geolocalization'];
            for (i = 0; i < num_geolocalization; i++) {
                //if (!(typeof prof[i]==='undefined')) {
                    icons_geolocalization[i]['img_geolocalization'] = prof[i]['img_geolocalization'];
                    icons_geolocalization[i]['follows_from_geolocalization'] = prof[i]['follows_from_geolocalization'];
                    icons_geolocalization[i]['login_geolocalization'] = prof[i]['login_geolocalization'];
                    icons_geolocalization[i]['status_geolocalization'] = prof[i]['status_geolocalization'];
                    
                    /*icons_geolocalization[i]['img_geolocalization'] = prof[i]['img_profile'];
                    icons_geolocalization[i]['follows_from_geolocalization'] = prof[i]['follows_from_profile'];
                    icons_geolocalization[i]['login_geolocalization'] = prof[i]['login_profile'];
                    icons_geolocalization[i]['status_geolocalization'] = prof[i]['status_profile'];*/
                //}
            }
            for (j = i; j < MAX_NUM_GEOLOCALIZATION; j++) {
                icons_geolocalization[j]['img_geolocalization'] = base_url + 'assets/images/avatar_geolocalization.jpg';
                icons_geolocalization[j]['follows_from_geolocalization'] = '0';
                icons_geolocalization[j]['login_geolocalization'] = 'geolocalization' + (j + 1);
                icons_geolocalization[j]['status_geolocalization'] = '0';
            }
            display_geolocalization();
        } else {
            alert('Não foi possível comunicar com o Instagram pra verificar seus perfis de referência. Tente depois');
        }
    }

    function display_geolocalization() {
        var geolocalization_status = false;
        for (i = 0; i < MAX_NUM_GEOLOCALIZATION; i++) {
            icons_geolocalization[i]['ptr_img_obj'].attr("src", icons_geolocalization[i]['img_geolocalization']);
            icons_geolocalization[i]['ptr_img_obj'].prop('title', T('Click para eliminar ') + icons_geolocalization[i]['login_geolocalization']);
            icons_geolocalization[i]['ptr_p_obj'].prop('title', T('Ver ') + icons_geolocalization[i]['login_geolocalization'] + T(' no Instagram'));
            icons_geolocalization[i]['ptr_label_obj'].text(icons_geolocalization[i]['follows_from_geolocalization']);
            $avatar = (icons_geolocalization[i]['login_geolocalization']).match("avatar_geolocalization.jpg");            
             
            icons_geolocalization[i]['ptr_p_obj'].text((icons_geolocalization[i]['login_geolocalization']).replace(/(^.{9}).*$/, '$1...'));

            icons_geolocalization[i]['ptr_lnk_geolocalization'].attr("href", 'https://www.instagram.com/' + icons_geolocalization[i]['login_geolocalization'] + '/');

            if (icons_geolocalization[i]['status_geolocalization'] === 'ended') {
                icons_geolocalization[i]['ptr_p_obj'].css({'color': 'red'});
                $('#geolocalization_status_list').append('<li>' + T('O sistema já siguiu todas as pessoas que postaram fotos na geolocalização ') + '<b style="color:red">"' + icons_geolocalization[i]['login_geolocalization'] + '"</b></li>');
                geolocalization_status = true;
            } else
            if (icons_geolocalization[i]['status_geolocalization'] === 'privated') {
                icons_geolocalization[i]['ptr_p_obj'].css({'color': 'red'});
                $('#geolocalization_status_list').append('<li>' + T('A geolocalização ') + '<b style="color:red">"' + icons_geolocalization[i]['login_geolocalization'] + '"</b>' + T(' passou a ser privada') + '</li>');
                geolocalization_status = true;
            } else
            if (icons_geolocalization[i]['status_geolocalization'] === 'deleted') {
                icons_geolocalization[i]['ptr_p_obj'].css({'color': 'red'});
                $('#geolocalization_status_list').append('<li>' + T('A geolocalização ') + '<b style="color:red">"' + icons_geolocalization[i]['login_geolocalization'] + '"</b>' + T(' não existe mais no Instragram') + '</li>');
                geolocalization_status = true;
            } else
                icons_geolocalization[i]['ptr_p_obj'].css({'color': 'black'});
            icons_geolocalization[i]['ptr_panel_obj'].css({"visibility": "visible", "display": "block"});
        }
        if (geolocalization_status) {
            $('#geolocalization_status_container').css({"visibility": "visible", "display": "block"})
        }
        if (num_geolocalization) {
            $('#container_present_geolocalization').css({"visibility": "visible", "display": "block"})
            $('#container_missing_geolocalization').css({"visibility": "hidden", "display": "none"});
        } else {
            $('#container_missing_geolocalization').css({"visibility": "visible", "display": "block"})
            $('#container_present_geolocalization').css({"visibility": "hidden", "display": "none"});
        }
    }

    function inser_icons_geolocalization(datas) {
        icons_geolocalization[num_geolocalization]['img_geolocalization'] = datas['img_url'];
        icons_geolocalization[num_geolocalization]['login_geolocalization'] = datas['profile'];
        icons_geolocalization[num_geolocalization]['follows_from_geolocalization'] = datas['follows_from_profile'];//datas['follows_from_geolocalization'];
        icons_geolocalization[num_geolocalization]['status_geolocalization'] = datas['status_profile'];//datas['status_geolocalization'];
        icons_geolocalization[num_geolocalization]['ptr_lnk_geolocalization'].attr("href", 'https://www.instagram.com/' + datas['profile'] + '/');
        num_geolocalization = num_geolocalization + 1;
        display_geolocalization();
        if (num_geolocalization) {
            $('#container_present_geolocalization').css({"visibility": "visible", "display": "block"})
            $('#container_missing_geolocalization').css({"visibility": "hidden", "display": "none"});
        } else {
            $('#container_missing_geolocalization').css({"visibility": "visible", "display": "block"})
            $('#container_present_geolocalization').css({"visibility": "hidden", "display": "none"});
        }
    }
    
    function delete_icons_geolocalization(name_localization) {
        var i, j;
        for (i = 0; i < num_geolocalization; i++) {
            if (icons_geolocalization[i]['login_geolocalization'] === name_localization)
                break;
        }
        for (j = i; j < MAX_NUM_GEOLOCALIZATION - 1; j++) {
            icons_geolocalization[j]['img_geolocalization'] = icons_geolocalization[j + 1]['img_geolocalization'];
            if ((icons_geolocalization[j + 1]['login_geolocalization']).match("geolocalization")) {
                icons_geolocalization[j]['login_geolocalization'] = 'geolocalization' + (j + 1);
                icons_geolocalization[j]['follows_from_geolocalization'] = 0;
            } else {
                icons_geolocalization[j]['login_geolocalization'] = icons_geolocalization[j + 1]['login_geolocalization'];
                icons_geolocalization[j]['follows_from_geolocalization'] = icons_geolocalization[j + 1]['follows_from_geolocalization'];
            }
            icons_geolocalization[j]['status_geolocalization'] = icons_geolocalization[j + 1]['status_geolocalization'];
            icons_geolocalization[j]['ptr_lnk_geolocalization'].attr("href", icons_geolocalization[j + 1]['ptr_lnk_geolocalization'].attr("href"));
        }
        icons_geolocalization[j]['img_geolocalization'] = base_url + 'assets/images/avatar_geolocalization.jpg';
        icons_geolocalization[j]['login_geolocalization'] = 'geolocalization' + (j + 1);
        icons_geolocalization[j]['follows_from_geolocalization'] = 0;
        icons_geolocalization[j]['ptr_lnk_geolocalization'].attr("href", "");
        num_geolocalization = num_geolocalization - 1;
        display_geolocalization();

        if (num_geolocalization) {
            $('#container_present_geolocalization').css({"visibility": "visible", "display": "block"})
            $('#container_missing_geolocalization').css({"visibility": "hidden", "display": "none"});
        } else {
            $('#container_missing_geolocalization').css({"visibility": "visible", "display": "block"})
            $('#container_present_geolocalization').css({"visibility": "hidden", "display": "none"});
        }
    }
    
    $('#modal_container_add_geolocalization').keypress(function (e) {
        if (e.which == 13) {
            $("#btn_insert_geolocalization").click();
            return false;
        }
    });
    //disable_geolocalization_painel();
    init_icons_geolocalization(profiles);
}); 