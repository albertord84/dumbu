<!DOCTYPE html>
<html lang="pt_BR">        
	<head>
                <?php  $CI =& get_instance();?>
                <meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
                <meta name="title" content="<?php echo $CI->T("Ganhar seguidores no Instagram | Ganhar ou Comprar Seguidores Reais e Ativos no Instagram", array()); ?>">
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
                
                <script type="text/javascript" src="<?php echo base_url().'assets/js/'.$language.'/internalization.js';?>"></script>
                <script type="text/javascript">var language = '<?php echo $language;?>';</script> 
                <script type="text/javascript">var base_url = '<?php echo base_url();?>';</script> 
                <script type="text/javascript">var user_id = '<?php echo $user_id;?>';</script>                 
                <script type="text/javascript">var profiles = '<?php echo $profiles;?>';</script>                 
                <script type="text/javascript" src="<?php echo base_url().'assets/js/purchase.js';?>"></script>
                
                <?php //para SEO 
                    if($language=="EN"){
                        echo '<link rel="canonical" href="https://www.dumbu.one" />';
                    }                              
                ?>
                
                <?php include_once("pixel_facebook.php")?>   
                
                <!-- Abandono de carrinho de Revanth --> 
                    <script type="text/javascript" src="https://ga.getresponse.com/script/ga.js?grid=sBDcEXURffXoIBw%3D%3D" async></script> 
                <!-- End Getresponse Analytics -->
	</head>
	<body>
		<?php include_once("analyticstracking.php") ?>
                <?php include_once("adwords_conversion.php")?>
                <?php include_once("retargeting.php")?>
                <?php include_once("remarketing.php")?>
                <?php echo '<img src="https://secure.afilio.com.br/sale.php?pid=2289&order_id=#'.$Afilio_UNIQUE_ID.'#&order_price=#'.$Afilio_order_price.'#" border="0" width="1" height="1" />';?>
                
                <!-- Abandono de carrinho de Revanth --> 
                    <?php                         
                        echo " <script type='text/javascript'>gaSetUserId('".$client_email."');</script>";                                             
                    ?> 
                   
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
						<li><a href="<?php echo base_url().'assets/images/user.png';?>"><img src="<?php echo base_url().'assets/images/user.png';?>" class="wauto us" alt=""> SAIR</a></li>
					</ul>
				</nav>
			</div>
		</header>

		<section id="ar" class="fleft100">
			<div class="container">
				<div class="col-md-3 col-sm-3 col-xs-12"><br></div>
				<div class="col-md-6 col-sm-6 col-xs-12 no-pd">	
					<div class="text-center fleft100 m-t45">
                                            <img src="<?php echo base_url().'assets/images/sim.png';?>" class="wauto" alt="">
						<h2 class="cl-green"><b><?php echo $CI->T("Assinatura aprovada ",array());?><br><?php echo $CI->T("com sucesso",array());?>!</b></h2>
					</div>				
					<div class="fleft100 text-center pd-20 bk-cinza m-t30">
						<p>
							<b><?php echo $CI->T("Sua compra foi autorizada com sucesso",array());?>!</b>
							<br><br>
							<?php echo $CI->T("Agora você precisa  escolher seus perfis de referência.",array());?> 
							<?php echo $CI->T("Eles serão usados para captar os seguidores que deseja.  Perfis de referênci são todos os perfis que tem algo a ver com a sua conta, como um concorrente ou perfil similar, por exemplo.",array());?> 
						</p>
						<span class="fleft100 m-tb30"><?php echo $CI->T("PASSO 4",array());?></span>
						<span class="fleft100 m-b10"><b><?php echo $CI->T("Adicione 3 perfis de referência abaixo",array());?>:</b> <small class="fleft100 cl-red m-b10">*<?php echo $CI->T("Obrigatório",array());?></small></span>
						
                                                                                                   
                                                    <ul class="add-perfil">
                                                            <li>
                                                                <div id="reference_profile0" class="container-reference-profile">                                                                    
                                                                    <img id="img_ref_prof0" class="img_profile" style="width:70px" src="<?php echo base_url().'assets/images/avatar.png';?>"> 
                                                                    <br>
                                                                    <a id="lnk_ref_prof0" target="_blank" href="#">
                                                                        <small id="name_ref_prof0" title="<?php echo $CI->T("Ver no Instagram",array());?>" style="color:black" class="fleft100"></small>
                                                                    </a>
                                                                </div>                                                                
                                                            </li>
                                                            
                                                            <li>
                                                                <div id="reference_profile1" class="container-reference-profile">                                                                    
                                                                    <img id="img_ref_prof1" class="img_profile" style="width:70px" src="<?php echo base_url().'assets/images/avatar.png';?>"> 
                                                                    <br>
                                                                    <a id="lnk_ref_prof1" target="_blank" href="#">
                                                                        <small id="name_ref_prof1" title="<?php echo $CI->T("Ver no Instagram",array());?>" style="color:black" class="fleft100"></small>
                                                                    </a>
                                                                </div>                                                                
                                                            </li>
                                                            
                                                            <li>
                                                                <div id="reference_profile2" class="container-reference-profile">                                                                    
                                                                    <img id="img_ref_prof2" class="img_profile" style="width:70px" src="<?php echo base_url().'assets/images/avatar.png';?>">
                                                                    <br>
                                                                    <a id="lnk_ref_prof2" target="_blank" href="#">
                                                                        <small id="name_ref_prof2" title="<?php echo $CI->T("Ver no Instagram",array());?>" style="color:black" class="fleft100"></small>
                                                                    </a>
                                                                </div>                                                                
                                                            </li>
                                                            <li class="add"><img id="btn_add_new_profile" src="<?php echo base_url().'assets/images/+.png';?>" class="wauto" alt="" type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal"><span></span></li>
                                                    </ul>
                                               
                                                    <!-- Modal -->                                                    
                                                    <div class="modal fade" style="top:30%" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                        <div id="modal_container_add_reference_rpofile" class="modal-dialog modal-sm" role="document">                                                          
                                                              <div class="modal-content">
                                                                  <div class="modal-header">
                                                                      <button id="btn_modal_close" type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                          <img src="<?php echo base_url() . 'assets/images/FECHAR.png'; ?>"> <!--<span aria-hidden="true">&times;</span>-->
                                                                      </button>
                                                                      <h4 class="modal-title" id="myModalLabel"><?php echo $CI->T("Perfil de referência",array());?></h4>
                                                                  </div>
                                                                  <div class="modal-body">
                                                                      <input id = "login_profile" type="text" class="form-control" placeholder="Perfil" onkeyup="javascript:this.value=this.value.toLowerCase();" style="text-transform:lowercase;"  required>
                                                                      <div id="reference_profile_message" class="form-group m-t10" style="text-align:left;visibility:hidden; font-family:sans-serif; font-size:0.9em"> </div>
                                                                  </div>
                                                                  <div class="modal-footer">
                                                                      <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
                                                                      <button id="btn_insert_profile" type="button" class="btn btn-primary text-center ladda-button" data-style="expand-left" data-spinner-color="#ffffff">
                                                                          <span class="ladda-label"><div style="color:white; font-weight:bold"><?php echo $CI->T("Adicionar",array());?></div></span>
                                                                      </button>
                                                                  </div>
                                                              </div>
                                                          </div>                                                        
                                                    </div>                                                    

                                                    <div class="text-center">
                                                        <button id="continuar_purchase" class="btn-primary btn-green m-t20 ladda-button" data-style="expand-left" data-spinner-color="#ffffff">
                                                            <span class="ladda-label"><div style="color:white; font-weight:bold"><?php echo $CI->T("CONTINUAR",array());?></div></span>
                                                        </button>
                                                    </div>
					</div>
				</div>
				<div class="col-md-3 col-sm-3 col-xs-12"><br></div>

				<div class="h150 fleft100"></div>
			</div>
		</section>
				

		<footer class="text-center fleft100 m-t30 m-b10"><div class="container"><img src="<?php echo base_url().'assets/images/logo-footer.png';?>" class="wauto" alt=""> <span class="fleft100 text-center">DUMBU - 2017 - <?php echo $CI->T("TODOS OS DIREITOS RESERVADOS",array());?></span></div></footer>

		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>-->
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="<?php echo base_url().'assets/bootstrap/js/bootstrap.min.js';?>"></script>
		<script src="<?php echo base_url().'assets/js/jquery.dlmenu.js';?>"></script>
		<script>
			$(function() {
				$( '#dl-menu' ).dlmenu();
			});
		</script>
                
                <!--modal_container_alert_message-->
                <div class="modal fade" style="top:30%" id="modal_alert_message" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div id="modal_container_alert_message" class="modal-dialog modal-sm" role="document">                                                          
                        <div class="modal-content">
                            <div class="modal-header">
                                <button id="btn_modal_close" type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <img src="<?php echo base_url() . 'assets/images/FECHAR.png'; ?>"> <!--<span aria-hidden="true">&times;</span>-->
                                </button>
                                <h5 class="modal-title" id="myModalLabel"><b><?php echo $CI->T("Mensagem", array()); ?></b></h5>                        
                            </div>
                            <div class="modal-body">                                            
                                <p id="message_text"></p>                        
                            </div>
                            <div class="modal-footer text-center">
                                <button id="accept_modal_alert_message" type="button" class="btn btn-default active text-center ladda-button" data-style="expand-left" data-spinner-color="#ffffff">
                                    <span class="ladda-label"><div style="color:white; font-weight:bold"><?php echo $CI->T("ACEITAR", array()); ?></div></span>
                                </button>
                            </div>
                        </div>
                    </div>                                                        
                </div> 
                
                <!-- Afilio Master Tag Purchase Page-->
                <?php 
                    echo'<script type="text/javascript" src="https://v2.afilio.com.br/mastertag.php?progid=2289&type=transaction&id_partner=dumbupro&amount=#'.sprintf("%.2f", ($Afilio_total_value/100)).'#&transaction_id=#'.$Afilio_UNIQUE_ID.'#&customer_type=#'.$Afilio_UNIQUE_ID.'#&product_id1=#dumbu_'.$Afilio_product_id.'#&url_product=#https://dumbu.pro/dumbu/src/index.php/welcome/purchase#&order_date=#'.date("Y-m-d",time()).'#&order_status=#completed#&customer_id=#$Afilio_UNIQUE_ID#"></script>';
                ?>
	</body>
</html>