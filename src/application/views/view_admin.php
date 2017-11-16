<!DOCTYPE html>
<html lang="en">
  <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Ganhar seguidores no Instagram. Aumente seus seguidores reais e qualificados de forma segmentada no Instagram. Followers, curtidas, geolocalizção, direct">
        <meta name="keywords" content="ganhar, seguidores, Instagram, seguidores segmentados, curtidas, followers, geolocalizção, direct, vendas">
        <meta name="revisit-after" content="7 days">
        <meta name="robots" content="index,follow">
        <meta name="distribution" content="global">
        <title>DUMBU</title>
        
        <link rel="shortcut icon" href="<?php echo base_url().'assets/img/icon.png'?>">    
        <link href="<?php echo base_url().'assets/bootstrap/css/bootstrap.min.css'?>" rel="stylesheet">
        
        <link href="<?php echo base_url().'assets/css/style1.css'?>" rel="stylesheet">
        
        <link rel="stylesheet" href="<?php echo base_url().'assets/css/ladda-themeless.min.css'?>">
        
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script type="text/javascript" src="<?php echo base_url().'assets/js/jquery.js'?>"></script>
        <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>-->
        <script src="<?php echo base_url().'assets/bootstrap/js/bootstrap.min.js'?>"></script>
        
        <script src="<?php echo base_url().'assets/js/spin.min.js'?>"></script>
        <script src="<?php echo base_url().'assets/js/ladda.min.js'?>"></script>
        
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script type="text/javascript">var base_url = '<?php echo base_url();?>'; </script>    
        <script type="text/javascript" src="<?php echo base_url().'assets/js/admin.js?1.0.0';?>"></script>
        <script type="text/javascript" src="<?php echo base_url().'assets/js/modal_alert_message.js';?>"></script>
        
        <!-- jQuery UI Datepicker - Select a Date Range -->
        <link rel="stylesheet" href="<?php echo base_url().'assets/jquery-ui-1.12.1/jquery-ui.css';?>">
        <link rel="stylesheet" href="https://jqueryui.com/resources/demos/style.css">
        <script src="<?php echo base_url().'assets/jquery-ui-1.12.1/jquery-ui.js';?>"></script>
        <script>
        $( function() {
          var dateFormat = "mm/dd/yy",
            from = $( "#date_from" )
              .datepicker({
                changeMonth: true,
                numberOfMonths: 1
              })
              .on( "change", function() {
                to.datepicker( "option", "minDate", getDate( this ) );
              }),
            to = $( "#date_to" ).datepicker({
              changeMonth: true,
              numberOfMonths: 1
            })
            .on( "change", function() {
              from.datepicker( "option", "maxDate", getDate( this ) );
            });
            
            var dateFormat2 = "mm/dd/yy",
            from2 = $( "#status_date_from" )
              .datepicker({
                changeMonth: true,
                numberOfMonths: 1
              })
              .on( "change", function() {
                to2.datepicker( "option", "minDate", getDate2( this ) );
              }),
            to2 = $( "#status_date_to" ).datepicker({
              changeMonth: true,
              numberOfMonths: 1
            })
            .on( "change", function() {
              from2.datepicker( "option", "maxDate", getDate2( this ) );
            });
            
            var dateFormat3 = "mm/dd/yy",
            from3 = $( "#creation_date_from" )
              .datepicker({
                changeMonth: true,
                numberOfMonths: 1
              })
              .on( "change", function() {
                to3.datepicker( "option", "minDate", getDate3( this ) );
              }),
            to3 = $( "#creation_date_to" ).datepicker({
              changeMonth: true,
              numberOfMonths: 1
            })
            .on( "change", function() {
              from3.datepicker( "option", "maxDate", getDate3( this ) );
            });
            
            $( "#event_date" ).datepicker();

          function getDate( element ) {
            var date;
            try {
              date = $.datepicker.parseDate( dateFormat, element.value );
            } catch( error ) {
              date = null;
            }

            return date;
          }
          
          function getDate2( element ) {
            var date;
            try {
              date = $.datepicker.parseDate( dateFormat2, element.value );
            } catch( error ) {
              date = null;
            }

            return date;
          }
          
          function getDate3( element ) {
            var date;
            try {
              date = $.datepicker.parseDate( dateFormat3, element.value );
            } catch( error ) {
              date = null;
            }

            return date;
          }
        } );
        </script>
        
        <!-- Performance Chart -->
        <script type="text/javascript">followings_data= jQuery.parseJSON('<?php echo $followings; ?>');</script>
        <script type="text/javascript">followers_data= jQuery.parseJSON('<?php echo $followers; ?>'); </script>
        <script type="text/javascript">var language = 'PT';</script>
        <script type="text/javascript" src="<?php echo base_url() . 'assets/canvasjs-1.9.6/canvasjs.min.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo base_url() . 'assets/js/chart.js'; ?>"></script>
        
        <?php include_once("pixel_facebook.php")?>
  </head>
  <body>
    <?php include_once("analyticstracking.php") ?>
    <?php include_once("remarketing.php")?>
    <div>
    <!--<div class="container shadow">-->
        <!--SECTION 1-->
            <div class="row header-section-1">
                <?php echo $section1; ?>                 
            </div>
    
            <div class="row client-body-section-1">
                <?php echo $section2; ?> 
            </div> 
        
            <div class="row body-section-5 center"> 
                <?php echo $section3; ?> 
            </div>
    </div>
      <!--modal_container_alert_message-->
        <div class="modal fade" style="top:30%" id="modal_alert_message" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div id="modal_container_alert_message" class="modal-dialog modal-sm" role="document">                                                          
                <div class="modal-content">
                    <div class="modal-header">
                        <button id="btn_modal_close" type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <img src="<?php echo base_url() . 'assets/images/FECHAR.png'; ?>"> <!--<span aria-hidden="true">&times;</span>-->
                        </button>
                        <h5 class="modal-title" id="myModalLabel"><b>Mensagem</b></h5>                        
                    </div>
                    <div class="modal-body">                                            
                        <p id="message_text"></p>                        
                    </div>
                    <div class="modal-footer text-center">
                        <button id="accept_modal_alert_message" type="button" class="btn btn-default active text-center ladda-button" data-style="expand-left" data-spinner-color="#ffffff">
                            <span class="ladda-label"><div style="color:white; font-weight:bold">ACEITAR</div></span>
                        </button>
                    </div>
                </div>
            </div>                                                        
        </div> 
  </body>
</html>