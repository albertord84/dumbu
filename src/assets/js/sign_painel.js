$(document).ready(function () {

    active_by_steep(1);

    $('#palno_mensal').prop('disabled', true);

    // Read a page's GET URL variables and return them as an associative array.
    function getUrlVars(){
        var vars = [], hash;
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for (var i = 0; i < hashes.length; i++){
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        return vars;
    }
                
    $("#check_cupao").click(function () {
        if($("#cupao_number").val()!==''){
            var l = Ladda.create(this);
            l.start();
            $.ajax({
                url: base_url + 'index.php/welcome/check_ticket_peixe_urbano',
                data: {
                    'cupao_number': $('#cupao_number').val(),
                    'pk': pk
                },
                type: 'POST',
                dataType: 'json',
                success: function (response) {
                    if (response['success']) {
                        set_global_var('cupao_number_checked', true);  
                    }
                    alert(response['message']);
                    l.stop();
                },
                error: function (xhr, status) {
                    alert('Não foi possível conferir a autenticidade do cupão. Tente depois.');                    
                    l.stop();
                }
            });
        }else{
            alert('Deve preencher o campo com o código do cupão');  
        }
    });
    
    $("#signin_btn_insta_login").click(function () {           
        if ($('#signin_clientLogin').val() != '' && $('#signin_clientPassword').val() != '' && $('#client_email').val() != '') {
            if (validate_element('#client_email', "^[a-zA-Z0-9\._-]+@([a-zA-Z0-9-]{2,}[.])*[a-zA-Z]{2,4}$")) {
                if (validate_element('#signin_clientLogin', '^[a-zA-Z0-9\._]{1,300}$')) {                   
                    if($("#errorcaptcha").text()!='Codigo de segurança errado' && $("#errorcaptcha").text()!=''){
                        var l = Ladda.create(this);
                        l.start();
                        l.start();
                        $.ajax({
                            url: base_url + 'index.php/welcome/check_user_for_sing_in',
                            data: {
                                'client_email': $('#client_email').val(),
                                'client_login': $('#signin_clientLogin').val(),
                                'client_pass': $('#signin_clientPassword').val(),
                                'utm_source': typeof getUrlVars()["utm_source"] !== 'undefined' ? getUrlVars()["utm_source"] : 'NULL'
                            },
                            type: 'POST',
                            dataType: 'json',
                            success: function (response) {
                                if (response['success']) {
                                    set_global_var('insta_profile_datas', jQuery.parseJSON(response['datas']));
                                    set_global_var('pk', response['pk']);
                                    set_global_var('datas', response['datas']);
                                    set_global_var('early_client_canceled', response['early_client_canceled']);
                                    set_global_var('login', $('#signin_clientLogin').val());
                                    set_global_var('pass', $('#signin_clientPassword').val());
                                    set_global_var('email', $('#client_email').val());
                                    set_global_var('need_delete', response['need_delete']);
                                    //if(need_delete<response['MIN_MARGIN_TO_INIT']){   
                                     //alert('Você precisa desseguer pelo menos '+need_delete+' usuários para que o sistema funcione corretamente');                                
                                    // }
                                    active_by_steep(2);
                                    l.stop();
                                } else {
                                    if (response['cause'] == 'checkpoint_required') {
                                        alert(response['message']);
                                    } else {
                                        $('#container_sigin_message').text(response['message']);
                                        $('#container_sigin_message').css('visibility', 'visible');
                                        $('#container_sigin_message').css('color', 'red');
                                    }
                                    l.stop();
                                }

                            },
                            error: function (xhr, status) {
                                $('#container_sigin_message').text(T('Não foi possível comprobar a autenticidade do usuario no Instagram!'));
                                $('#container_sigin_message').css('visibility', 'visible');
                                $('#container_sigin_message').css('color', 'red');
                                l.stop();
                            }
                        });
                    } else{
                        $('#container_sigin_message').text(T('Verifique o codigo de segurança'));
                        $('#container_sigin_message').css('visibility', 'visible');
                        $('#container_sigin_message').css('color', 'red');                    
                    }    
                } else {
                    $('#container_sigin_message').text(T('O nome de um perfil só pode conter combinações de letras, números, sublinhados e pontos!'));
                    $('#container_sigin_message').css('visibility', 'visible');
                    $('#container_sigin_message').css('color', 'red');
                }
            } else {
                $('#container_sigin_message').text(T('Problemas na estrutura do email informado!'));
                $('#container_sigin_message').css('visibility', 'visible');
                $('#container_sigin_message').css('color', 'red');
                //alert('O email informado não é correto');
            }
        } else {
            $('#container_sigin_message').text(T('Deve preencher todos os dados corretamente!'));
            $('#container_sigin_message').css('visibility', 'visible');
            $('#container_sigin_message').css('color', 'red');
            //alert('Formulario incompleto');
        }
    });
    
    $("#btn_sing_in").click(function () {
//        alert("Estamos apresentando problemas com o pagamento, por favor entre em contato como atendimento! Obrigado");
//        return;
        if (flag == true) {
            flag = false;
            $('#btn_sing_in').attr('disabled', true);
            $('#btn_sing_in').css('cursor', 'wait');
            $('#my_body').css('cursor', 'wait');
            var l = Ladda.create(this);
            l.start();
            l.start();
            var name = validate_element('#client_credit_card_name', "^[A-Z ]{4,50}$");
            var number = validate_element('#client_credit_card_number', "^[0-9]{10,20}$");
            var cvv = validate_element('#client_credit_card_cvv', "^[0-9 ]{3,5}$");
            var month = validate_month('#client_credit_card_validate_month', "^[0-10-9]{2,2}$");
            var year = validate_year('#client_credit_card_validate_year', "^[2-20-01-20-9]{4,4}$");            
            if (name && number && cvv && month && year) {
                $.ajax({
                    url: base_url + 'index.php/welcome/check_client_data_bank',
                    data: {
                        'user_login': login,
                        'user_pass': pass,
                        'user_email': email,
                        'client_credit_card_number': $('#client_credit_card_number').val(),
                        'client_credit_card_cvv': $('#client_credit_card_cvv').val(),
                        'client_credit_card_name': $('#client_credit_card_name').val(),
                        'client_credit_card_validate_month': $('#client_credit_card_validate_month').val(),
                        'client_credit_card_validate_year': $('#client_credit_card_validate_year').val(),
                        'need_delete': need_delete,
                        'early_client_canceled': early_client_canceled,
                        'plane_type': plane,
                        'pk': pk,
                        'datas': datas
                    },
                    type: 'POST',
                    dataType: 'json',
                    success: function (response) {
                        if (response['success']) {
                            //alert("Sua compra foi realizada corretamente. Você sera redirecionado ...");
                            //$(location).attr('href',base_url+'index.php/welcome/client');
                            $(location).attr('href', base_url + 'index.php/welcome/purchase');
                        } else {
                            alert(response['message']);
                            set_global_var('flag', true);
                            $('#btn_sing_in').attr('disabled', false);
                            $('#btn_sing_in').css('cursor', 'pointer');
                            $('#my_body').css('cursor', 'auto');
                            l.stop();
                        }
                    },
                    error: function (xhr, status) {
                        set_global_var('flag', true);
                    }
                });
            } else {
                alert('Verifique os dados fornecidos');
                set_global_var('flag', true);
                $('#btn_sing_in').attr('disabled', false);
                $('#btn_sing_in').css('cursor', 'pointer');
                $('#my_body').css('cursor', 'auto');
                l.stop();
            }            
        } else {
            console.log('paymet working');
        }
    });
    
    $('#container_login_panel').keypress(function (e) {
        if (e.which == 13) {
            $("#signin_btn_insta_login").click();
            return false;
        }
    });

    $('#radio_plane_4_90').click(function () {
        $("#container_plane_4_90").addClass( "active" );
        $("#container_plane_9_90").removeClass( "active" );
        $("#container_plane_29_90").removeClass( "active" );
        $("#container_plane_99_90").removeClass( "active" );
        plane = '2';
    });
    $('#radio_plane_9_90').click(function () {
        $("#container_plane_4_90").removeClass( "active" );
        $("#container_plane_9_90").addClass( "active" );
        $("#container_plane_29_90").removeClass( "active" );
        $("#container_plane_99_90").removeClass( "active" );
        plane = '3';
    });
    $('#radio_plane_29_90').click(function () {
        $("#container_plane_4_90").removeClass( "active" );
        $("#container_plane_9_90").removeClass( "active" );
        $("#container_plane_29_90").addClass( "active" );
        $("#container_plane_99_90").removeClass( "active" );
        plane = '4';
    });
    $('#container_plane_99_90').click(function () {
        $("#container_plane_4_90").removeClass( "active" );
        $("#container_plane_9_90").removeClass( "active" );
        $("#container_plane_29_90").removeClass( "active" );
        $("#container_plane_99_90").addClass( "active" );
        plane = '5';
    });
    
    $('#container_plane_4_90').hover(function () {$("#container_plane_4_90").css('cursor', 'pointer');},function () {$("#container_plane_4_90").css('cursor', 'auto');});
    $('#container_plane_9_90').hover(function () {$("#container_plane_9_90").css('cursor', 'pointer');},function () {$("#container_plane_9_90").css('cursor', 'auto');});
    $('#container_plane_29_90').hover(function () {$("#container_plane_29_90").css('cursor', 'pointer');},function () {$("#container_plane_29_90").css('cursor', 'auto');});
    $('#container_plane_99_90').hover(function () {$("#container_plane_99_90").css('cursor', 'pointer');},function () {$("#container_plane_99_90").css('cursor', 'auto');});



    $('#coniner_data_panel').css({'height': ''+$('#coniner_login_panel').height()});
    $('#container_sing_in_panel').css({'height': ''+$('#coniner_login_panel').height()});

    function active_by_steep(steep) {
        switch (steep) {
            case 1:               
                $('#container_login_panel').css('visibility', 'visible');
                $('#container_login_panel').css('display', 'block');
                $('#signin_profile').css({'visibility':'hidden','display':'none'});                
                $('#coniner_data_panel *').prop('disabled', true);
                $('#coniner_data_panel').css('background-color', '#F5F5F5');
                $('#container_sing_in_panel *').prop('disabled', true);
                $('#container_sing_in_panel').css('background-color', '#F5F5F5');
                $("#btn_sing_in").hover(function () {
                    $('#btn_sing_in').css('cursor', 'not-allowed');
                }, function () { });                
                $("#coniner_data_panel *").hover(function () {
                    $('#coniner_data_panel *').css('cursor', 'not-allowed');
                }, function () { });                
                $("#container_sing_in_panel *").hover(function () {
                    $('#container_sing_in_panel *').css('cursor', 'not-allowed');
                }, function () { });                
                break;                
            case 2:                
                $('#login_sign_in').css('visibility', 'hidden');
                $('#container_sigin_message').css('visibility', 'hidden');
                $('#container_login_panel').css('visibility', 'hidden');
                $('#container_login_panel').css('display', 'none');
                $('#signin_profile').css('visibility', 'visible');
                $('#signin_profile').css('display', 'block');
                $('#img_ref_prof').attr("src", insta_profile_datas.profile_pic_url);
                $('#name_ref_prof').text(insta_profile_datas.username);
                $('#ref_prof_followers').text('Seguidores: ' + insta_profile_datas.follower_count);
                $('#ref_prof_following').text('Seguindo: ' + insta_profile_datas.following);
                $('#coniner_data_panel *').prop('disabled', false);
                $('#coniner_data_panel *').css('color', '#000000');
                $('#container_sing_in_panel *').prop('disabled', false);
                $('#container_sing_in_panel *').css('color', '#000000');
                $('#coniner_data_panel').css('background-color', 'transparent');
                $('#container_sing_in_panel').css('background-color', 'transparent');
                $('#btn_sing_in').css('cursor', 'default');
                $("#coniner_data_panel *").hover(function () {
                    $('#coniner_data_panel *').css('cursor', 'auto');
                }, function () { });
                $("#container_sing_in_panel *").hover(function () {
                    $('#container_sing_in_panel *').css('cursor', 'auto');
                }, function () { });
                break;
        }
    }

    $("#show_login").click(function () {
        $("#loginform").fadeIn();
        $("#loginform").css({"visibility": "visible", "display": "block"});
    });

    $("#close_login").click(function () {
        $("#loginform").fadeOut();
        $("#loginform").css({"visibility": "hidden", "display": "none"});
    });

    $("#lnk_use_term").click(function () {
        url = base_url + "assets/others/TERMOS DE USO DUMBU.pdf";
        window.open(url, '_blank');
        return false;
    });

    function validate_element(element_selector, pattern) {
        if (!$(element_selector).val().match(pattern)) {
            $(element_selector).css("border", "1px solid red");
            return false;
        } else {
            $(element_selector).css("border", "1px solid gray");
            return true;
        }
    }

    function validate_month(element_selector, pattern) {
        if (!$(element_selector).val().match(pattern) || Number($(element_selector).val()) > 12) {
            $(element_selector).css("border", "1px solid red");
            return false;
        } else {
            $(element_selector).css("border", "1px solid gray");
            return true;
        }
    }
    
    function validate_year(element_selector, pattern) {
        if (!$(element_selector).val().match(pattern) || Number($(element_selector).val()) < 2017) {
            $(element_selector).css("border", "1px solid red");
            return false;
        } else {
            $(element_selector).css("border", "1px solid gray");
            return true;
        }
    }

    function set_global_var(str, value) {
        switch (str) {
            case 'pk':
                pk = value;
                break;
            case 'early_client_canceled':
                early_client_canceled = value;
                break;
            case 'need_delete':
                need_delete = value;
                break;
            case 'login':
                login = value;
                break;
            case 'pass':
                pass = value;
                break;
            case 'datas':
                datas = value;
                break;
            case 'email':
                email = value;
                break;
            case 'flag':
                flag = value;
                break;
            case 'insta_profile_datas':
                insta_profile_datas = value;
                break;
            case 'cupao_number_checked':
                cupao_number_checked = value;
                break;
        }
    }



    var plane, pk, datas,cupao_number_checked=false, early_client_canceled = false, login, pass, email, insta_profile_datas, need_delete = 0, flag = true, option_seven_days = true;
    plane = '4';
}); 