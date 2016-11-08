

<script type="text/javascript" src="<?php echo base_url().'assets/js/client_painel.js'?>"></script>
<script type="text/javascript" src="<?php echo base_url().'assets/js/an_user_active.js';?>"></script>
<script type="text/javascript">profiles=<?php echo json_encode($profiles)?>; MAX_NUM_PROFILES=3<?php //TODO:echo $MAX_NUM_PROFILES?>;</script>

<link type="text/css" rel="stylesheet" href="<?php echo base_url().'assets/css/insert_profile.css'?>">

<div style="width:100%;height:100%; background-color:white">
    <div style="color:black;text-align:center; position:absolute; left:35%; top:5%; height:6%; width:30%; ">
        <b style="font-size:16px; font-family:sans-serif;">PERFIS DE REFERÊNCIA</b>
    </div>
    
    <div id="my_profile" style="text-align:center; position:absolute; left:16%; top:10%; height:28%; width:10%;">       
        <img id="my_img" src="<?php echo $my_img_profile;?>" style=" position:absolute; left:8%; top:1%;height:83%; width:83%; border:1px solid silver; border-radius:100px;"/>
        <div style="position:absolute; top:78%; left:0%; width:100%; height:25%;">
            <b><p id="my_name" style="font-size:16px; font-family:sans-serif;"><?php echo $my_login_profile;?></p></b>
        </div>
    </div>
    
    <div id="container_profiles" style="z-index:1;overflow:hidden; background-color:#F4F4F4; position:absolute; left:27%; top:10%;height:24%; width:55%; border:1px solid silver; border-radius:100px;">
        <div id="actual_profiles" style="z-index:2; position:absolute; left:0%; top:0%;height:100%; width:85%; ">
            
            <div id="reference_profile0"  style="z-index:3; visibility:hidden; display:none; text-align:center;font-size:20px; font-family:sans-serif; position:absolute; left:4%; top:1%;height:98%; width:20%;">                
                <img id="img_ref_prof0" src="" style=" position:absolute; top:4%; left:12%; width:66%; height:72%; border:1px solid silver; border-radius:50px;">
                <div style="position:absolute; top:74%; left:0%; width:100%; height:25%;">
                    <p id="name_ref_prof0" style="font-size:12px; font-family:sans-serif;"></p>
                </div>
            </div>
            
            <div id="reference_profile1"  style="z-index:3; visibility:hidden; display:none; text-align:center;font-size:20px; font-family:sans-serif; position:absolute; left:22%; top:1%;height:98%; width:20%;">                
                <img id="img_ref_prof1" src="" style=" position:absolute; top:4%; left:12%; width:66%; height:72%; border:1px solid silver; border-radius:50px;">
                <div style="position:absolute; top:74%; left:0%; width:100%; height:25%;">
                    <p id="name_ref_prof1" style="font-size:12px; font-family:sans-serif;"></p>
                </div>
            </div>
            
            <div id="reference_profile2"  style="z-index:3; visibility:hidden; display:none; text-align:center;font-size:20px; font-family:sans-serif; position:absolute; left:40%; top:1%;height:98%; width:20%;">                
                <img id="img_ref_prof2" src="" style=" position:absolute; top:4%; left:12%; width:66%; height:72%; border:1px solid silver; border-radius:50px;">
                <div style="position:absolute; top:74%; left:0%; width:100%; height:25%;">
                    <p id="name_ref_prof2" style="font-size:12px; font-family:sans-serif;"></p>
                </div>
            </div>
            
            <div id="reference_profile3"  style="z-index:3; visibility:hidden; display:none; text-align:center;font-size:20px; font-family:sans-serif; position:absolute; left:58%; top:1%;height:98%; width:20%;">                
                <img id="img_ref_prof3" src="" style=" position:absolute; top:4%; left:12%; width:66%; height:72%; border:1px solid silver; border-radius:50px;">
                <div style="position:absolute; top:74%; left:0%; width:100%; height:25%;">
                    <p id="name_ref_prof3" style="font-size:12px; font-family:sans-serif;"></p>
                </div>
            </div>
            
            <div id="reference_profile4"  style="z-index:3; visibility:hidden; display:none; text-align:center;font-size:20px; font-family:sans-serif; position:absolute; left:76%; top:1%;height:98%; width:20%;">                
                <img id="img_ref_prof4" src="" style=" position:absolute; top:4%; left:12%; width:66%; height:72%; border:1px solid silver; border-radius:50px;">
                <div style="position:absolute; top:74%; left:0%; width:100%; height:25%;">
                    <p id="name_ref_prof4" style="font-size:12px; font-family:sans-serif;"></p>
                </div>
            </div>
        </div>
        <div id="action_profiles" style="z-index:4; position:absolute; left:78%; top:0%;height:100%; width:20%; ">             
            <input type="submit" id="adding_profile" value="+" style=" color:white; font-size:28px; font-family:sans-serif; background-color:#6BA7F5; position:absolute; top:20%; left:45%; width:80px; height:80px; border:1px solid silver; border-radius:50px;"/>            
        </div>
    </div>
    <center>            
        <div id = "insert_profile_form">                
            <form method = "post" action = "">
                <img type = "image"       id = "close_palnel_insert_profile"   src = "<?php echo base_url().'assets/img/close.png'?>">
                <input type = "text"      id = "login_profile"         placeholder = "Perfil">                    
                <input type = "button"    id = "btn_insert_profile"       value = "Aceitar">
            </form>
            <img id="waiting_inser_profile" src="<?php echo base_url().'assets/img/waiting.gif'?>"/>
        </div>
    </center>
    
    
    <div style="background-color:#F4F4F4; position:absolute; left:14%; top:50%; height:42%; width:71%; border:1px solid silver; border-radius:5px;">
        <div id="important_warning" style="align-content:left; position:absolute; top:4%; left:2%; width: 25%;height: 9%">
            <img height="80%" src="<?php echo base_url().'assets/img/avisos_importantes_titulo.png'?>" />
        </div>
        <div id="important_warning" style="align-content:left; position:absolute; top:18%; left:4%; width: 94%;height: 81%">
            <div id="important_warning1" style="align-content:left; position:absolute; top:0%; left:0%; width: 100%;height: 17%">
                <img src="<?php echo base_url().'assets/img/aviso_importante1.png'?>" />
            </div>
            
            <div id="important_warning2" style="align-content:left; position:absolute; top:22%; left:0%; width: 100%;height: 17%">
                <img src="<?php echo base_url().'assets/img/aviso_importante2.png'?>" />
            </div>
            
            <div id="important_warning3" style="align-content:left; position:absolute; top:44%; left:0%; width: 100%;height: 17%">
                <img src="<?php echo base_url().'assets/img/aviso_importante3.png'?>" />
            </div>
            
            <div id="important_warning4" style="align-content:left; position:absolute; top:76%; left:0%; width: 100%;height: 17%">
                <img src="<?php echo base_url().'assets/img/aviso_importante4.png'?>" />
            </div>
            
        </div>
    </div>

    
    
    <!--<div style="color:black;  position:absolute; left:27%; top:40%; height:25%; width:20%; border:1px solid silver; border-radius:5px;">
        <b style="position:absolute; top:20%; left:40%; font-size:18px; font-family:sans-serif;">STATUS</b>
        <div style="position:absolute; top:50%; left:42%; font-size:18px; font-family:sans-serif;">
            <div class="ui-switchbutton ui-switchbutton-default ui-switchbutton-ios5 ui-state-active" >
                <label class="ui-switchbutton-disabled" style="width:57px;">
                    <span style="width:33px; margin-right:-38px;">OFF</span>
                </label>
                <label class="ui-switchbutton-enabled" style="width:38px;">
                    <span style="width:33px; margin-left:0px;">ON</span>
                </label>
                <div class="ui-switchbutton-handle" style="width:34px; left:38px;">                    
                </div>
                <input  id="switch_status" checked="checked" type="checkbox">
            </div>
        </div>
    </div>
    
    <div style="color:black;  position:absolute; left:53%; top:40%; height:25%; width:20%; border:1px solid silver; border-radius:5px;">
        <b id="senha" style="position:absolute; top:20%; left:40%; font-size:18px; font-family:sans-serif;">SENHA</b>        
    </div>    
   -->
</div>












<!--
<script type="text/javascript" src="<?php //echo base_url().'assets/js/demo/jquery-1.js'?>"></script>
<script type="text/javascript" src="<?php //echo base_url().'assets/js/demo/jquery_002.js'?>"></script>
<script type="text/javascript" src="<?php //echo base_url().'assets/js/demo/jquery-ui-1.js'?>"></script>
<script type="text/javascript" src="<?php //echo base_url().'assets/js/demo/jquery1.js'?>"></script>
<script type="text/javascript" src="<?php //echo base_url().'assets/js/demo/demo.js'?>"></script>
<link type="text/css" rel="stylesheet" href="<?php //echo base_url().'assets/js/demo/ui.css'?>">-->


