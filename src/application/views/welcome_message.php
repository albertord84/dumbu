<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>DUMBU</title>
        <link rel="shortcut icon" href="<?php echo base_url().'assets/img/icon.png'?>">
        <style type="text/css">                       
            a { color: white; background-color: transparent; font-weight: normal; font-size: 12px;}                        
            #container {z-index: 1; position: absolute; left: 0%; top:0%; width: 100%; height: 100%;}            
            #head { position: absolute;  background-color: #0F0F0F; top:0%; height: 10%; width: 100%;}             
            #body {position: absolute; background-color: #2B2B2B; top:10%; height: 74%; width: 100%;}             
            #footer {position: absolute; background-color: #202020; top:84%; height: 16%; width: 100%;}
        </style>
        
        <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/users_login_style.css'?>">    
        <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/js/alert/css/wow-alert.css'?>">        
        
    </head>
    <body style="z-index: 1">        
        <script type="text/javascript">var base_url = '<?php echo base_url();?>'; </script>    
        <script type="text/javascript" src="<?php echo base_url().'assets/js/jquery.js'?>"></script>        
        <!--<script type="text/javascript" src="<?php // echo base_url().'assets/js/alert/js/wow-alert.js'?>" ></script>-->
        <script type="text/javascript">
            var user_active=0;
        </script>
        
        <div id="container">
            <div id="head">                 
                <!--<div id="content_header" style="height: 100%; width:100%"> <?php// if($content_header) echo $content_header; var_dump($content_header);?> </div>-->
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
                    <a  href="<?php echo base_url().'index.php/welcome/sign_in';?>">ASSINAR AGORA</a>
                </div> 

                <div style="position: absolute; top:2%; left: 93%;">
                    <a id="login" href="#">ENTRAR</a>
                </div>

                <div id="update_user" style="position: absolute; top:2%; left: 84%;">
                    <a  href="<?php echo base_url().'index.php/welcome/sign_client_update'?>">ATUALIZAR DADOS</a>
                </div>

                <div id="logout" style="position: absolute; top:2%; left: 94%">
                    <a  href="<?php echo base_url().'index.php/welcome/log_out'?>">SAIR</a>
                </div>

                <div id="my_sesion" style="position: absolute; top:55%; left: 93%;">
                    <a  href="<?php echo base_url().'index.php/welcome/panel_client'?>">PERFIS</a>
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
                
                
            </div>
            
            <div id="body">
                <div id="content_body" style="height: 100%;"> <?php if($content) echo $content; ?> </div>
            </div>
            
           
           <div id="footer">
               <div id="content_footer" style="height: 100%; width:100%"> <?php if($content_footer) echo $content_footer; ?> </div>                                
            </div>
        </div>
                      
        <script type="text/javascript">
            user_active=<?php if($user_active===true) echo 1;  else echo 0;?>;
        </script>
       <script type="text/javascript" src="<?php echo base_url().'assets/js/an_user_active.js'?>" ></script>    
       <script type="text/javascript" src="<?php echo base_url().'assets/js/user.js'?>" ></script>     
       
    </body>
</html>