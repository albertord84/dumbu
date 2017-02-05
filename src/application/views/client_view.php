<!DOCTYPE html>
<html lang="pt_BR">
    <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <?php  $CI =& get_instance();?>
            <meta name="description" content="<?php echo $CI->T("Ganhar seguidores no Instagram. Aumente seus seguidores reais e qualificados de forma segmentada no Instagram. Followers, curtidas, geolocalizção, direct",array());?>">
            <meta name="keywords" content="<?php echo $CI->T("ganhar, seguidores, Instagram, seguidores segmentados, curtidas, followers, geolocalizção, direct, vendas",array());?>">
            <meta name="revisit-after" content="7 days">
            <meta name="robots" content="index,follow">
            <meta name="distribution" content="global">
            <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
            <title>DUMBU</title>
            <link rel="shortcut icon" href="<?php echo base_url().'assets/images/icon.png'?>"> 

            <!-- jQuery -->
            <script type="text/javascript" src="<?php echo base_url().'assets/js/jquery.js';?>"></script>

            <!-- Bootstrap -->
            <link href="<?php echo base_url().'assets/bootstrap/css/bootstrap.min.css';?>" rel="stylesheet">
            <link href="<?php echo base_url().'assets/css/loading.css';?>" rel="stylesheet">
            <link href="<?php echo base_url().'assets/css/style.css';?>" rel="stylesheet">

            <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/default.css';?>" />
            <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/component.css';?>" />
            <script type="text/javascript" src="<?php echo base_url().'assets/js/modernizr.custom.js';?>"></script>

            <link rel="stylesheet" href="<?php echo base_url().'assets/css/ladda-themeless.min.css'?>">
            <script src="<?php echo base_url().'assets/js/spin.min.js'?>"></script>
            <script src="<?php echo base_url().'assets/js/ladda.min.js'?>"></script>

            <script type="text/javascript">var base_url = '<?php echo base_url();?>';</script> 
            <script type="text/javascript" src="<?php echo base_url().'assets/js/client_painel.js';?>"></script>
            <script type="text/javascript" src="<?php echo base_url().'assets/js/talkme_painel.js';?>"></script>
            <script type="text/javascript" src="<?php echo base_url().'assets/js/update_client_painel.js';?>"></script>
            
            <?php include_once("pixel_facebook.php")?>
    </head>
    <body>
            <?php include_once("analyticstracking.php") ?>
            <?php include_once("remarketing.php")?>
            <div class="windows8">
             <div class="wBall" id="wBall_1">
              <div class="wInnerBall"></div>
             </div>
             <div class="wBall" id="wBall_2">
              <div class="wInnerBall"></div>
             </div>
             <div class="wBall" id="wBall_3">
              <div class="wInnerBall"></div>
             </div>
             <div class="wBall" id="wBall_4">
              <div class="wInnerBall"></div>
             </div>
             <div class="wBall" id="wBall_5">
              <div class="wInnerBall"></div>
             </div>
            </div>
            <header class="bk-black">
                    <div class="container">
                            <nav class="navbar navbar-default navbar-static-top">
                                    <!-- Collect the nav links, forms, and other content for toggling -->
                                    <div class="logo pabsolute fleft100 text-center">	
                                            <a class="navbar-brand i-block" href="#">
                                                    <img alt="Brand" src="<?php echo base_url().'assets/images/logo.png';?>">
                                            </a>
                                    </div>
                                    <ul class="nav navbar-nav navbar-right">
                                            <li><a href="<?php echo base_url().'index.php/welcome/log_out'?>"><img src="<?php echo base_url().'assets/images/user.png';?>" class="wauto us" alt=""><?php echo $CI->T("SAIR",array());?></a></li>
                                    </ul>
                            </nav>
                    </div>
            </header>

            <section id="perfil" class="fleft100">
                    <div class="container">	


                    <!---------------------------------------------------------------------------------------->
                    <?php
                        echo '<script type="text/javascript">';
                        echo 'var plane_id='.$plane_id.';';
                        if(isset($profiles))
                            echo 'var profiles='.json_encode($profiles).';';
                        else
                             echo 'var profiles='.json_encode(array()).';';
                        if(isset($MAX_NUM_PROFILES))
                            echo 'var MAX_NUM_PROFILES='.$MAX_NUM_PROFILES.';';
                        echo '</script>' ;
                    ?>
                    <!---------------------------------------------------------------------------------------->
                    <br>
                    <div class="row">
                        <?php
                            switch ($status['status_id']) {
                                case 3:
                                    echo '
                                        <div id="activate_account" class="center" style="margin-left:25%; width:50%; padding: 2%;  border:1px solid red; border-radius:5px ">
                                            <b style="margin:1%; font-family:sans-serif; font-size:1em; color:red;">'.$CI->T("SAIR",array()).'</b><BR>
                                            <b style="margin:1%; font-family:sans-serif; font-size:0.8em;">'.$CI->T("INFORME NOVAMENTE LOGIN E SENHA DE INSTAGRAM",array()).'</b>             
                                            <br><br>
                                            <form id="usersLoginForm"  action="#" method="#"  class="form" role="form" style="margin-left:25%;margin-right:25%;"  accept-charset="UTF-8" >                                
                                                <div class="form-group">                                                                
                                                     <input id="userLogin" type="text" class="form-control" value="'.$my_login_profile.'" disabled="true">
                                                </div>
                                                <div class="form-group">                                                                
                                                    <input id="userPassword" type="password" class="form-control" placeholder="'.$CI->T("Senha",array()).'" required>
                                                </div>                                                             
                                                <div class="form-group">
                                                    <button id="activate_account_by_status_3" class="btn btn-success btn-block ladda-button" type="button" data-style="expand-left" data-spinner-color="#ffffff">
                                                        <span class="ladda-label">'.$CI->T("ACTIVAR AGORA",array()).'</span>
                                                    </button>
                                                </div>
                                                <div id="container_login_message" class="form-group" style="text-align:justify;visibility:hidden; font-family:sans-serif; font-size:0.9em">                                                        
                                                </div>
                                            </form>
                                        </div>';
                                    break;
                                case 2:
                                    echo '
                                        <div class="center" style="margin-left:20%; width:60%; padding: 2%;  border:1px solid red; border-radius:5px ">
                                            <b style="margin:1%; font-family:sans-serif; font-size:1em; color:red;">'.$CI->T("HABILITE SUA CONTA",array()).'</b><BR>
                                            <b style="margin:1%; font-family:sans-serif; font-size:0.8em;">'.$CI->T("PRECISAMOS QUE VOCÊ ATUALIZE SEUS DADOS BANCÁRIOS",array()).'</b>  <BR>           
                                            <a id="lnk_update_data_bank" href="#lnk_update">
                                                <button id="btn_update_data_bank" type="button" style="margin:1%; color:white;font-size:1em; " class="btn btn-success ladda-button"  data-style="expand-left" data-spinner-color="#ffffff">
                                                    '.$CI->T("ATUALIZAR AGORA",array()).'
                                                </button>
                                            </a>
                                        </div>';
                                    break;
                                case 6:
                                    echo '
                                        <div class="center" style="margin-left:20%; width:60%; padding: 2%;  border:1px solid red; border-radius:5px ">
                                            <b style="margin:1%; font-family:sans-serif; font-size:1em; color:red;">'.$CI->T("MANTENHA ATIVA SUA CONTA",array()).'</b><BR>
                                            <b style="margin:1%; font-family:sans-serif; font-size:0.8em;">'.$CI->T("PRECISAMOS QUE VOCÊ ATUALIZE SEUS DADOS BANCÁRIOS",array()).'</b>  <BR>           
                                            <b style="margin:1%; font-family:sans-serif; font-size:0.8em;">'.$CI->T("NÃO FOI POSSIVEL REALIZAR O PAGAMENTO NA DATA CORRESPONTE",array()).'</b><BR>
                                            <a id="lnk_update_data_bank" href="#lnk_update">
                                                <button id="btn_update_data_bank" type="button" style="margin:1%; color:white;font-size:1em; " class="btn btn-success ladda-button"  data-style="expand-left" data-spinner-color="#ffffff">
                                                    '.$CI->T("ATUALIZAR AGORA",array()).'
                                                </button>
                                            </a>
                                        </div>';
                                    break;
                                case 7:                                        
                                    echo '
                                        <div class="center" style="margin-left:20%; width:60%; padding: 2%;  border:1px solid red; border-radius:5px ">
                                            <b id="message_status1" style="margin:1%; font-family:sans-serif; font-size:1em; color:red;">'.$CI->T("ATUALIZAR AGORA",array()).'</b>
                                            <b id="message_status2" style="margin:1%; font-family:sans-serif; font-size:0.8em;"><BR>'.$CI->T("PRECISAMOS QUE VOCÊ SIGA MÁXIMO 6500 PERFIS NO INSTAGRAM PARA INICIAR A FERRAMENTA NO SEU PERFIL",array()).'</b><BR>
                                        </div>'; 
                                    break;
                                case 9:
                                    if(isset($verify_account_datas)&&is_array($verify_account_datas)){
                                        if($verify_account_datas['verify_account_url']!="")
                                            echo '
                                            <div class="center" style="margin-left:20%; width:60%; padding: 2%;  border:1px solid red; border-radius:5px ">
                                                <b style="margin:1%; font-family:sans-serif; font-size:1em; color:red;">'.$CI->T("ATIVE SUA CONTA",array()).'</b><BR>
                                                <b style="margin:1%; font-family:sans-serif; font-size:0.8em;">'.$CI->T("PRECISAMOS QUE VOCÊ VERIFIQUE SUA CONTA DIRETAMENTE NO INSTAGRAM COMO MEDIDA DE SEGURANÇA",array()).'</b>             
                                                <a id="lnk_verify_account" target="_blank" style="color:black;font-size:1em;"  href="'.$verify_account_datas['verify_account_url'].'">
                                                    <button id="btn_verify_account" type="button" style="margin:1%; color:white;font-size:1em; " class="btn btn-success ladda-button"  data-style="expand-left" data-spinner-color="#ffffff">
                                                        '.$CI->T("ACTIVAR AGORA",array()).'
                                                    </button>
                                                </a>
                                            </div>';
                                        else
                                            echo '
                                            <div class="center" style="margin-left:20%; width:60%; padding: 2%;  border:1px solid red; border-radius:5px ">
                                                <b style="margin:1%; font-family:sans-serif; font-size:1em; color:red;">'.$CI->T("ATIVE SUA CONTA",array()).'</b><BR>
                                                <b style="margin:1%; font-family:sans-serif; font-size:0.8em;">'.$CI->T("PRECISAMOS QUE VOCÊ VERIFIQUE SUA CONTA DIRETAMENTE NO INSTAGRAM COMO MEDIDA DE SEGURANÇA",array()).'</b>                                             
                                            </div>';
                                    }
                                    break;
                                case 10:
                                    echo '
                                        <div class="center" style="margin-left:20%; width:60%; padding: 2%;  border:1px solid red; border-radius:5px ">
                                            <b id="message_status2" style="margin:1%; font-family:sans-serif; font-size:0.8em;">'.$CI->T("SUA CONTA ESTA TEMPORÁRIAMENTE LIMITADA NO DUMBU DEVIDO A RESTRIÇÕES DE TEMPO COM O INSTRAGRAM",array()).'</b>  <BR>           
                                            <b id="message_status2" style="margin:1%; font-family:sans-serif; font-size:0.8em;">'.$CI->T("EM POUCO TEMPO SERÁ RESTABELECIDO O SERVIÇO PARA VOCÊ",array()).'</b><BR>           
                                        </div>'; 
                                    break;
                            }
                        ?>
                    </div>

                    <div id="reference_profile_status_container" class="row" style="visibility:hidden;display:none">        
                        <div id="reference_profile_status_message" class="center" style="margin-left:20%; width:60%; padding: 2%;  border:1px solid red; border-radius:5px ">
                            <div class="center">
                                <b style="margin:1%; font-family:sans-serif; font-size:1em; color:red;"><?php echo $CI->T("PERFIS DE REFERÊNCIA COM PROBLEMA. CONSIDERE TROCAR",array());?></b>
                                <hr><BR>
                            </div>

                            <div style="text-align:left">
                                <ul id="reference_profile_status_list">

                                </ul>
                            </div>
                            <div class="center">
                                <button id="btn_RP_status" type="button" style="margin:1%; color:white;font-size:1em; " class="btn btn-success ladda-button"  data-style="expand-left" data-spinner-color="#ffffff">
                                    <?php echo $CI->T("ACEITAR",array());?>
                                </button>
                            </div>            
                        </div>
                    </div>
                    <!---------------------------------------------------------------------------------------->

                    <!-- Single button -->
                    <div class="btn-group fleft100 m-tb20">
                        <button type="button" class="btn btn-drop fleft100 dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <b><?php echo $CI->T("AVISOS IMPORTANTES",array());?></b> <img src="<?php echo base_url().'assets/images/seta.png';?>" alt="" class="wauto fright">
                        </button>
                        <ul class="dropdown-menu drop-lista bk-cinza fleft100">
                            <li><?php echo $CI->T("O Instagram só permite que você siga 7.500 perfis no total. Se você segue entre 6.000 e 7.500, precisarémos desseguir perfis para iniciar a ferramenta;",array());?></li>
                            <li><?php echo $CI->T("Nossa ferramenta é integrada ao instagram, por isso, pode sofrer variações no desempenho a cada atualização feita pelo instagram;",array());?></li>
                            <li><?php echo $CI->T("Caso altere sua senha ou usuário, não se preocupe, basta você efetuar login em nosso site e pronto! Sua conta será atualizada automatcamente;",array());?></li>
                            <li><?php echo $CI->T("Nunca deixe sua conta privada, você conseguirá captar mais seguidores se eles puderem ver seu conteúdo e se identificarem com seu perfil;",array());?></li>
                            <li><?php echo $CI->T("Nunca escolha perfis privados ou com poucos seguidores.",array());?></li>
                        </ul>
                    </div>

                    <div class="text-center fleft100">
                        <img src="<?php echo base_url().'assets/images/perfis.png';?>" class="wauto m-t20" alt="">
                        <h4 class="fleft100 m-t20"><b><?php echo $CI->T("PERFIS DE PREFERÊNCIA",array());?></b></h4>
                        <p class="m-t10 fleft100"><?php echo $CI->T("O Dumbu seguirá os usuários que seguem os perfis de referência que você escolher, uma parte <br>desses usuários o seguirão de volta e após um determinado tempo deixaremos de <br>seguir esses usuários. Adicione seus perfis de referência aqui:",array());?></p>
                    </div>

                    <div class="col-md-2 col-sm-2 col-xs-12 text-center m-t30">
                        <img id="my_img" src="<?php echo $my_img_profile;?>" class="img50" alt="">
                        <b><p id="my_name" style="font-size:1.2em; font-family:sans-serif;"><?php echo $my_login_profile;?></p></b> 
                        <!--<span class="fleft100 m-t10">@pedropetti</span>-->
                        <span class="fleft100 cl-green">
                            <?php
                                if($status['status_id']==1 ||$status['status_id']==6 || $status['status_id']==10)
                                    echo '<b id="status_text" style="font-family:sans-serif">'.$CI->T($status["status_name"],array()).'</b>';
                                else                   
                                    echo '<b id="status_text" ">'.$CI->T($status["status_name"],array()).'</b>';                    
                            ?>
                        </span>
                    </div>

                    <div class="col-md-10 col-sm-10 col-xs-12 m-t30">
                        <div class="num">
                            <img src="<?php echo base_url().'assets/images/bol-g.png';?>" class="wauto" alt="">
                                <?php echo $CI->T('Número de perfis já seguidos ',array()).$total_amount_followers_today;?>
                            <span class="fright">
                                <?php echo $total_amount_reference_profile_today.' '.$CI->T('Perfis de referência utilizados até hoje',array());?>
                            </span></div>
                        <div class="fleft100 bk-cinza pf-painel">
                            <ul class="add-perfil text-center">
                                
                                <li>
                                    <div id="reference_profile0" class="container-reference-profile">                                                                    
                                        <img id="img_ref_prof0" class="img_profile" style="width:70px" src="<?php echo base_url().'assets/images/avatar.png';?>"> 
                                        <br>
                                        <a id="lnk_ref_prof0" target="_blank" href="#">
                                            <small id="name_ref_prof0" title="<?php echo $CI->T("Ver no Instagram",array());?>" style="color:black" class="fleft100 m-t10"></small>
                                        </a>
                                        <b id="cnt_follows_prof0" title='<?php echo $CI->T("Seguidos por mim para este perfil",array());?>' class="cl-green fleft100 red_number">520</b>
                                    </div>
                                </li>
                                
                                <li>
                                    <div id="reference_profile1" class="container-reference-profile">                                                                    
                                        <img id="img_ref_prof1" class="img_profile" style="width:70px" src="<?php echo base_url().'assets/images/avatar.png';?>"> 
                                        <br>
                                        <a id="lnk_ref_prof1" target="_blank" href="#">
                                            <small id="name_ref_prof1" title="<?php echo $CI->T("Ver no Instagram",array());?>" style="color:black" class="fleft100 m-t10"></small>
                                        </a>
                                        <b id="cnt_follows_prof1" title='<?php echo $CI->T("Seguidos por mim para este perfil",array());?>' class="cl-green fleft100 red_number">520</b>
                                    </div>
                                </li>
                                
                                <li>
                                    <div id="reference_profile2" class="container-reference-profile">                                                                    
                                        <img id="img_ref_prof2" class="img_profile" style="width:70px" src="<?php echo base_url().'assets/images/avatar.png';?>"> 
                                        <br>
                                        <a id="lnk_ref_prof2" target="_blank" href="#">
                                        </a>
                                        <b id="cnt_follows_prof2" title='<?php echo $CI->T("Seguidos por mim para este perfil",array());?>' class="cl-green fleft100 red_number">520</b>
                                    </div>
                                </li>
                                
                                <li>
                                    <div id="reference_profile3" class="container-reference-profile">                                                                    
                                        <img id="img_ref_prof3" class="img_profile" style="width:70px" src="<?php echo base_url().'assets/images/avatar.png';?>"> 
                                        <br>
                                        <a id="lnk_ref_prof3" target="_blank" href="#">
                                            <small id="name_ref_prof3" title="<?php echo $CI->T("Ver no Instagram",array());?>" style="color:black" class="fleft100 m-t10"></small>
                                        </a>
                                        <b id="cnt_follows_prof3" title='<?php echo $CI->T("Seguidos por mim para este perfil",array());?>' class="cl-green fleft100 red_number">520</b>
                                    </div>
                                </li>
                                
                                <li>
                                    <div id="reference_profile4" class="container-reference-profile">                                                                    
                                        <img id="img_ref_prof4" class="img_profile" style="width:70px" src="<?php echo base_url().'assets/images/avatar.png';?>"> 
                                        <br>
                                        <a id="lnk_ref_prof4" target="_blank" href="#">
                                            <small id="name_ref_prof4" title="<?php echo $CI->T("Ver no Instagram",array());?>" style="color:black" class="fleft100 m-t10"></small>
                                        </a>
                                        <b id="cnt_follows_prof4" title='<?php echo $CI->T("Seguidos por mim para este perfil",array());?>' class="cl-green fleft100 red_number">520</b>
                                    </div>
                                </li>
                                
                                <li>
                                    <div id="reference_profile5" class="container-reference-profile">                                                                    
                                        <img id="img_ref_prof5" class="img_profile" style="width:70px" src="<?php echo base_url().'assets/images/avatar.png';?>"> 
                                        <br>
                                        <a id="lnk_ref_prof5" target="_blank" href="#">
                                            <small id="name_ref_prof5" title="<?php echo $CI->T("Ver no Instagram",array());?>" style="color:black" class="fleft100 m-t10"></small>
                                        </a>
                                        <b id="cnt_follows_prof5" title='<?php echo $CI->T("Seguidos por mim para este perfil",array());?>' class="cl-green fleft100 red_number">520</b>
                                    </div>
                                </li>
                                
                                <li class="add"><img id="btn_add_new_profile" src="<?php echo base_url().'assets/images/+.png';?>" class="wauto" alt="" type="button" data-toggle="modal" data-target="#myModal"></li>
                            </ul>
                            
                        </div>
                        <div class="num fleft100"><b>Dica:</b><?php echo $CI->T("Lembre-se que para garantir um bom desempenho da ferramenta você deve adicionar perfis de referência que combine com o seu perfil. Para mais informação, consulte nossa ",array());?><a href="<?php echo base_url().'index.php/welcome/help'?>" style="color:green" target="_blank"><?php echo $CI->T("Ajuda!",array());?></a></div>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" style="top:30%" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div id="modal_container_add_reference_rpofile" class="modal-dialog modal-sm" role="document">                                                          
                              <div class="modal-content">
                                  <div class="modal-header">
                                      <button id="btn_modal_close" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                      <h4 class="modal-title" id="myModalLabel"><?php echo $CI->T("Perfil de referência",array());?></h4>
                                  </div>
                                  <div class="modal-body">
                                      <input id = "login_profile" type="text" class="form-control" placeholder="<?php echo $CI->T("Perfil",array());?>" onkeyup="javascript:this.value=this.value.toLowerCase();" style="text-transform:lowercase;"  required>
                                      <div id="reference_profile_message" class="form-group m-t10" style="text-align:left;visibility:hidden; font-family:sans-serif; font-size:0.9em"> </div>
                                  </div>
                                  <div class="modal-footer">
                                      <button id="btn_insert_profile" type="button" class="btn btn-primary text-center"><?php echo $CI->T("Adicionar",array());?></button>
                                  </div>
                              </div>
                          </div>                                                        
                    </div> 

                    
                    
                    
                    <div class="pf fleft100 text-center m-t45">
                        <img src="<?php echo base_url().'assets/images/perf.png';?>" class="wauto" alt="">
                        <h4 class="fleft100"><b><?php echo $CI->T("PERFOMANCE",array());?></b></h4>
                    </div>
                    
                    <!--
                    <div class="col-md-6 col-sm-6 col-xs-12 m-t20">
                            <div class="col-md-5 col-sm-5 col-xs-12 bk-cinza text-center bloco">
                                <h3 class="fleft100 m-t10"><b>INÍCIO <?php //echo date("j", $my_sigin_date).'/'.date("n", $my_sigin_date);?></b></h3>
                                <div class="col-md-6 col-sm-6 col-xs-12 border pd-r15"><h3 class="no-mg"><b><?php //echo $my_initial_followings;?></b></h3><small class="fleft100">Seguindo</small></div>
                                <div class="col-md-6 col-sm-6 col-xs-12 pd-l15"><h3 class="no-mg"><b><?php //echo $my_initial_followers;?></b></h3><small class="fleft100">Seguidores</small></div>
                            </div>

                            <div class="col-md-1 col-sm-1 col-xs-12"><br></div>

                            <div class="col-md-5 col-sm-5 col-xs-12 bk-cinza text-center bloco cl-blue">
                                <h3 class="fleft100 m-t10"><b>AGORA</b></h3>
                                <div class="col-md-6 col-sm-6 col-xs-12 border pd-r15"><h3 class="no-mg"><b><?php //echo $my_actual_followings;?></b></h3><small class="fleft100">Seguindo</small></div>
                                <div class="col-md-6 col-sm-6 col-xs-12 pd-l15"><h3 class="no-mg"><b><?php //echo $my_actual_followers;?></b></h3><small class="fleft100">Seguidores</small></div>
                            </div>


                            <div class="col-md-4 col-sm-4 col-xs-12 cl-blue m-t30 no-pd center-mobile">
                                <b class="cl-black">Ganho hoje</b>
                                <h1 class="no-mg fleft100 fsize60"><b>256</b></h1>
                            </div>

                            <div class="col-md-8 col-sm-8 col-xs-12 cl-green m-t30 no-pd center-mobile">
                                <b class="cl-black fleft100 m-b10">Conversão</b>
                                <div class="cv fleft">56%</div>
                                <img src="<?php  //echo base_url().'assets/images/s-top.png';?>" class="wauto fleft st" alt="">
                            </div>

                            <div class="fleft100 m-t45">
                                <div class="col-md-4 col-sm-4 col-xs-12 no-pd center-mobile m-b10">
                                    <b class="cl-black">Ganho semanal</b>
                                    <h1 class="no-mg fleft100"><b>892</b></h1>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12 no-pd center-mobile m-b10">
                                    <b class="cl-black">Ganho Mensal</b>
                                    <h1 class="no-mg fleft100"><b>37822</b></h1>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12 no-pd center-mobile m-b10">
                                    <b class="cl-black">Ganho desde o início</b>
                                    <h1 class="no-mg fleft100"><b>10.522</b></h1>
                                </div>
                            </div>

                            <div class="col-md-12 col-sm-12 col-xs-12 no-pd m-t30 center-mobile m-b10">
                                <b class="cl-black">Seguidas até hoje</b>
                                <h1 class="no-mg fleft100"><b>37005</b></h1>
                            </div>  
                    </div>

                    <div class="col-md-6 col-sm-6 col-xs-12 m-t20">
                            <div class="col-md-6 col-sm-6 col-xs-12 pd-r5">
                                <div class='input-group date'>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                    <input type='text' class="form-control" id='datetimepicker1' placeholder="Selecione o período" />		                    
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12 pd-l5">
                                <div class='input-group date'>
                                    <input type='text' class="form-control" id='datetimepicker2' placeholder="Selecione o período" />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>

                            <b class="cl-black m-t20 fleft100">Gráfico de desempenho</b>
                            <div class="grafico fleft100 m-tb20">
                                <img src="<?php //echo base_url().'assets/images/grafico.jpg';?>" alt="">
                            </div>
                            <span class="fleft100"><b style="color:#2f61c5;">▬Seguidores ganhos</b> <b style="color:#ea4018;">▬Seguidores iniciais</b></span>

                            <b class="cl-black fleft100 m-t30 center-mobile">Melhores Perfis de referência:</b>
                            <div class="fleft100 pf-melhor pf-painel m-t30">
                                <ul class="add-perfil text-center">
                                    <li><a href=""><span>1º</span><img src="<?php //echo base_url().'assets/images/avatar.png';?>" class="wauto" alt=""></a><small class="fleft100 m-t10">@perfilderef <b class="cl-green fleft100 m-t20">25% <br><small>seguiu você</small></b></small></li>
                                    <li><a href=""><span>2º</span><img src="<?php //echo base_url().'assets/images/avatar.png';?>" class="wauto" alt=""></a><small class="fleft100 m-t10">@perfilderef <b class="cl-green fleft100 m-t20">25% <br><small>seguiu você</small></b></small></li>
                                    <li><a href=""><span>3º</span><img src="<?php //echo base_url().'assets/images/avatar.png';?>" class="wauto" alt=""></a><small class="fleft100 m-t10">@perfilderef <b class="cl-green fleft100 m-t20">25% <br><small>seguiu você</small></b></small></li>							
                                </ul>
                            </div>
                    </div>
                    
                    <div class="fleft100">
                            <div class="col-md-6 col-sm-6 col-xs-12 bk text-center pd-r15 m-t30">
                                <div class="fleft100 bk-cinza">
                                    <img src="<?php //echo base_url().'assets/images/direct.png';?>" class="wauto" alt="">
                                    <h2 class="no-mg"><b>DIRECT</b></h2>
                                    <div class="breve">EM BREVE</div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12 bk text-center pd-l15 m-t30">
                                <div class="fleft100 bk-cinza">
                                    <img src="<?php //echo base_url().'assets/images/viu.png';?>" class="wauto" alt="">
                                    <h2 class="no-mg"><b>QUEM VIU SEU PERFIL</b></h2>
                                    <div class="breve">EM BREVE</div>
                                </div>
                            </div>
                    </div>

                    <div class="col-md-12 col-sm-12 col-xs-12 bk text-center no-pd m-t30">
                            <div class="fleft100 bk-cinza local">
                                <img src="<?php //echo base_url().'assets/images/local.png';?>" class="wauto" alt="">
                                <h2 class="no-mg"><b>GEOLOCALIZAÇÃO</b></h2>
                                <div class="breve"><a href="" data-toggle="modal" data-target=".bs-simular">EM BREVE</a></div>

                                <!-- Modal --><!--
                                <div class="modal fade bs-simular bs-example-ligar" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                                    <div class="modal-dialog modal-lg ligar" role="document">
                                        <div class="modal-content text-center pd-20">
                                            <h4 class="m-tb30 cl-green"><b>MUITAS NOVIDADES!</b></h4>
                                            <p class="">EM BREVE A DUMBU DISBONIBILIZARÁ NOVAS FUNÇÕES, CLIQUE EM OK SE QUISER <br>PARTICIPAR DA VERSÃO DE TESTES E SER UM DOS PRIMEROS A TER ACESSO.</p>
                                            <div class="text-center m-b20"><button class="btn-primary w40 btn-green m-t20">QUERO PARTICIPAR</button></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    
                    
                    <div class="fleft100">
                        <div class="col-md-6 col-sm-6 col-xs-12 bk text-center pd-r15 m-t30">
                            <div class="fleft100 bk-cinza">
                                <img src="<?php //echo base_url().'assets/images/direct.png';?>" class="wauto" alt="">
                                <h2 class="no-mg"><b>DIRECT</b></h2>
                                <div class="breve">EM BREVE</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 bk text-center pd-l15 m-t30">
                            <div class="fleft100 bk-cinza">
                                <img src="<?php //echo base_url().'assets/images/viu.png';?>" class="wauto" alt="">
                                <h2 class="no-mg"><b>QUEM VIU SEU PERFIL</b></h2>
                                <div class="breve">EM BREVE</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12 col-xs-12 bk text-center no-pd m-t30">
                        <div class="fleft100 bk-cinza local">
                            <img src="<?php //echo base_url().'assets/images/local.png';?>" class="wauto" alt="">
                            <h2 class="no-mg"><b>GEOLOCALIZAÇÃO</b></h2>
                            <div class="breve"><a href="" data-toggle="modal" data-target=".bs-simular">EM BREVE</a></div>

                            <!-- Modal --><!--
                            <div class="modal fade bs-simular bs-example-ligar" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                                <div class="modal-dialog modal-lg ligar" role="document">
                                    <div class="modal-content text-center pd-20">
                                        <h4 class="m-tb30 cl-green"><b>MUITAS NOVIDADES!</b></h4>
                                        <p class="">EM BREVE A DUMBU DISBONIBILIZARÁ NOVAS FUNÇÕES, CLIQUE EM OK SE QUISER <br>PARTICIPAR DA VERSÃO DE TESTES E SER UM DOS PRIMEROS A TER ACESSO.</p>
                                        <div class="text-center m-b20"><button class="btn-primary w40 btn-green m-t20">QUERO PARTICIPAR</button></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    -->
                    
                    
                    
                    
                    
                    <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-5 col-sm-5 col-xs-12 bk-cinza text-center bloco">
                                <h3 class="fleft100 m-t10"><b><?php echo $CI->T("INÍCIO ",array());?><?php echo date("j", $my_sigin_date).'/'.date("n", $my_sigin_date);?></b></h3>
                                <div class="col-md-6 col-sm-6 col-xs-12 border pd-r15"><h3 class="no-mg"><b><?php echo $my_initial_followings;?></b></h3><small class="fleft100"><?php echo $CI->T("Seguindo",array());?></small></div>
                                <div class="col-md-6 col-sm-6 col-xs-12 pd-l15"><h3 class="no-mg"><b><?php echo $my_initial_followers;?></b></h3><small class="fleft100"><?php echo $CI->T("Seguidores",array());?></small></div>
                            </div>

                            <div class="col-md-1 col-sm-1 col-xs-12"><br></div>

                            <div class="col-md-5 col-sm-5 col-xs-12 bk-cinza text-center bloco cl-blue">
                                <h3 class="fleft100 m-t10"><b><?php echo $CI->T("AGORA",array());?></b></h3>
                                <div class="col-md-6 col-sm-6 col-xs-12 border pd-r15"><h3 class="no-mg"><b><?php echo $my_actual_followings;?></b></h3><small class="fleft100"><?php echo $CI->T("Seguindo",array());?></small></div>
                                <div class="col-md-6 col-sm-6 col-xs-12 pd-l15"><h3 class="no-mg"><b><?php echo $my_actual_followers;?></b></h3><small class="fleft100"><?php echo $CI->T("Seguidores",array());?></small></div>
                            </div>

                            <!--
                            <div class="col-md-4 col-sm-4 col-xs-12 cl-blue m-t30 no-pd center-mobile">
                                <b class="cl-black">Ganho hoje</b>
                                <h1 class="no-mg fleft100 fsize60"><b>256</b></h1>
                            </div>

                            <div class="col-md-8 col-sm-8 col-xs-12 cl-green m-t30 no-pd center-mobile">
                                <b class="cl-black fleft100 m-b10">Conversão</b>
                                <div class="cv fleft">56%</div>
                                <img src="<?php  //echo base_url().'assets/images/s-top.png';?>" class="wauto fleft st" alt="">
                            </div>

                            <div class="fleft100 m-t45">
                                <div class="col-md-4 col-sm-4 col-xs-12 no-pd center-mobile m-b10">
                                    <b class="cl-black">Ganho semanal</b>
                                    <h1 class="no-mg fleft100"><b>892</b></h1>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12 no-pd center-mobile m-b10">
                                    <b class="cl-black">Ganho Mensal</b>
                                    <h1 class="no-mg fleft100"><b>37822</b></h1>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12 no-pd center-mobile m-b10">
                                    <b class="cl-black">Ganho desde o início</b>
                                    <h1 class="no-mg fleft100"><b>10.522</b></h1>
                                </div>
                            </div>

                            <div class="col-md-12 col-sm-12 col-xs-12 no-pd m-t30 center-mobile m-b10">
                                <b class="cl-black">Seguidas até hoje</b>
                                <h1 class="no-mg fleft100"><b>37005</b></h1>
                            </div> 
                            -->
                    </div>
                    
                    <!--
                    <div class="col-md-6 col-sm-6 col-xs-12 m-t20">
                            <div class="col-md-6 col-sm-6 col-xs-12 pd-r5">
                                <div class='input-group date'>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                    <input type='text' class="form-control" id='datetimepicker1' placeholder="Selecione o período" />		                    
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12 pd-l5">
                                <div class='input-group date'>
                                    <input type='text' class="form-control" id='datetimepicker2' placeholder="Selecione o período" />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>

                            <b class="cl-black m-t20 fleft100">Gráfico de desempenho</b>
                            <div class="grafico fleft100 m-tb20">
                                <img src="<?php //echo base_url().'assets/images/grafico.jpg';?>" alt="">
                            </div>
                            <span class="fleft100"><b style="color:#2f61c5;">▬Seguidores ganhos</b> <b style="color:#ea4018;">▬Seguidores iniciais</b></span>

                            <b class="cl-black fleft100 m-t30 center-mobile">Melhores Perfis de referência:</b>
                            <div class="fleft100 pf-melhor pf-painel m-t30">
                                <ul class="add-perfil text-center">
                                    <li><a href=""><span>1º</span><img src="<?php //echo base_url().'assets/images/avatar.png';?>" class="wauto" alt=""></a><small class="fleft100 m-t10">@perfilderef <b class="cl-green fleft100 m-t20">25% <br><small>seguiu você</small></b></small></li>
                                    <li><a href=""><span>2º</span><img src="<?php //echo base_url().'assets/images/avatar.png';?>" class="wauto" alt=""></a><small class="fleft100 m-t10">@perfilderef <b class="cl-green fleft100 m-t20">25% <br><small>seguiu você</small></b></small></li>
                                    <li><a href=""><span>3º</span><img src="<?php //echo base_url().'assets/images/avatar.png';?>" class="wauto" alt=""></a><small class="fleft100 m-t10">@perfilderef <b class="cl-green fleft100 m-t20">25% <br><small>seguiu você</small></b></small></li>							
                                </ul>
                            </div>
                    </div>
                    -->
                    
                    
                    
                    
                    <div class="fleft100">
                        <div class="col-md-6 col-sm-6 col-xs-12 bk text-center pd-r15 m-t30">
                            <div class="fleft100 bk-cinza">
                                <img src="<?php echo base_url().'assets/images/direct.png';?>" class="wauto" alt="">
                                <h2 class="no-mg"><b><?php echo $CI->T("DIRECT",array());?></b></h2>
                                <div class="breve"><?php echo $CI->T("EM BREVE",array());?></div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 bk text-center pd-l15 m-t30">
                            <div class="fleft100 bk-cinza">
                                <img src="<?php echo base_url().'assets/images/viu.png';?>" class="wauto" alt="">
                                <h2 class="no-mg"><b><?php echo $CI->T("QUEM VIU SEU PERFIL",array());?></b></h2>
                                <div class="breve"><?php echo $CI->T("EM BREVE",array());?></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12 col-xs-12 bk text-center no-pd m-t30">
                        <div class="fleft100 bk-cinza local">
                            <img src="<?php echo base_url().'assets/images/local.png';?>" class="wauto" alt="">
                            <h2 class="no-mg"><b><?php echo $CI->T("GEOLOCALIZAÇÃO",array());?></b></h2>
                            <div class="breve"><a href="" data-toggle="modal" data-target=".bs-simular"><?php echo $CI->T("EM BREVE",array());?></a></div>

                            <!-- Modal -->
                            <div class="modal fade bs-simular bs-example-ligar" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                                <div class="modal-dialog modal-lg ligar" role="document">
                                    <div class="modal-content text-center pd-20">
                                        <h4 class="m-tb30 cl-green"><b><?php echo $CI->T("MUITAS NOVIDADES",array());?>!</b></h4>
                                        <p class=""><?php echo $CI->T("EM BREVE A DUMBU DISBONIBILIZARÁ NOVAS FUNÇÕES, CLIQUE EM OK SE QUISER <br>PARTICIPAR DA VERSÃO DE TESTES E SER UM DOS PRIMEROS A TER ACESSO.",array());?></p>
                                        <div class="text-center m-b20"><button class="btn-primary w40 btn-green m-t20"><?php echo $CI->T("QUERO PARTICIPAR",array());?></button></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    

                    <div class="col-md-1 col-sm-1 col-xs-12 no-pd"><br></div>
                    <div class="col-md-5 col-sm-5 col-xs-12 bk text-center pd-r15 m-t45">
                        <div class="text-center fleft100 m-t20">
                            <img src="<?php echo base_url().'assets/images/pay.png';?>" class="wauto" alt="">
                            <h4 class="fleft100 m-t20"><b><?php echo $CI->T("DADOS DE PAGAMENTO",array());?></b></h4>
                        </div>
                        <div class="pay fleft100 input-form">
                                <fieldset>
                                    <input id="client_credit_card_name" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control" placeholder="<?php echo $CI->T("Meu nome no cartão",array());?>" required style="text-transform:uppercase;">
                                </fieldset>
                            
                                <fieldset>
                                    <input type="text" placeholder="<?php echo $CI->T("E-mail",array());?>"  id="client_email" type="email" class="form-control" required>
                                </fieldset>
                            
                                <div class="col-md-9 col-sm-9 col-xs-12 pd-r5">
                                    <fieldset>
                                        <input id="client_credit_card_number" type="text" class="form-control" placeholder="<?php echo $CI->T("Número no cartão",array());?>" data-mask="0000 0000 0000 0000" maxlength="20" required>
                                    </fieldset>
                                </div>
                            
                                <div class="col-md-3 col-sm-3 col-xs-12 pd-l5">
                                    <fieldset>
                                        <input id="client_credit_card_cvv" type="text" class="form-control" placeholder="<?php echo $CI->T("CVV/CVC",array());?>" maxlength="5" required>
                                    </fieldset>
                                </div>
                            
                                <div class="col-md-4 col-sm-4 col-xs-12 no-pd m-t10">
                                    <span class="val"><?php echo $CI->T("Validade",array());?></span>
                                </div>
                            
                                <div class="col-md-4 col-sm-4 col-xs-12 pd-r15 m-t10">
                                    <fieldset>
                                        <div class="select"> 
                                            <select name="local" id="client_credit_card_validate_month" class="btn-primeiro sel"> 
                                                <option>01</option><option>02</option><option>03</option><option>04</option><option>05</option><option>06</option><option>01</option><option>02</option><option>03</option><option>04</option><option>05</option><option>06</option><option>07</option><option>08</option><option>09</option><option>10</option><option>11</option><option>12</option>
                                            </select>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12 no-pd m-t10">
                                    <fieldset>
                                        <div class="select">
                                            <select name="local" id="client_credit_card_validate_year" class="btn-primeiro sel">
                                                <option>2017</option><option>2018</option><option>2019</option><option>2020</option><option>2021</option><option>2022</option><option>2023</option><option>2024</option><option>2025</option><option>2026</option><option>2027</option><option>2028</option><option>2029</option><option>2030</option><option>2031</option><option>2032</option><option>2033</option><option>2034</option><option>2035</option><option>2036</option><option>2037</option><option>2038</option><option>2039</option>
                                            </select>
                                        </div>
                                    </fieldset>
                                </div>
                                
                                <div class="col-md-4 col-sm-4 col-xs-12 pd-r15  m-t10">
                                    <span class="val"><?php echo $CI->T("Mudar plano",array());?>:</span>
                                </div>

                                <div class="col-md-8 col-sm-8 col-xs-12 pd-r15  m-t10">
                                    <fieldset>
                                        <div class="select"> 
                                            <select name="local" id="client_update_plane" class="btn-primeiro sel"> 
                                                <?php
                                                    for($i=0;$i<count($all_planes);$i++){
                                                        if( $i+2==$plane_id )
                                                            echo '<option id="cbx_plane'.($i+2).'" value="'.($i+2).'" title="('.$CI->T("Plano atual",array()).'" selected="true"><b>'.$currency.' '.(($all_planes[$i]['normal_val'])/100).'0 ('.$CI->T("Plano atual",array()).')</b></option>';
                                                        else
                                                            echo '<option id="cbx_plane'.($i+2).'" value="'.($i+2).'">'.$currency.' '.(($all_planes[$i]['normal_val'])/100).'0</option>';
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="text-center">
                                    <button id = "btn_send_update_datas" type="button" style="border-radius:20px" class="btn-primary m-t20 ladda-button" data-style="expand-left" data-spinner-color="#ffffff">
                                        <span class="ladda-label"><div style="color:white; font-weight:bold">CONFERIR</div></span>
                                    </button>
                                </div>
                        </div>
                    </div>
                    
                    
                    <div class="col-md-5 col-sm-5 col-xs-12 bk text-center pd-l15 m-t45">
                        <div class="text-center fleft100 m-t20">
                            <img src="<?php echo base_url().'assets/images/mail.png';?>" class="wauto" alt="">
                            <h4 class="fleft100 m-t20"><b>FALE CONOSCO</b></h4>
                        </div>
                        <div class="pay fleft100 input-form" id="talkme_frm">
                            <fieldset>
                                <input id="visitor_name" type="text" placeholder="Nome">
                            </fieldset>
                            <fieldset>
                                <input id="visitor_email" type="text" placeholder="E-mail">
                            </fieldset>
                            <fieldset>
                                <input id="visitor_company" type="text" placeholder="Empresa">
                            </fieldset>
                            <fieldset>
                                <input id="visitor_phone" type="text" placeholder="Telefone">
                            </fieldset>
                            <fieldset>
                                <textarea name="" id="visitor_message" cols="30" rows="5" placeholder="Mensagem"></textarea>
                            </fieldset>
                            <div class="text-center">
                                <button id="btn_send_message" type="button" style="border-radius:20px" class="btn-primary m-t20 ladda-button" data-style="expand-left" data-spinner-color="#ffffff">
                                    <span class="ladda-label"><div style="color:white; font-weight:bold">ENVIAR</div></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        
            <div class="m-t10">
                <div>
                    <p class=text-center>CANCELAMENTO DA ASSINATURA</p> 
                </div>

                <div class="text-center" >
                    <div class="row" style="margin-top: 2%; margin-bottom: 2%">
                        <button id="cancel_usser_account" class="btn btn-default ladda-button"  type="button" data-style="expand-left" data-spinner-color="#ffffff">
                            <span class="ladda-label">Cancelar conta</span>
                        </button>
                    </div>
                </div>
             </div>

            <div class="h150 fleft100"></div>
            <footer class="text-center fleft100 m-t30 m-b10"><div class="container"><img src="<?php echo base_url().'assets/images/logo-footer.png';?>" class="wauto" alt=""> <span class="fleft100 text-center">DUMBU - 2016 - TODOS OS DIREITOS RESERVADOS</span></div></footer>

            <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
            <!-- Include all compiled plugins (below), or include individual files as needed -->
            <script src="<?php echo base_url().'assets/bootstrap/js/bootstrap.min.js';?>"></script>
            <script src="<?php echo base_url().'assets/js/jquery.dlmenu.js';?>"></script>
            <script>
                $(function() {
                    $( '#dl-menu' ).dlmenu();
                });
            </script>
            <script src="<?php echo base_url().'assets/js/datepiker.js';?>"></script>
            <script type="text/javascript">
                // When the document is ready
                $(document).ready(function () {                
                    $('#datetimepicker1').datepicker({
                        format: "dd/mm/yyyy"
                    });            
                });
            </script>
            <script type="text/javascript">
                // When the document is ready
                $(document).ready(function () {
                    $('#datetimepicker2').datepicker({
                        format: "dd/mm/yyyy"
                    }); 
                });
            </script>
    </body>
</html>