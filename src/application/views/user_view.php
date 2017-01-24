<!DOCTYPE html>
<html lang="pt_BR">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
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
                
                <script type="text/javascript">var base_url = '<?php echo base_url();?>';</script> 
                <script type="text/javascript" src="<?php echo base_url().'assets/js/user.js';?>"></script>
                <script type="text/javascript" src="<?php echo base_url().'assets/js/sign_painel.js';?>"></script>

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
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
						<li><a href="#lnk_how_function">COMO FUNCIONA</a></li>
						<li><a href="#lnk_sign_in_now">ASSINAR AGORA</a></li>
						<li>
							<a href="#">ENTRAR</a>
							<ul class="dl-submenu">
								<li>
									<form id="usersLoginForm" action="#" method="#" class="form" role="form" accept-charset="UTF-8">
										<div class="form-group center" style="font-family:sans-serif; font-size:0.9em">
										EXCLUSIVO PARA USUÁRIOS
										</div>
										<div class="form-group center" style="font-family:sans-serif; font-size:0.7em">
										Use login e senha de Instagram
										</div>
										<div class="form-group">
											<input id="userLogin1" type="text" class="form-control" placeholder="Usuário" onkeyup="javascript:this.value=this.value.toLowerCase();" style="text-transform:lowercase;" required="">
										</div>
										<div class="form-group">
											<input id="userPassword1" type="password" class="form-control" placeholder="Senha" required="">
										</div>
										<div class="form-group">
											<input type="submit" name="" value="ENTRAR" id="btn_dumbu_login1" class="btn btn-success btn-block ladda-button" type="button" data-style="expand-left" data-spinner-color="#ffffff" />
										</div>
										<div id="container_login_message1" class="form-group" style="text-align:justify;visibility:hidden; font-family:sans-serif; font-size:0.9em">
										</div>
									</form>
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
						<li><a href="#lnk_how_function">COMO FUNCIONA</a></li>
						<li><a href="#lnk_sign_in_now">ASSINAR AGORA</a></li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><img src="assets/images/user.png" class="wauto us" alt=""> ENTRAR <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li>
									<div class="row">
										<div class="col-md-12">
											<form id="usersLoginForm" action="#" method="#" class="form" role="form" accept-charset="UTF-8">
												<div class="form-group center" style="font-family:sans-serif; font-size:0.9em">
												EXCLUSIVO PARA USUÁRIOS
												</div>
												<div class="form-group center" style="font-family:sans-serif; font-size:0.7em">
												Use login e senha de Instagram
												</div>
												<div class="form-group">
													<input id="userLogin2" type="text" class="form-control" placeholder="Usuário" onkeyup="javascript:this.value=this.value.toLowerCase();" style="text-transform:lowercase;" required="">
												</div>
												<div class="form-group">
													<input id="userPassword2" type="password" class="form-control" placeholder="Senha" required="">
												</div>
												<div class="form-group">
													<button id="btn_dumbu_login2" class="btn btn-success btn-block ladda-button" type="button" data-style="expand-left" data-spinner-color="#ffffff">
														<span class="ladda-label">Entrar</span>
													</button>
												</div>
												<div id="container_login_message2" class="form-group" style="text-align:justify;visibility:hidden; font-family:sans-serif; font-size:0.9em">
												</div>
											</form>
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
					<h1 class="fleft100 text-center">Ganhe em média 5 mil* seguidores reais por mês.</h1>
					<h4 class="fleft100 text-center no-mg">Pague por mês e receba seguidores todos os dias.</h4>
				</div>
				<div class="col-md-4 col-sm-4 col-xs-12 pd-r15 passos m-t45">
					<h4 class="fleft100"><b>PASSO A PASSO</b></h4>
					<ul class="fleft100 passos">
						<li><span>1</span><p>Escolha os Perfis de referência que deseja captar seus seguidores</p></li>
						<li><span>2</span><p>A ferramenta seguirá automaticamente os seguidores dos Perfis de referência</p></li>
						<li class="active"><span>3</span><p>Uma parte desses seguidores seguirá você de volta por se identificar com seu conteúdo</p></li>
						<li><span>4</span><p>Entre 24h e 48h a ferramenta deixará de seguir esses perfis automáticamente</p></li>
					</ul>
				</div>
				<div class="col-md-3 col-sm-3 col-xs-12 text-center ps m-t45">
					<div class="plano plano-mensal text-center fleft100">
						<h4 class="no-mg"><b>PLANO MENSAL</b></small></h4>
						<span class="cl-fff fleft100">Sem multa de rescisão.</span>
						<hr>
						<span class="fleft100 cl-fff no-mg">A partir de</span>
						<h1 class="fleft100 cl-fff no-mg">R$<b>4,90</b></h1>
						<span class="fleft100 cl-fff no-mg">no 1º mês</span>
                                                <a href="#lnk_sign_in_now">
                                                    <div class="text-center"><button class="btn-primary btn-green m-t20">ASSINAR</button></div>
                                                </a>
					</div>
					<span class="fleft100 m-t30">Presente em mais de</span>
					<h2 class="fleft100 no-mg">50 países</h2>
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
					<li><img src="assets/images/100.png" alt=""><span>100%</span><p>seguidores reais</p></li>
					<li><img src="assets/images/lupa.png" style="margin-top: 3px;" alt=""><p>Você escolhe o perfil para captar seguidores</p></li>
					<li><img src="assets/images/nuvem.png" style="margin-top: 6px;" alt=""> <p>Todos os perfis que segue  estarão protegidos em seu backup</p></li>
				</ul>
			</div>
		</section>

		<section id="funciona" class="fleft100">
			<div class="container">
				<A name="lnk_how_function"></A>				
                                <h3 class="titulo fleft100 text-center m-tb30">COMO FUNCIONA</h3>
				<div class="col-md-8 col-sm-8 col-xs-12">
					<img src="assets/images/como-funciona-1.png" class="hidden-mobile" alt="">
					<img src="assets/images/como-funciona-2.png" class="visible-mobile" alt="">
				</div>
				<div class="col-md-4 col-sm-4 col-xs-12 text-center">
					<img src="assets/images/info.png" class="wauto m-b10" alt="">
					<span class="texto fleft100">
						A Dumbu não deposita seguidores em sua conta, nós captamos seguidores reais. O resultado da ferramenta depende diretamente das escolhas que o assinante faz para os seus perfis de referência.
						<br><br>
						*5 mil seguidores é a média de ganho para assinantes do plano de R$ 189,90 que postam diariamente e escolhem bons perfis de referência.  Esse número pode variar de acordo com cada conta.
					</span>
                                        <span class="texto fleft100">
                                            <br>
                                        </span>
                                        <a id="help" style="color:green; margin-top:7%">
                                            <div >
                                                <img style="width:12%" src="<?php echo base_url().'assets/images/help.png'?>"/>
                                            </div>
                                            <div style="margin-top:2%;margin-bottom:2%">
                                                Veja as dicas para melhorar o desempenho
                                            </div>
                                        </a>
				</div>
			</div>
		</section>

		<section id="assinar" class="fleft100">
                        <A name="lnk_sign_in_now"></A>
			<div class="container">
				<h3 class="titulo fleft100 text-center m-tb30">ASSINAR <small class="fleft100">Plano mensal sem multa de rescisão.</small></h3>
				<div class="col-md-3 col-sm-3 col-xs-12">
					<div id="container_plane_4_90" class="plano text-center fleft100">
						<h2>R$<b>4,90</b> <small>/1º mês</small></h2>
						<span>Depois R$<b>29,90</b></span>
						<hr>
						<p>
							<b>RESULTADO:</b> <br>
							Em média <b>750</b> <br>
							seguidores ao mês
						</p>
                                                <input id="radio_plane_4_90" type="radio" name="plano">
					</div>
				</div>
				<div class="col-md-3 col-sm-3 col-xs-12">
					<div id="container_plane_9_90" class="plano text-center fleft100">
						<h2>R$<b>9,90</b> <small>/1º mês</small></h2>
						<span>Depois R$<b>49,90</b></span>
						<hr>
						<p>
							<b>RESULTADO:</b> <br>
							Em média <b>1.500</b> <br>
							seguidores ao mês
						</p>
                                                <input id="radio_plane_9_90" type="radio" name="plano">
					</div>
				</div>
				<div class="col-md-3 col-sm-3 col-xs-12">
					<div id="container_plane_29_90" class="plano active text-center fleft100">
						<h2>R$<b>29,90</b> <small>/1º mês</small></h2>
						<span>Depois R$<b>99,90</b></span>
						<div class="rc">RECOMENDADO</div>
						<hr>
						<p>
							<b>RESULTADO:</b> <br>
							Em média <b>3.000</b> <br>
							seguidores ao mês
						</p>
                                                <input id="radio_plane_29_90" type="radio" name="plano">
					</div>
				</div>
				<div class="col-md-3 col-sm-3 col-xs-12">
                                        <div id="container_plane_99_90" class="plano text-center fleft100">
						<h2>R$<b>99,90</b> <small>/1º mês</small></h2>
						<span>Depois R$<b>189,90</b></span>
						<hr>
						<p>
							<b>RESULTADO:</b> <br>
							Em média <b>5.000</b> <br>
							seguidores ao mês
						</p>
                                                <input id="radio_plane_99_90" type="radio" name="plano">
					</div>
				</div>
			</div>
		</section>
                
                
		<section id="passos" class="fleft100 m-t30">
			<div class="container cl-black">
                                <div id="container_login_panel" class="col-md-4 col-sm-4 col-xs-12 passo">
					<h5 class="no-mg text-center"><b>PASSO 1</b></h5>
					<div class="text-center fleft100 m-t20">
						<img src="assets/images/ig.png" class="wauto" alt="">
						<span class="fleft100 m-b5">Conta de Instagram</span>
					</div>
                                        <div id="login_sign_in" class="login fleft100 input-form">
						<fieldset>
                                                    <input id="client_email" type="text" placeholder="E-mail pessoal" required>
						</fieldset>
						<fieldset>
							<input id = "signin_clientLogin" type="text" placeholder="Usuário Instagram" onkeyup="javascript:this.value=this.value.toLowerCase();" style="text-transform:lowercase;"  required>
						</fieldset>
						<fieldset>
							<input id = "signin_clientPassword" type="password" placeholder="Senha Instagram" required>
						</fieldset>
						<div class="text-center"><button id="container_sigin_message" class="btn-primary m-t20">CONFERIR</button></div>
					</div>
				</div>
                            
                                <div id="coniner_data_panel" class="col-md-4 col-sm-4 col-xs-12 passo">
					<h5 class="no-mg text-center"><b>PASSO 2</b></h5>
					<div class="text-center fleft100 m-t20">
						<img src="assets/images/pay.png" class="wauto" alt="">
						<span class="fleft100">Informações de pagamento</span>
					</div>
					<div class="pay fleft100 input-form">
						<fieldset>
							<input id="client_credit_card_name" type="text" placeholder="Nome no cartão"  type="text" onkeyup="javascript:this.value=this.value.toUpperCase();" style="text-transform:uppercase;" required>
						</fieldset>
						<div class="col-md-9 col-sm-9 col-xs-12 pd-r5">
                                                    <fieldset>
                                                        <input id="client_credit_card_number" type="text" placeholder="Número do cartão" maxlength="20" required>
                                                    </fieldset>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-12 pd-l5">
							<fieldset>
                                                            <input id="client_credit_card_cvv" type="text" placeholder="CVV/CVC" maxlength="5" required>
							</fieldset>
						</div>
						<div class="col-md-4 col-sm-4 col-xs-12 no-pd">
							<span class="val">Validade</span>
						</div>
						<div class="col-md-4 col-sm-4 col-xs-12 pd-r15">
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
						<div class="col-md-4 col-sm-4 col-xs-12 no-pd">
							<fieldset>
								<div class="select">
                                                                    <select id="client_credit_card_validate_year" name="local" class="btn-primeiro sel" id="local">
                                                                        <option value="">2017</option>
								    </select>
								</div>
							</fieldset>
						</div>
					</div>
				</div>
                                <div id="container_sing_in_panel" class="col-md-4 col-sm-4 col-xs-12 passo text-center">
					<h5 class="no-mg text-center"><b>PASSO 3</b></h5>
					<div class="text-center fleft100 m-t20">
						<img src="assets/images/ass.png" class="wauto" alt="">
						<span class="fleft100">Assine e configure sua conta</span>
					</div>

					<div class="text-center"><button class="btn-primary btn-green m-t20">ASSINAR AGORA</button></div>

                                        <br><br>Ao assinar já estou aceitando os <a href="<?php echo base_url().'assets/others/TERMOS DE USO DUMBU.pdf'?>" target="_blank" style="color: blue">termos de uso</a>
                                        <br><br><img src="assets/images/seguro.png" class="wauto" alt="">
				</div>
			</div>
		</section>

		<section id="contato" class="fleft100 input-form">
			<div class="container">
				<h3 class="titulo fleft100 text-center m-tb30">FALE CONOSCO</h3>
				<div class="col-md-3 col-sm-3 col-xs-12"><br></div>
				<div class="col-md-6 col-sm-6 col-xs-12 no-pd">
					<div class="col-md-6 col-sm-6 col-xs-12 pd-r15">
						<fieldset>
							<input type="text" placeholder="Nome">
						</fieldset>
						<fieldset>
							<input type="text" placeholder="E-mail">
						</fieldset>
					</div>
					<div class="col-md-6 col-sm-6 col-xs-12 pd-l15">
						<fieldset>
							<input type="text" placeholder="Empresa">
						</fieldset>
						<fieldset>
							<input type="text" placeholder="Telefone">
						</fieldset>
					</div>
					<div class="col-md-12 col-sm-12 col-xs-12 no-pd">
						<textarea name="" placeholder="Mensagem" id=""  rows="8"></textarea>

						<div class="text-center"><button class="btn-primary btn-475f66 m-t20">ENVIAR MENSAGEM</button></div>
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