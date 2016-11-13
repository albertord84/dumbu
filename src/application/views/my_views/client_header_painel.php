
<div style="z-index: 1;color:white; position: absolute; top:15%; height: 60%; left: 43%; width:14%">
    <a href="#<?php //echo base_url().'index.php/welcome/'?>"><img src="<?php echo base_url().'assets/img/dumbu_logo_png.png'?>" style=" z-index: 2;position: absolute; top:0%; height: 100%; left: 0%; width: 100%;"></a>
</div>

<div id="talkme" style="position: absolute; top:2%; left: 75%;">
    <a href="#lnk_talkme">FALE CONOSCO</a>
</div>
    
<div id="update_user" style="position: absolute; top:2%; left: 84%;">
    <a  href="#lnk_update">ATUALIZAR DADOS</a>
</div>

<div id="logout" style="position: absolute; top:2%; left: 94%">
    <a  href="<?php echo base_url().'index.php/welcome/log_out'?>">SAIR</a>
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


