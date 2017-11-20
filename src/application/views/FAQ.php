<!DOCTYPE html>
<html lang="en">
    <head>
        <?php  $CI =& get_instance();?>        
        <meta charset="utf-8">
        <title>FAQ</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="title" content="<?php echo $CI->T("Ganhar seguidores no Instagram | Ganhar ou Comprar Seguidores Reais e Ativos no Instagram", array(),$language); ?>">
        <meta name="description" content="<?php echo $CI->T("Ganhe seguidores no Instagram. www.dumbu.pro te permite ganhar seguidores no Instagram 100% reais e qualificados. Ganhe mais seguidores.", array(),$language);?>">
        <meta name="keywords" content="<?php echo $CI->T("ganhar, seguidores, Instagram, seguidores segmentados, curtidas, followers, geolocalizção, direct, vendas", array(),$language);?>">
        <meta name="revisit-after" content="7 days">
        <meta name="robots" content="index,follow">
        <meta name="distribution" content="global"> 
        <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/bootstrap/css/bootstrapold.min.css';?>" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/fonts/font-awesome.min.css';?>" />
        <link rel="shortcut icon" href="<?php echo base_url().'assets/images/icon.png'?>"> 
        <script type="text/javascript" src="<?php echo base_url().'assets/js/jquery1102.min.js';?>"></script>
        <script type="text/javascript" src="<?php echo base_url().'assets/bootstrap/js/bootstrapold.min.js';?>"></script>
        <script type="text/javascript" src="<?php echo base_url() . 'assets/js/talkme_painel.js'; ?>"></script>
        <script type="text/javascript">var language = '<?php echo $language;?>'; </script>
     <style type="text/css">
        .text-center{
            padding-top:3px;
        }
        
        #cabeçalho{
            padding-top:25px;
            font-size: 1.8em;
            font-family: inherit;
        }
        
        body{
            background: white;        
        }
        
    </style> 
    </head>
<body>
    <div class="container">
        <br />
        <div class="col-md-12">
            <div class="row">
                    <div class="container">
                        <div class="col-md-12">
                            <div class="text-center"><img src="<?php echo base_url() . 'assets/images/dumbu.png'; ?>" class="img-responsive center-block" width="100" height="10"></div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
    
    
    <div class="container">
        <b/>
        <b/>
        <b/>
        <h2 id= "cabeçalho" class="text-center"><?php echo $CI->T("Confira abaixo as perguntas mais frequentes", array(),$language);?></h2>
        <?php
            if ($language === EN){
                $Pergunta= Pregunta_EN;
                $Resposta= Respuesta_EN;
            }                        
            elseif ($language === ES){
                $Pergunta= Pregunta_ES;
                $Resposta= Respuesta_ES;
            }
            else{
                $Pergunta= Pregunta_PT;
                $Resposta= Respuesta_PT;
            }
        ?>
        
        <div class="panel-group" id="accordion">
            <div class="panel panel-default">
                <?php 
                    $num_rows=count($info);                   
                    for($i=0;$i<$num_rows;$i++){
                    echo '<div class="panel-heading">
                        <h4 class="panel-title">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse'.$i.'">'.$info[$i][$Pergunta].'</a>
                        </h4>
                        </div>';
                    echo '<div id="collapse'.$i.'" class="panel-collapse collapse off">
                          <div class="panel-body">'.$info[$i][$Resposta].'</div>
                          </div>';
                        
                    }
                    ?>            
            </div>
        </div>
    </div>
    
        <section id="perfil" class="fleft100">
            <div class="container">	
                <div style="z-index:1;" class="pf fleft100 text-center m-t30">
                    <div class="col-md-6 col-sm-6 col-xs-12 bk text-center pd-l15 m-t45">
                    <div class="text-center fleft100 m-t20"><A name="lnk_update"></A>
                        <img src="<?php echo base_url() . 'assets/images/mail.png'; ?>" class="wauto" alt="">
                        <h4 class="fleft100 m-t20"><b><?php echo $CI->T("FALE CONOSCO", array(), $language); ?></b></h4>
                        <?php
                            if($language=='EN'){?>

                                <div class="col-md-1 col-sm-1 col-xs-12"></div>
                                <div class="col-md-8 col-sm-8 col-xs-12 text-right">      
                                    <spam style="color:black; font-size:0.8em">
                                        WRITE TO US! OUR SERVICE IS SUPPORTED <BR> IN MORE THAN ONE LANGUAGE:
                                    </spam>

                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-12 m-t10 text-left">
                                    <img src="assets/images/flag_EN.png" title="English" class="wauto" alt="">
                                    <img src="assets/images/flag_BR.png" title="Português" class="wauto" alt="">
                                    <img src="assets/images/flag_ES.png" title="Español" class="wauto" alt="">
                                </div>
                        <?php    }
                        ?>          
                    </div>
                    <div class="pay fleft100 input-form" id="talkme_frm">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <fieldset>
                                <input id="visitor_name" type="text" placeholder="<?php echo $CI->T("Nome", array(), $language); ?>">
                            </fieldset>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <fieldset>
                                <input id="visitor_email" type="text" placeholder="<?php echo $CI->T("E-mail", array(), $language); ?>">
                            </fieldset>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <fieldset>
                                <input id="visitor_company" type="text" placeholder="<?php echo $CI->T("Empresa", array(), $language); ?>">
                            </fieldset>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <fieldset>
                                <input id="visitor_phone" type="text" placeholder="<?php echo $CI->T("Telefone", array(), $language); ?>">
                            </fieldset>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <fieldset>
                                <textarea name="" id="visitor_message" cols="30" rows="5" placeholder="<?php echo $CI->T("Mensagem", array(), $language); ?>"></textarea>
                            </fieldset>
                        </div>
                        <div class="text-center">
                            <button id="btn_send_message" type="button" style="border-radius:20px" class="btn-primary m-t20 ladda-button" data-style="expand-left" data-spinner-color="#ffffff">
                                <span class="ladda-label"><div style="color:white; font-weight:bold"><?php echo $CI->T("ENVIAR", array(), $language); ?></div></span>
                            </button>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </section>
    
</body>
</html>    
