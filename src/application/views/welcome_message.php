<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>DUMBU</title>
        <style type="text/css">                       
            a { color: white; background-color: transparent; font-weight: normal; font-size: 12px;}            
            p.footer { text-align: right;font-size: 11px; border-top: 1px solid #D0D0D0; line-height: 32px; padding: 0 10px 0 10px; margin: 20px 0 0 0;}
            #container {position: absolute; left: 0%; top:0%; width: 100%; height: 100%;}            
            #head { position: absolute;  background-color: #0F0F0F; top:0%; height: 10%; width: 100%;}             
            #body {position: absolute; background-color: #2B2B2B; top:10%; height: 70%; width: 100%;}             
            #footer {position: absolute; background-color: #202020; top:80%; height: 20%; width: 100%;}
        </style>
    </head>    
    
    <script type="text/javascript">var base_url = '<?php echo base_url();?>'; </script>
    <script type="text/javascript">var user_active=false; </script>
    <script type="text/javascript" src="<?php echo base_url().'assets/js/jquery.js'?>"></script>
    
    
    
    <script type="text/javascript" src="<?php echo base_url().'assets/js/an_user_active.js'?>" ></script>    
    <script type="text/javascript" src="<?php echo base_url().'assets/js/user.js'?>" ></script>
    
    
   
    <body style="z-index: 1">        
        <div id="container">
            <div id="head"> 
                <h2 style="color:white; position: absolute; top:2%; left: 47.5%; width:120px">dumbu</h2>      
                <div style="position: absolute; top:2%; left: 70%;">
                    <a id="how_function" href="<?php echo base_url().'index.php/welcome/how_function'?>">COMO FUNCIONA</a>
                </div>
                <div style="position: absolute; top:2%; left: 80%;">
                    <a  id="sing_in" href="<?php echo base_url().'index.php/welcome/sing_in'?>">ASINAR AGORA</a>
                </div>                
                <div style="position: absolute; top:2%; left: 90%;"> 
                    <a id="login" href="<?php echo base_url().'index.php/welcome/log_in'?>">ENTRAR</a>
                </div>
                <div style="position: absolute; top:2%; left: 90%;"> 
                    <a id="logout" href="<?php echo base_url().'index.php/welcome/log_out'?>">SAIR</a>
                </div>
                
                <div style="position: absolute; top:2%; left: 80%;"> 
                    <a id="update_user" href="<?php echo base_url().'index.php/welcome/update_client'?>">ACTUALIZAR DADOS</a>
                </div>
            </div>
            
            <div id="body">
                <div id="content"> <?php if($content) echo $content; ?> </div>
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
        
    </body>
</html>