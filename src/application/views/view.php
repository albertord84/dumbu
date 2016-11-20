<!DOCTYPE html>
<html lang="en">
  <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">        
        <title>DUMBU</title>
        <link rel="shortcut icon" href="<?php echo base_url().'assets/img/icon.png'?>">    


        <link href="<?php echo base_url().'assets/bootstrap/css/bootstrap.min.css'?>" rel="stylesheet">
        <link href="<?php echo base_url().'assets/bootstrap/css/style.css'?>" rel="stylesheet">
        
    
  </head>
  <body>
    <div class="container shadow">
        <!--SECTION 1-->
            <div class="row header-section-1">
                <?php echo $head_section1; ?>                 
            </div>
    
            <div class="row body-section-1">
                <?php if($body_section1) echo $body_section1; ?> 
            </div>
            <div class="row footter-section-1 center">                
                <?php echo $footer_section1; ?> 
            </div>
        
        <!--SECTION 2-->
        <div class="row body-section-2 center">
            <A name="lnk_how_function"></A>
            <?php echo $body_section2; ?>          
        </div>
        
        <!--SECTION 3-->
        <div class="row body-section-3 center">
            <A name="lnk_sign_in_now"></A>
            <?php echo $body_section3; ?>
        </div>
        
        <!--SECTION 4-->
        <div class="row body-section-4 center">
            <A name="lnk_talkme"></A>
            <?php echo $body_section4; ?>
        </div>

        <!--SECTION 5-->
        <div class="row body-section-5 center">                           
            <?php echo $body_section5; ?>            
        </div>
        
    </div>
	
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    
    <script src="<?php echo base_url().'assets/bootstrap/js/bootstrap.min.js'?>"></script>
  </body>
</html>