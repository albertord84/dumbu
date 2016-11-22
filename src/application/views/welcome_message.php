<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
    <head>
            <meta name="google-site-verification" content="0E7a5_j1eX8dCmhyUPM9-gWKQCyrBshagGL5_BqVvOc" />
            <meta charset="utf-8">
            <title>DUMBU</title>
            <link rel="shortcut icon" href="<?php echo base_url().'assets/img/icon.png'?>">        
            <style type="text/css">
                a { color: white; background-color: transparent; font-weight: normal; font-size: 12px;}            
            </style>        
            <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/users_login_style.css'?>">    
            <link rel="stylesheet" type="text/css" href="<?php //echo base_url().'assets/js/alert/css/wow-alert.css'?>">                
            
            
            
            <!--<script type="text/javascript" async="" src="menu_files/ga.js"></script>
            <script type="text/javascript" src="menu_files/jquery-1.js"></script>
            <script type="text/javascript" src="menu_files/jquery.js"></script>
            <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/js/menu/fixedMenu_style2.css'?>">
            <script>
                //$('document').ready(function(){
                  //  $('.menu').fixedMenu();
               // });
            </script>-->
            
            
            
            
            
    </head>
    
    <body style="z-index:1">
            <script type="text/javascript">var base_url = '<?php echo base_url();?>'; </script>    
            <script type="text/javascript" src="<?php echo base_url().'assets/js/jquery.js'?>"></script>
            <script type="text/javascript" src="<?php echo base_url().'assets/js/user.js'?>" ></script>
            <div style="position:absolute; top:0%; left:0%; height:100%;width:100%">
            <!--SECTION 1-->
                <div id="section1"              style="z-index:5; position:absolute;  background-color:white;   top:0%;   left:0%;     height:100%;    width:100%;">                
                    <div id="head_section1"     style="z-index:6; position:absolute;  background-color:#0F0F0F;  top:0%;   left:0%;     height:10%;     width:100%;">                
                        <?php if($head_section1) echo $head_section1; ?> 
                    </div>            
                    <div id="body_section1"     style="z-index:2; position:absolute;  background-color:#2B2B2B;  top:10%;   left:0%;    height:75%;     width:100%;">                
                        <?php if($head_section1) echo $body_section1; ?> 
                    </div>            
                    <div id="footer_section1"   style="z-index:2; position:absolute;  background-color:#202020;  top:85%;   left:0%;    height:15%;     width:100%;">
                        <?php if($head_section1) echo $footer_section1; ?> 
                    </div>
                </div>

            <!--SECTION 2-->
                <div id="section2"              style="z-index:1; position:absolute;  background-color:white;   top:100%;   left:0%;   height:65%;     width:100%;">                
                    <div id="body_section2"     style="z-index:2; position:absolute;  background-color:gray;     top:0%;     left:0%;   height:100%;    width:100%;">                
                        <A name="lnk_how_function"></A>
                        <?php if($body_section2) echo $body_section2; ?> 
                    </div>            
                </div>

            <!--SECTION 3-->
                <div id="section3"              style="z-index:1; position:absolute;  background-color:white;   top:165%;   left:0%;   height:75%;     width:100%;">                
                    <div id="body_section3"     style="z-index:2; position:absolute;  background-color:green;    top:0%;     left:0%;   height:100%;    width:100%;">
                        <A name="lnk_sign_in_now"></A>
                        <?php if($body_section3) echo $body_section3; ?>
                    </div>            
                </div>

            <!--SECTION 4-->
                <div id="section4"              style="z-index:1; position:absolute;  background-color:white;   top:240%;   left:0%;   height:75%; width:100%;">                
                    <div id="body_section4"     style="z-index:2; position:absolute;  background-color:white;      top:0%;     left:0%;   height:100%; width:100%;">                
                        <A name="lnk_talkme"></A>
                        <?php if($body_section4) echo $body_section4; ?>
                    </div>            
                </div>

            <!--SECTION 5-->
                <div id="section5"              style="z-index:1; position:absolute;  background-color:white;   top:315%;   left:0%;   height:15%; width:100%;">                
                    <div id="body_section5"     style="z-index:2; position:absolute;  background-color:white;      top:0%;     left:0%;   height:100%; width:100%;">                
                        <?php if($body_section5) echo $body_section5; ?>
                    </div>
                </div>
        </div>
    </body>
</html>