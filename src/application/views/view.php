<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="google-site-verification" content="0E7a5_j1eX8dCmhyUPM9-gWKQCyrBshagGL5_BqVvOc" />
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">        
        <title>DUMBU</title>
        
        <link rel="shortcut icon" href="<?php echo base_url().'assets/img/icon.png'?>">    
        <link href="<?php echo base_url().'assets/bootstrap/css/bootstrap.min.css'?>" rel="stylesheet">
        <link href="<?php echo base_url().'assets/bootstrap/css/style.css'?>" rel="stylesheet">
        
        <link rel="stylesheet" href="<?php echo base_url().'assets/labda/ladda-themeless.min.css'?>">
        
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script type="text/javascript" src="<?php echo base_url().'assets/js/jquery.js'?>"></script>
        <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>-->

        <script src="<?php echo base_url().'assets/bootstrap/js/bootstrap.min.js'?>"></script>        
        
        <script src="<?php echo base_url().'assets/labda/spin.min.js'?>"></script>
        <script src="<?php echo base_url().'assets/labda/ladda.min.js'?>"></script>        
        
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script type="text/javascript">var base_url = '<?php echo base_url();?>';</script> 
        <script type="text/javascript" src="<?php echo base_url().'assets/js/user.js'?>" ></script>
        <script type="text/javascript" src="<?php echo base_url().'assets/js/sign_painel.js'?>"></script>
        <script type="text/javascript" src="<?php echo base_url().'assets/js/talkme_painel.js';?>"></script>        
                
  </head>
  <body>
    <?php include_once("analyticstracking.php") ?>
    <?php include_once("remarketing.php")?>
    <div class="container shadow">
        <!--SECTION 1-->
            <div class="row body-section-2 center" style="background-image:url('<?php echo base_url()."assets/img/black-friday/BG.png"; ?>'); background-repeat:no-repeat; background-size:cover;">
                <?php echo $section1;?> 
            </div>

        <!--SECTION 2-->
            <div class="row body-section-2 center">
                <A name="lnk_how_function"></A>
                <?php echo $section2; ?>          
            </div>

        <!--SECTION 3-->
            <div class="row body-section-3 center">
                <A name="lnk_sign_in_now"></A>
                <?php echo $section3; ?>
            </div>

        <!--SECTION 4-->
            <div class="row body-section-4 center">
                <A name="lnk_talkme"></A>
                <?php echo $section4; ?>
            </div>

        <!--SECTION 5-->
            <div class="row body-section-5 center">                           
                <?php echo $section5; ?>            
            </div>
    </div>

    </body>
</html>