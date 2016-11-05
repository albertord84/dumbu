<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>DUMBU</title>
        <!--p.footer { text-align: right;font-size: 11px; border-top: 1px solid #D0D0D0; line-height: 32px; padding: 0 10px 0 10px; margin: 20px 0 0 0;}-->
        <style type="text/css">                       
            a { color: white; background-color: transparent; font-weight: normal; font-size: 12px;}            
            
            #container {z-index: 1; position: absolute; left: 0%; top:0%; width: 100%; height: 100%;}            
            #head { position: absolute;  background-color: #0F0F0F; top:0%; height: 10%; width: 100%;}             
            #body {position: absolute; background-color: #2B2B2B; top:10%; height: 70%; width: 100%;}             
            #footer {position: absolute; background-color: #202020; top:80%; height: 20%; width: 100%;}
        </style>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/users_login_style.css'?>">        
    
        <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/js/alert/css/wow-alert.css'?>">        
        
        
    </head>    
    
    <script type="text/javascript">var base_url = '<?php echo base_url();?>'; </script>
    <script type="text/javascript">var user_active /*=false;*/</script>
    <script type="text/javascript" src="<?php echo base_url().'assets/js/jquery.js'?>"></script>
    
    <script type="text/javascript" src="<?php echo base_url().'assets/js/user.js'?>" ></script>
    
    <script type="text/javascript" src="<?php echo base_url().'assets/js/alert/js/wow-alert.js'?>" ></script>
    
   
    <body style="z-index: 1">        
        <div id="container">
            <div id="head">                 
                <div style="z-index: 1;color:white; position: absolute; top:15%; height: 60%; left: 43%; width:14%">
                    <a href="<?php echo base_url().'index.php/welcome/'?>"><img src="<?php echo base_url().'assets/img/dumbu_logo_png.png'?>" style=" z-index: 2;position: absolute; top:0%; height: 100%; left: 0%; width: 100%;"></a>
                </div>   
                <div id="talkme" style="position: absolute; top:2%; left: 67%;">
                    <a  href="<?php echo base_url().'index.php/welcome/talk_me'?>">FALE CONOSCO</a>
                </div>
                <div id="how_function" style="position: absolute; top:2%; left: 75%;">
                    <a  href="<?php echo base_url().'index.php/welcome/how_function'?>">COMO FUNCIONA</a>
                </div>
                <div id="sing_in" style="position: absolute; top:2%; left: 84%;">
                    <a  href="<?php echo base_url().'index.php/welcome/sign_in'?>">ASSINAR AGORA</a>
                </div>                
                <div id="login" style="position: absolute; top:2%; left: 93%;">
                    <a href="#"  >ENTRAR</a> <!--<?php// echo base_url().'index.php/welcome/log_in'?>-->                    
                </div>
                <center>
                <div id = "usersLoginForm">
                    <form method = "post" action = "">
                        <img type = "image"       id = "userCloseLogin"   src = "<?php echo base_url().'assets/img/close.png'?>">
                        <input type = "text"      id = "userLogin"         placeholder = "Usuário">
                        <input type = "password"  id = "userPassword"      placeholder = "***"  >
                        <input type = "button"    id = "btn_dumbu_login"       value = "Entrar" style="cursor:pointer;">
                    </form>
                    <img id="waiting" src="<?php echo base_url().'assets/img/waiting.gif'?>"/>
                </div>
                </center>
                
                <div id="logout" style="position: absolute; top:2%; left: 94%;">
                        <a  href="<?php echo base_url().'index.php/welcome/log_out'?>">SAIR</a>                    
                </div>
                
                <div id="update_user" style="position: absolute; top:2%; left: 84%;"> 
                    <a  href="<?php echo base_url().'index.php/welcome/sign_client_update'?>">ATUALIZAR DADOS</a>
                </div>
            </div>
            
            <div id="body">
                <div id="content" style="height: 100%;"> <?php if($content) echo $content; ?> </div>
            </div>
            
           
           <div id="footer">                
                <div style="color: white; position: absolute; top: 20%; height: 60%; left: 15%; width: 20%">
                    <div style="position: absolute; left: 0%; top: 20%">
                        <img src="<?php echo base_url().'assets/img/user.png'?>">
                    </div>
                    <div style="text-align:center; position: absolute; left: 25%; top: 12%">
                        <b style="font-size: 35px">100%</b><br>
                        <b style="font-size: 20px">seguidores reais</b>
                    </div>
                </div>
                
                <div style="color: white; position: absolute; top: 20%; height: 60%; left: 40%; width: 20%">
                    <div style="position: absolute; left: 0%; top: 20%">
                        <img src="<?php echo base_url().'assets/img/lupa.png'?>">
                    </div>
                    <div style="text-align:left; position: absolute; left: 25%; top: 20%">
                        <b style="font-size: 20px">Você escolhe o perfil</b><br>
                        <b style="font-size: 20px">para captar seguidores</b>
                    </div>
                </div>
                
                <div style="color: white; position: absolute; top: 20%; height: 60%; left: 65%; width: 20%">
                    <div style="position: absolute; left: 0%; top: 20%">
                        <img src="<?php echo base_url().'assets/img/nuvem.png'?>">
                    </div>
                    <div style="text-align:left; position: absolute; left: 27%; top: 12%">
                        <b style="font-size: 20px">Seus followings</b><br>
                        <b style="font-size: 20px">originais estarão salvos</b><br>
                        <b style="font-size: 20px">em seu backup</b>
                    </div>
                </div>
            </div>
        </div>
        
        
        <script type="text/javascript">user_active=<?php echo $user_active;?></script>
        <script type="text/javascript" src="<?php echo base_url().'assets/js/an_user_active.js'?>" ></script>    
        
    </body>
</html>