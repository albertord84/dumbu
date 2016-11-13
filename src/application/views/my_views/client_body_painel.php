

<script type="text/javascript" src="<?php echo base_url().'assets/js/client_painel.js'?>"></script>
<script type="text/javascript">
    var profiles=<?php echo json_encode($profiles);?>;
    var MAX_NUM_PROFILES=<?php echo $MAX_NUM_PROFILES; ?>;
</script>

<link type="text/css" rel="stylesheet" href="<?php echo base_url().'assets/css/insert_profile.css'?>">

<div style="width:100%;height:100%; background-color:white">
    <div style="color:black;text-align:center; position:absolute; left:35%; top:5%; height:6%; width:30%; ">
        <b style="font-size:18px; font-family:sans-serif;">PERFIS DE REFERÊNCIA</b>
    </div>
    
    <div id="my_profile" style="text-align:center; position:absolute; left:16%; top:14%; height:28%; width:10%;">       
        <img id="my_img" src="<?php echo $my_img_profile;?>" style="box-shadow:2px 0px 5px 2px #424242; position:absolute; left:8%; top:1%;height:83%; width:83%;  border-radius:100px;"/>
        <div style="position:absolute; top:78%; left:0%; width:100%; height:25%;">
            <b><p id="my_name" style="font-size:16px; font-family:sans-serif;"><?php echo $my_login_profile;?></p></b>
        </div>
    </div>
    
    <div id="container_profiles" style="box-shadow:2px 0px 5px 2px #424242; z-index:1;overflow:hidden; background-color:#F4F4F4; position:absolute; left:27%; top:14%;height:24%; width:55%; border-radius:100px;">
        <div id="actual_profiles" style="z-index:2; position:absolute; left:0%; top:0%;height:100%; width:85%; ">
            <!--<img id="missing_referrence_profiles" src="<?php //echo base_url().'assets/img/reference_profiles_here.png'?>" style="z-index:8; visibility:hidden; display:none; position:absolute; left:25%; top:40%;height:20%; width:50%;"/>-->
            <b id="missing_referrence_profiles" style="font-family:sans-serif; font-size:1.5em; color:silver;  z-index:8; visibility:hidden; display:none; position:absolute; left:15%; top:40%;height:20%; width:75%;">
                Adicione seus perfis de referência aqui ...
            </b>
            
            <div id="reference_profile0"  style="z-index:3; visibility:hidden; display:none; text-align:center;font-size:20px; font-family:sans-serif; position:absolute; left:4%; top:1%;height:98%; width:20%;">                
                <img id="img_ref_prof0" src="" style="box-shadow:2px 0px 5px 2px #424242; position:absolute; top:4%; left:12%; width:66%; height:72%; border-radius:50px;">
                <div style="position:absolute; top:74%; left:0%; width:100%; height:25%;">
                    <p id="name_ref_prof0" style="font-size:12px; font-family:sans-serif;"></p>
                </div>
            </div>
            
            <div id="reference_profile1"  style="z-index:3; visibility:hidden; display:none; text-align:center;font-size:20px; font-family:sans-serif; position:absolute; left:22%; top:1%;height:98%; width:20%;">                
                <img id="img_ref_prof1" src="" style="box-shadow:2px 0px 5px 2px #424242; position:absolute; top:4%; left:12%; width:66%; height:72%;  border-radius:50px;">
                <div style="position:absolute; top:74%; left:0%; width:100%; height:25%;">
                    <p id="name_ref_prof1" style="font-size:12px; font-family:sans-serif;"></p>
                </div>
            </div>
            
            <div id="reference_profile2"  style="z-index:3; visibility:hidden; display:none; text-align:center;font-size:20px; font-family:sans-serif; position:absolute; left:40%; top:1%;height:98%; width:20%;">                
                <img id="img_ref_prof2" src="" style="box-shadow:2px 0px 5px 2px #424242; position:absolute; top:4%; left:12%; width:66%; height:72%;  border-radius:50px;">
                <div style="position:absolute; top:74%; left:0%; width:100%; height:25%;">
                    <p id="name_ref_prof2" style="font-size:12px; font-family:sans-serif;"></p>
                </div>
            </div>
            
            <div id="reference_profile3"  style="z-index:3; visibility:hidden; display:none; text-align:center;font-size:20px; font-family:sans-serif; position:absolute; left:58%; top:1%;height:98%; width:20%;">                
                <img id="img_ref_prof3" src="" style="box-shadow:2px 0px 5px 2px #424242; position:absolute; top:4%; left:12%; width:66%; height:72%;  border-radius:50px;">
                <div style="position:absolute; top:74%; left:0%; width:100%; height:25%;">
                    <p id="name_ref_prof3" style="font-size:12px; font-family:sans-serif;"></p>
                </div>
            </div>
            
            <div id="reference_profile4"  style="z-index:3; visibility:hidden; display:none; text-align:center;font-size:20px; font-family:sans-serif; position:absolute; left:76%; top:1%;height:98%; width:20%;">                
                <img id="img_ref_prof4" src="" style="box-shadow:2px 0px 5px 2px #424242; position:absolute; top:4%; left:12%; width:66%; height:72%; border-radius:50px;">
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
    
    
    <div style="background-color:#F4F4F4; position:absolute; left:12%; top:50%; height:42%; width:76%; border:1px solid silver; border-radius:5px;">
        <div id="important_warning" style=" align-content:left; position:absolute; top:4%; left:2%; width: 25%;height: 9%">
            <b style="font-size:110%; font-family:sans-serif">AVISOS IMPORTANTES</b>
            <!--<img height="80%" width="90%"  src="<?php //echo base_url().'assets/img/avisos_importantes_titulo.png'?>" />-->
        </div>
        <div id="important_warning" style=" align-content:left; position:absolute; top:4%; left:75%; width: 25%;height: 9%">
            <b style="font-size:110%; font-family:sans-serif">STATUS:</b>
            <?php
                if($status['status_id']==1)
                    echo '<b id="status_text" style="color:green; font-size:110%; font-family:sans-serif">'.$status["status_name"].'</b>';
                else
                if($status['status_id']==2)
                    echo '<b id="status_text" style="color:red; font-size:110%; font-family:sans-serif">'.$status["status_name"].'</b>';
                else
                    echo '<b id="status_text" style="color:orange; font-size:110%; font-family:sans-serif">'.$status["status_name"].'</b>';
            ?> 
            
            <b id='client_status' style="font-size:110%; font-family:sans-serif"></b>
        </div>
        <div id="important_warning" style="align-content:left; position:absolute; top:16%; left:2%; width: 96%;height: 81%">                    
            
            <ul id='list_warnings' type='disk' style="font-size: 1em; font-family:sans-serif">
                <?php
                    if($status['status_id']==2)
                        echo '<li style="color:red; margin-bottom:0.7em;">'.$status["status_message"];
                    else
                    if($status['status_id']!=1)
                        echo '<li style="color:red; margin-bottom:0.7em;">'.$status["status_message"];
                    else
                        echo '<li style="margin-bottom:0.7em;"> O Instagram só permite que você siga alredor de 7000 perfis. Precisamos que você siga máximo 6000 perfis para iniciar a ferramenta;';                   
                ?>                
                <li style="margin-bottom:0.7em;"> Nossa ferramenta é interligada ao Instagram, por isso, pode sofrer variações no desempenho a cada atualização feita pelo Instagram;
                <li style="margin-bottom:0.7em;"> Casso altere seu nome de usuário ou senha no Instagram, o seviço de Dumbu será desconetado temporáriamente. Somente precisa fazer login no Dumbu para atualizar as suas credenciais e continuar recevendo o serviço;
                <li style="margin-bottom:0.7em;"> Nunca utilice outras ferramentas junto a Dumbu.           
            </ul>
           
            
            
            
            
            <!--<div id="important_warning1" style="align-content:left; position:absolute; top:0%; left:0%; width: 100%;height: 17%">
                <img src="<?php //echo base_url().'assets/img/aviso_importante1.png'?>" />
            </div>            
            <div id="important_warning2" style="align-content:left; position:absolute; top:22%; left:0%; width: 100%;height: 17%">
                <img src="<?php //echo base_url().'assets/img/aviso_importante2.png'?>" />
            </div>            
            <div id="important_warning3" style="align-content:left; position:absolute; top:44%; left:0%; width: 100%;height: 17%">
                <img src="<?php //echo base_url().'assets/img/aviso_importante3.png'?>" />
            </div>            
            <div id="important_warning4" style="align-content:left; position:absolute; top:76%; left:0%; width: 100%;height: 17%">
                <img src="<?php //echo base_url().'assets/img/aviso_importante4.png'?>" />
            </div>-->
        </div>
    </div>

</div>
