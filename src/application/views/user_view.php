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
                <script type="text/javascript" src="<?php echo base_url().'assets/js/user.js';?>"></script>
                <script type="text/javascript" src="<?php echo base_url().'assets/js/sign_painel.js';?>"></script>
                <script type="text/javascript" src="<?php echo base_url().'assets/js/talkme_painel.js';?>"></script>
                
                <?php include_once("pixel_facebook.php")?>
	</head>
	<body id="my_body">
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
				<div id="dl-menu" class="dl-menuwrapper">
					<button class="dl-trigger">Open Menu</button>
					<ul class="dl-menu">
						<li><a href="#lnk_how_function"><?php echo $CI->T("COMO FUNCIONA",array());?></a></li>
						<li><a href="#lnk_sign_in_now"><?php echo $CI->T("ASSINAR AGORA",array());?></a></li>
						<li>
							<a href="#"><?php echo $CI->T("ENTRAR",array());?></a>
							<ul class="dl-submenu">
								<li>
                                                                    <div id="login_container1">
									<form id="usersLoginForm" action="#" method="#" class="form" role="form" accept-charset="UTF-8">
										<div class="form-group center" style="font-family:sans-serif; font-size:0.9em">
										<?php echo $CI->T("EXCLUSIVO PARA USUÁRIOS",array());?>
										</div>
										<div class="form-group center" style="font-family:sans-serif; font-size:0.7em">
										<?php echo $CI->T("Use login e senha de Instagram",array());?>
										</div>
										<div class="form-group">
											<input id="userLogin1" type="text" class="form-control" placeholder="<?php echo $CI->T("Usuário",array());?>" onkeyup="javascript:this.value=this.value.toLowerCase();" style="text-transform:lowercase;" required="">
										</div>
										<div class="form-group">
											<input id="userPassword1" type="password" class="form-control" placeholder="<?php echo $CI->T("Senha",array());?>" required="">
										</div>
										<div class="form-group">
                                                                                    <button type="button" name="" value="<?php echo $CI->T("ENTRAR",array());?>" id="btn_dumbu_login1" style="white-space: normal;" class="btn btn-success btn-block ladda-button" type="button" data-style="expand-left" data-spinner-color="#ffffff" ><span class="ladda-label"></span></button>
										</div>
                                                                                <!--<button id="btn_dumbu_login1" class="btn btn-success btn-block ladda-button" type="button" data-style="expand-left" data-spinner-color="#ffffff">
                                                                                        <span class="ladda-label">Entrar</span>
                                                                                </button>-->
										<div id="container_login_message1" class="form-group" style="text-align:justify;visibility:hidden; font-family:sans-serif; font-size:0.9em">
										</div>
									</form>
                                                                    </div>
								</li>
							</ul>
						</li>
					</ul>
				</div><!-- /dl-menuwrapper -->
				<nav class="navbar navbar-default navbar-static-top">
					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="logo pabsolute fleft100 text-center">
						<a class="navbar-brand i-block" href="#">
							<img alt="Brand" src="assets/images/logo.png">
						</a>
					</div>
					<ul class="nav navbar-nav navbar-right menu-principal">
						<li><a href="#lnk_how_function"><?php echo $CI->T("COMO FUNCIONA",array());?></a></li>
						<li><a href="#lnk_sign_in_now"><?php echo $CI->T("ASSINAR AGORA",array());?></a></li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><img src="assets/images/user.png" class="wauto us" alt=""><?php echo $CI->T("ENTRAR",array());?><span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li>
									<div class="row">
										<div class="col-md-12">
                                                                                    <div id="login_container2">
											<form id="usersLoginForm" action="#" method="#" class="form" role="form" accept-charset="UTF-8">
												<div class="form-group center" style="font-family:sans-serif; font-size:0.9em">
                                                                                                    <?php echo $CI->T("EXCLUSIVO PARA USUÁRIOS",array());?>
												</div>
												<div class="form-group center" style="font-family:sans-serif; font-size:0.7em">
                                                                                                    <?php echo $CI->T("Use login e senha de Instagram",array());?>
												</div>
												<div class="form-group">
													<input id="userLogin2" type="text" class="form-control" placeholder="<?php echo $CI->T("Usuário",array());?>" onkeyup="javascript:this.value=this.value.toLowerCase();" style="text-transform:lowercase;" required="">
												</div>
												<div class="form-group">
													<input id="userPassword2" type="password" class="form-control" placeholder="<?php echo $CI->T("Senha",array());?>" required="">
												</div>
												<div class="form-group">
													<button id="btn_dumbu_login2" class="btn btn-success btn-block ladda-button" type="button" data-style="expand-left" data-spinner-color="#ffffff">
														<span class="ladda-label"><?php echo $CI->T("Entrar",array());?></span>
													</button>
												</div>
												<div id="container_login_message2" class="form-group" style="text-align:justify;visibility:hidden; font-family:sans-serif; font-size:0.9em">
												</div>
											</form>
                                                                                    </div>
										</div>
									</div>
								</li>
							</ul>
						</li>
					</ul>
				</nav>
			</div>
		</header>

		<section id="destaque" class="fleft100 bk-black cl-fff">
			<div class="container">
				<div class="fleft100 m-tb60">
					<h1 class="fleft100 text-center"><?php echo $CI->T("Aumente o número de seguidores no seu Instagram",array());?></h1>
					<!--<h4 class="fleft100 text-center no-mg">Pague por mês e receba seguidores todos os dias.</h4>-->
				</div>
				<div class="col-md-4 col-sm-4 col-xs-12 pd-r15 passos m-t45">
					<h4 class="fleft100"><b><?php echo $CI->T("PASSO A PASSO",array());?></b></h4>
					<ul class="fleft100 passos">
						<li><span>1</span><p><?php echo $CI->T("Escolha os Perfis de referência que deseja captar seus seguidores",array());?></p></li>
						<li><span>2</span><p><?php echo $CI->T("A ferramenta seguirá automaticamente os seguidores dos Perfis de referência",array());?></p></li>
						<li class="active"><span>3</span><p><?php echo $CI->T("Alguns desses seguidores poderão seguir você de volta por se identificar com seu conteúdo",array());?></p></li>
						<li><span>4</span><p><?php echo $CI->T("Entre 24h e 48h a ferramenta deixará de seguir esses perfis automáticamente",array());?></p></li>
					</ul>
				</div>
				<div class="col-md-3 col-sm-3 col-xs-12 text-center ps m-t45">
					<div class="plano plano-mensal text-center fleft100">
						<h4 class="no-mg"><b><?php echo $CI->T("PLANO MENSAL",array());?></b></small></h4>
						<span class="cl-fff fleft100"><?php echo $CI->T("Sem multa de rescisão.",array());?></span>
						<hr>
						<span class="fleft100 cl-fff no-mg"><?php echo $CI->T("A partir de",array());?></span>
						<h1 class="fleft100 cl-fff no-mg">R$<b>4,90</b></h1>
						<span class="fleft100 cl-fff no-mg"><?php echo $CI->T("no 1º mês",array());?></span>
                                                <a href="#lnk_sign_in_now">
                                                    <div class="text-center"><button class="btn-primary btn-green m-t20"><?php echo $CI->T("ASSINAR",array());?></button></div>
                                                </a>
					</div>
					<span class="fleft100 m-t30"><?php echo $CI->T("Presente em mais de",array());?></span>
					<h2 class="fleft100 no-mg"><?php echo $CI->T("50 países",array());?></h2>
					<img src="assets/images/50 países.png" class="fleft100 i-block wauto paises" alt="">
				</div>
				<div class="col-md-5 col-sm-5 col-xs-12 text-center cel">
					<img src="assets/images/755K.png" class="fleft100 m-wauto" alt="">
				</div>
			</div>
		</section>

		<section id="vantagens" class="fleft100">
			<div class="container">
				<ul class="fleft100 vantagens text-center cl-fff">
					<li><img src="assets/images/100.png" alt=""><span>100%</span><p><?php echo $CI->T("seguidores reais",array());?></p></li>
					<li><img src="assets/images/lupa.png" style="margin-top: 3px;" alt=""><p><?php echo $CI->T("Você escolhe o perfil para captar seguidores",array());?></p></li>
					<li><img src="assets/images/nuvem.png" style="margin-top: 6px;" alt=""> <p><?php echo $CI->T("Todos os perfis que segue  estarão protegidos em seu backup",array());?></p></li>
				</ul>
			</div>
		</section>

		<section id="funciona" class="fleft100">
			<div class="container">
				<A name="lnk_how_function"></A>				
                                <h3 class="titulo fleft100 text-center m-tb30"><?php echo $CI->T("COMO FUNCIONA",array());?></h3>
				<div class="col-md-8 col-sm-8 col-xs-12">
					<img src="assets/images/como-funciona-1.png" class="hidden-mobile" alt="">
					<img src="assets/images/como-funciona-2.png" class="visible-mobile" alt="">
				</div>
				<div class="col-md-4 col-sm-4 col-xs-12 text-center">
					<img src="assets/images/info.png" class="wauto m-b10" alt="">
					<span class="texto fleft100">
						<?php echo $CI->T("A Dumbu não deposita seguidores em sua conta, nós captamos seguidores reais. O resultado da ferramenta depende diretamente das escolhas que o assinante faz para os seus perfis de referência.",array());?>
						<br>    
						<!-- *5 mil seguidores é a média de ganho para assinantes do plano de R$ 189,90 que postam diariamente e escolhem bons perfis de referência.  Esse número pode variar de acordo com cada conta. -->                                                
					</span>
                                        <span class="texto fleft100">
                                            <br>
                                        </span>
                                        <a id="help" style="color:green; margin-top:7%">
                                            <div >
                                                <img style="width:12%" src="<?php echo base_url().'assets/images/help.png'?>"/>
                                            </div>
                                            <div style="margin-top:2%;margin-bottom:2%">
                                                <?php echo $CI->T("Veja as dicas para melhorar o desempenho",array());?>
                                            </div>
                                        </a>
				</div>
			</div>
		</section>

		<section id="assinar" class="fleft100">
                        <A name="lnk_sign_in_now"></A>
			<div class="container">
				<h3 class="titulo fleft100 text-center m-tb30"><?php echo $CI->T("ASSINAR",array());?><small class="fleft100">Plano mensal sem multa de rescisão.</small></h3>
				<div class="col-md-3 col-sm-3 col-xs-12">
					<div id="container_plane_4_90" class="plano text-center fleft100">
                                                <img style="width:60%" src="<?php echo base_url().'assets/images/velocimetro01.png'?>"/>
                                                <p style="font-size:0.7em"><?php echo $CI->T("VELOCIDADE",array());?></p>
                                                <b style="font-size:1.5em"><?php echo $CI->T("BAIXA",array());?></b>
                                                <hr>
						<h2><?php echo $CI->T("R$",array());?><b>4,90</b> <small>/<?php echo $CI->T("1º mês",array());?></small></h2>
						<span><?php echo $CI->T("Depois R$",array());?><b>29,90</b></span>
                                                <br>
                                                <input id="radio_plane_4_90" type="radio" name="plano">
					</div>
				</div>
				<div class="col-md-3 col-sm-3 col-xs-12">
					<div id="container_plane_9_90" class="plano text-center fleft100">
						<img style="width:60%" src="<?php echo base_url().'assets/images/velocimetro02.png'?>"/>
                                                <p style="font-size:0.7em"><?php echo $CI->T("VELOCIDADE",array());?></p>
                                                <b style="font-size:1.5em"><?php echo $CI->T("MODERADA",array());?></b>
                                                <hr>
						<h2><?php echo $CI->T("R$",array());?><b>9,90</b> <small>/<?php echo $CI->T("1º mês",array());?></small></h2>
						<span><?php echo $CI->T("Depois R$",array());?><b>49,90</b></span>
                                                <br>
                                                <input id="radio_plane_9_90" type="radio" name="plano">
					</div>
				</div>
				<div class="col-md-3 col-sm-3 col-xs-12">
					<div id="container_plane_29_90" class="plano active text-center fleft100">
                                                <img style="width:60%" src="<?php echo base_url().'assets/images/velocimetro03.png'?>"/>
                                                <p style="font-size:0.7em"><?php echo $CI->T("VELOCIDADE",array());?></p>
                                                <b style="font-size:1.5em"><?php echo $CI->T("RÁPIDA",array());?></b>
                                                <h2>R$<b>29,90</b> <small>/<?php echo $CI->T("1º mês",array());?></small></h2>
						<span><?php echo $CI->T("Depois R$",array());?><b>99,90</b></span>
						<div class="rc">RECOMENDADO</div>
                                                <br>
                                                <input id="radio_plane_29_90" type="radio" name="plano" checked="true">
					</div>
				</div>
				<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div id="container_plane_99_90" class="plano text-center fleft100">
                                                <img style="width:60%" src="<?php echo base_url().'assets/images/velocimetro04.png'?>"/>
                                                <p style="font-size:0.7em"><?php echo $CI->T("VELOCIDADE",array());?></p>
                                                <b style="font-size:1.5em"><?php echo $CI->T("TURBO!",array());?></b>
                                                <hr>
						<h2><?php echo $CI->T("R$",array());?><b>99,90</b> <small>/<?php echo $CI->T("1º mês",array());?></small></h2>
						<span><?php echo $CI->T("Depois R$",array());?><b>189,90</b></span>
                                                <br>
                                                <input id="radio_plane_99_90" type="radio" name="plano">
					</div>
				</div>
			</div>
		</section>
                                
		<section id="passos" class="fleft100 m-t30">
			<div class="container cl-black">
                                <div  class="col-md-4 col-sm-4 col-xs-12 passo m-t40">
                                    <div id="container_login_panel" style="visibility:hidden;display:none">
                                        <h5 class="no-mg text-center"><b><?php echo $CI->T("PASSO 1",array());?></b></h5>
					<div class="text-center fleft100 m-t20">
						<img src="assets/images/ig.png" class="wauto" alt="">
						<span class="fleft100 m-b5"><?php echo $CI->T("Conta de Instagram",array());?></span>
					</div>
                                        <div id="login_sign_in" class="login fleft100 input-form">
						<fieldset>
                                                    <input id="client_email" type="text" placeholder="<?php echo $CI->T("E-mail pessoal",array());?>" required>
						</fieldset>
						<fieldset>
							<input id = "signin_clientLogin" type="text" placeholder="<?php echo $CI->T("Usuário Instagram",array());?>" onkeyup="javascript:this.value=this.value.toLowerCase();" style="text-transform:lowercase;"  required>
						</fieldset>
						<fieldset>
							<input id = "signin_clientPassword" type="password" placeholder="<?php echo $CI->T("Senha Instagram",array());?>" required>
						</fieldset>
                                                <div class="text-center">
                                                    <button id = "signin_btn_insta_login" type="button" class="btn-primary m-t20 ladda-button" data-style="expand-left" data-spinner-color="#ffffff">
                                                        <span class="ladda-label"><div style="color:white; font-weight:bold"><?php echo $CI->T("CONFERIR",array());?></div></span>
                                                    </button>
                                                </div>
                                                <div id="container_sigin_message" class="text-center" style="margin-top:7%; visibility:hidden; font-family:sans-serif; font-size:0.9em">                                                        
                                                </div>
					</div>
                                    </div>
                                    <div id="signin_profile"  style="text-align:center; visibility:visible;display:block">
                                        <h5 class="no-mg text-center"><b><?php echo $CI->T("PASSO 1",array());?></b></h5>
                                        <br><p style="font-family:sans-serif; font-size:1em; color: green"><?php echo $CI->T("Perfil conferido!",array());?><br><br></p>                    
                                        <div id="reference_profile">
                                            <img id="img_ref_prof" class="img-circle image-reference-profile" style="width:20%" src=""><br>
                                            <b id="name_ref_prof" style="font-family:sans-serif; font-size:1em;"></b><br>
                                            <div id="ref_prof_followers" style="font-family:sans-serif; font-size:1em;"></div>
                                            <div id="ref_prof_following" style="font-family:sans-serif; font-size:1em;"></div>
                                        </div>
                                    </div>
				</div>
                            
                                <div id="coniner_data_panel" class="col-md-4 col-sm-4 col-xs-12 passo m-t40">
					<h5 class="no-mg text-center"><b><?php echo $CI->T("PASSO 2",array());?></b></h5>
					<div class="text-center fleft100 m-t20">
						<img src="assets/images/pay.png" class="wauto" alt="">
						<span class="fleft100"><?php echo $CI->T("Informações de pagamento",array());?></span>
					</div>
					<div class="pay fleft100 input-form">
						<fieldset>
							<input id="client_credit_card_name" type="text" placeholder="<?php echo $CI->T("Meu nome no cartão",array());?>"  type="text" onkeyup="javascript:this.value=this.value.toUpperCase();" style="text-transform:uppercase;" required>
						</fieldset>
						<div class="col-md-9 col-sm-9 col-xs-12 pd-r5">
                                                    <fieldset>
                                                        <input id="client_credit_card_number" type="text" placeholder="<?php echo $CI->T("Número do cartão",array());?>" maxlength="20" required>
                                                    </fieldset>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-12 pd-l5">
							<fieldset>
                                                            <input id="client_credit_card_cvv" type="text" placeholder="<?php echo $CI->T("CVV/CVC",array());?>" maxlength="5" required>
							</fieldset>
						</div>
						<div class="col-md-4 col-sm-4 col-xs-12 no-pd">
							<span class="val"><?php echo $CI->T("Validade",array());?></span>
						</div>
						<div class="col-md-4 col-sm-4 col-xs-12 pd-r15 m-t10">
							<fieldset>
								<div class="select">
                                                                    <select id="client_credit_card_validate_month" name="local" class="btn-primeiro sel" id="local">
									<option>01</option><option>02</option><option>03</option>
                                                                        <option>04</option><option>05</option><option>06</option>
                                                                        <option>07</option><option>08</option><option>09</option>
                                                                        <option>10</option><option>11</option><option>12</option>
								    </select>
								</div>
							</fieldset>
						</div>
						<div class="col-md-4 col-sm-4 col-xs-12 no-pd m-t10">
							<fieldset>
								<div class="select">
                                                                    <select id="client_credit_card_validate_year" name="local" class="btn-primeiro sel" id="local">
                                                                        <option>2017</option><option>2018</option>
                                                                        <option>2019</option><option>2020</option><option>2021</option>
                                                                        <option>2022</option><option>2023</option><option>2024</option>
                                                                        <option>2025</option><option>2026</option><option>2027</option>
                                                                        <option>2028</option><option>2029</option><option>2030</option>
                                                                        <option>2031</option><option>2032</option><option>2033</option>
                                                                        <option>2034</option><option>2035</option><option>2036</option>
                                                                        <option>2037</option><option>2038</option><option>2039</option>
								    </select>
								</div>
							</fieldset>
						</div>
					</div>
				</div>
                            
                                <div id="container_sing_in_panel" class="col-md-4 col-sm-4 col-xs-12 passo text-center m-t40">
					<h5 class="no-mg text-center"><b><?php echo $CI->T("PASSO 3",array());?></b></h5>
					<div class="text-center fleft100 m-t20">
						<img src="assets/images/ass.png" class="wauto" alt="">
						<span class="fleft100"><?php echo $CI->T("Assine e configure sua conta",array());?></span>
					</div>

					<div class="text-center">
                                            <button id="btn_sing_in" type="button" class="btn-primary btn-green m-t20 ladda-button btn-lg" data-style="expand-left" data-spinner-color="#ffffff" data-toggle="modal" data-target="#myModal">
                                                <span class="ladda-label"><div style="color:white; font-weight:bold"><?php echo $CI->T("ASSINAR AGORA",array());?></div></span>
                                            </button>                                            
                                        </div>
                                        
                                        <!--
                                        <div class="modal fade" id="myModal" role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">Atenção</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>A operação pode demorar alguns segundos. Aguarde, por favor...</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>-->
                                        <br><br><?php echo $CI->T("Ao assinar já estou aceitando os ",array());?><a id="use_term" href="<?php echo base_url().'assets/others/TERMOS DE USO DUMBU.pdf'?>" target="_blank" style="color: blue"><?php echo $CI->T("termos de uso",array());?></a>
                                        <br><br><img src="assets/images/seguro.png" class="wauto" alt="">
				</div>
			</div>
		</section>

		<section id="contato" class="fleft100 input-form">
			<div class="container">
				<h3 class="titulo fleft100 text-center m-tb30"><?php echo $CI->T("FALE CONOSCO",array());?></h3>
				<div class="col-md-3 col-sm-3 col-xs-12"><br></div>
                                <div id="talkme_frm" class="col-md-6 col-sm-6 col-xs-12 no-pd">
					<div class="col-md-6 col-sm-6 col-xs-12 pd-r15">
						<fieldset>
							<input id="visitor_name" type="text" placeholder="<?php echo $CI->T("Nome",array());?>">
						</fieldset>
						<fieldset>
							<input id="visitor_email" type="text" placeholder="<?php echo $CI->T("E-mail",array());?>">
						</fieldset>
					</div>
					<div class="col-md-6 col-sm-6 col-xs-12 pd-l15">
						<fieldset>
							<input id="visitor_company" type="text" placeholder="<?php echo $CI->T("Empresa",array());?>">
						</fieldset>
						<fieldset>
							<input id="visitor_phone" type="text" placeholder="<?php echo $CI->T("Telefone",array());?>">
						</fieldset>
					</div>
					<div class="col-md-12 col-sm-12 col-xs-12 no-pd">
						<textarea id="visitor_message" name="" placeholder="<?php echo $CI->T("Mensagem",array());?>" id=""  rows="8"></textarea>

						<div class="text-center">
                                                    <button id="btn_send_message" class="btn-primary btn-475f66 m-t20 ladda-button"  data-style="expand-left" data-spinner-color="#ffffff">
                                                        <?php echo $CI->T("ENVIAR MENSAGEM",array());?>
                                                    </button>
                                                </div>
					</div>
				</div>
				<div class="col-md-3 col-sm-3 col-xs-12"><br></div>

				<footer class="text-center fleft100 m-t30 m-b10"><img src="assets/images/logo-footer.png" class="wauto" alt=""></footer>
			</div>
		</section>

		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="assets/bootstrap/js/bootstrap.min.js"></script>
		<script src="assets/js/jquery.dlmenu.js"></script>
		<script>
			$(function() {
				$( '#dl-menu' ).dlmenu();
			});
		</script>
	</body>
</html>