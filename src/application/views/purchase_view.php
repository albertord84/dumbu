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
                
                <link rel="stylesheet" href="<?php echo base_url().'assets/css/ladda-themeless.min.css'?>">
                <script src="<?php echo base_url().'assets/js/spin.min.js'?>"></script>
                <script src="<?php echo base_url().'assets/js/ladda.min.js'?>"></script>
                
                <script type="text/javascript">var base_url = '<?php echo base_url();?>';</script> 
                <script type="text/javascript">var user_id = '<?php echo $user_id;?>';</script>                 
                <script type="text/javascript">var profiles = '<?php echo $profiles;?>';</script>                 
                <script type="text/javascript" src="<?php echo base_url().'assets/js/purchase.js';?>"></script>
                
	</head>
	<body>
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
						<li><a href="#"><img src="<?php echo base_url().'assets/images/user.png';?>" class="wauto us" alt=""> SAIR</a></li>
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
						<h2 class="cl-green"><b>Assinatura aprovada <br>com sucesso!</b></h2>
					</div>				
					<div class="fleft100 text-center pd-20 bk-cinza m-t30">
						<p>
							<b>Sua compra foi autorizada com sucesso!</b>
							<br><br>
							Agora você precisa  escolher seus perfis de referência. 
							Eles serão usados para captar os seguidores que deseja.  Perfis de referênci são todos os perfis que tem algo a ver com a sua conta, como um concorrente ou perfil similar, por exemplo.
						</p>
						<span class="fleft100 m-tb30">PASSO 4</span>
						<span class="fleft100 m-b10"><b>Adicione 3 perfis de referência abaixo:</b> <small class="fleft100 cl-red m-b10">*Obrigatório</small></span>
						
                                                                                                   
                                                    <ul class="add-perfil">
                                                            <li>
                                                                <div id="reference_profile0" class="container-reference-profile">                                                                    
                                                                    <img id="img_ref_prof0" class="img_profile" style="width:70px" src="<?php echo base_url().'assets/images/avatar.png';?>"> 
                                                                    <br>
                                                                    <a id="lnk_ref_prof0" target="_blank" href="#">
                                                                        <small id="name_ref_prof0" title="Ver no Instagram" style="color:black" class="fleft100"></small>
                                                                    </a>
                                                                </div>                                                                
                                                            </li>
                                                            
                                                            <li>
                                                                <div id="reference_profile1" class="container-reference-profile">                                                                    
                                                                    <img id="img_ref_prof1" class="img_profile" style="width:70px" src="<?php echo base_url().'assets/images/avatar.png';?>"> 
                                                                    <br>
                                                                    <a id="lnk_ref_prof1" target="_blank" href="#">
                                                                        <small id="name_ref_prof1" title="Ver no Instagram" style="color:black" class="fleft100"></small>
                                                                    </a>
                                                                </div>                                                                
                                                            </li>
                                                            
                                                            <li>
                                                                <div id="reference_profile2" class="container-reference-profile">                                                                    
                                                                    <img id="img_ref_prof2" class="img_profile" style="width:70px" src="<?php echo base_url().'assets/images/avatar.png';?>">
                                                                    <br>
                                                                    <a id="lnk_ref_prof2" target="_blank" href="#">
                                                                        <small id="name_ref_prof2" title="Ver no Instagram" style="color:black" class="fleft100"></small>
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
                                                                      <button id="btn_modal_close" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                      <h4 class="modal-title" id="myModalLabel">Perfil de referência</h4>
                                                                  </div>
                                                                  <div class="modal-body">
                                                                      <input id = "login_profile" type="text" class="form-control" placeholder="Perfil" onkeyup="javascript:this.value=this.value.toLowerCase();" style="text-transform:lowercase;"  required>
                                                                      <div id="reference_profile_message" class="form-group m-t10" style="text-align:left;visibility:hidden; font-family:sans-serif; font-size:0.9em"> </div>
                                                                  </div>
                                                                  <div class="modal-footer">
                                                                      <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
                                                                      <button id="btn_insert_profile" type="button" class="btn btn-primary text-center">Adicionar</button>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                        
                                                    </div>                                                    

                                                    <div class="text-center">
                                                        <button id="continuar_purchase" class="btn-primary btn-green m-t20">CONTINUAR</button>
                                                    </div>
					</div>
				</div>
				<div class="col-md-3 col-sm-3 col-xs-12"><br></div>

				<div class="h150 fleft100"></div>
			</div>
		</section>
				

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
	</body>
</html>