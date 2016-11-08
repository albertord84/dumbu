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
            #body {position: absolute; background-color: #2B2B2B; top:10%; height: 74%; width: 100%;}             
            #footer {position: absolute; background-color: #202020; top:84%; height: 16%; width: 100%;}
        </style>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/users_login_style.css'?>">        
    
        <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/js/alert/css/wow-alert.css'?>">        
        
        
    </head>    
    
    <script type="text/javascript">var base_url = '<?php echo base_url();?>'; </script>
    <script type="text/javascript">var user_active;</script>
    <script type="text/javascript" src="<?php echo base_url().'assets/js/jquery.js'?>"></script>
    
    <script type="text/javascript" src="<?php echo base_url().'assets/js/user.js'?>" ></script>
    
    <script type="text/javascript" src="<?php echo base_url().'assets/js/alert/js/wow-alert.js'?>" ></script>
    
   
    <body style="z-index: 1">        
        <div id="container">
            <div id="head">                 
                <div id="content_header" style="height: 100%; width:100%"> <?php if($content_header) echo $content_header; ?> </div>
            </div>
            
            <div id="body">
                <div id="content_body" style="height: 100%;"> <?php if($content) echo $content; ?> </div>
            </div>
            
           
           <div id="footer">
               <div id="content_footer" style="height: 100%; width:100%"> <?php if($content_footer) echo $content_footer; ?> </div>                                
            </div>
        </div>
        
        
        <script type="text/javascript"><?php if($user_active)echo 'user_active='.$user_active;?></script>
        <script type="text/javascript" src="<?php echo base_url().'assets/js/an_user_active.js'?>" ></script>    
        
    </body>
</html>